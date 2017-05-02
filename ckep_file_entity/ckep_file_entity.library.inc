<?php
/**
 * Build the upload form.
 */
function ckep_file_entity_upload_form($form, &$form_state, $settings) {
  // Upload form.
  $form['cke_placeholder_file_upload'] = array(
    '#type' => 'fieldset',
    '#tree' => TRUE,
    '#weight' => 2,
    '#attributes' => array(
      'class' => array('cke-placeholder-library-upload', 'cke-library-fieldset'),
      'id' => 'cke-placeholder-library-upload',
    ),
  );

  $form['cke_placeholder_file_upload']['description'] = array(
    '#markup' => theme('html_tag', array(
      'element' => array(
        '#tag' => 'h4',
        '#value' => t('Any changes made here are applied to the file in the database. When ever the file is used, these will be the default texts.'),
      ),
    )),
  );

  $form['cke_placeholder_file_upload']['new_file'] = array(
    '#type' => 'managed_file',
    '#title' => t('Upload file'),
    '#size' => 48,
    '#description' => t('Pick an image or PDF file to upload.'),
    '#upload_location' => 'public://media/' . date('Y') . '/' . date('W') . '/',
    '#default_value' => NULL,
    '#upload_validators' => array(
      'file_validate_extensions' => array(variable_get('cke_placeholder_allowed_extensions', 'png jpg gif pdf')),
    ),
  );

  $form['cke_placeholder_file_upload']['file_details'] = array(
    '#prefix' => '<div id="cke-placeholder-file-upload-details">',
    '#suffix' => '</div>',
  );

  // This is only non-empty after file has been uploaded.
  if (!empty($form_state['values']['cke_placeholder_file_upload']['new_file'])) {
    $form['cke_placeholder_file_upload']['file_details']['title'] = array(
      '#type' => 'textfield',
      '#size' => 30,
      '#description' => t('The title of the file. This will be used for browsing and searching through the media/PDF library, so write something proper.'),
      '#attributes' => array(
        'placeholder' => t('File title'),
      ),
    );

    $file = file_load($form_state['values']['cke_placeholder_file_upload']['new_file']);

    if ($file->type == 'image') {
      $form['cke_placeholder_file_upload']['file_details']['field_alt'] = array(
        '#type' => 'textfield',
        '#size' => 30,
        '#description' => t('Alternative text for the image. This text is shown if the image is not found and it is used by the screen readers.'),
        '#attributes' => array(
          'placeholder' => t('Image alt'),
        ),
      );

      $form['cke_placeholder_file_upload']['file_details']['field_caption'] = array(
        '#type' => 'textarea',
        '#rows' => 2,
        '#cols' => 30,
        '#description' => t('Edit the caption text, shown to the end user.'),
        '#attributes' => array(
          'placeholder' => t('Caption'),
        ),
      );
    }
    else {
      // TODO: any fields for PDF files besides the title?
    }
  }

  $form['cke_placeholder_file_upload']['submit_upload'] = array(
    '#type' => 'submit',
    '#value' => t('Save in library'),
    '#ajax' => array(
      'callback' => 'ckep_file_entity_library_upload_callback',
      'wrapper' => 'upload_file-wrap',
    ),
    '#submit' => array('ckep_file_entity_library_form_upload_submit'),
    '#name' => 'submit_upload',
  );

  return $form;
}

/**
 * Returns media library tab content for library pane.
 *
 * @param array $form
 *   Library pane form array.
 * @param array $form_state
 *   Form state array.
 *
 * @return array
 *   Media library subpart of the media library form.
 */
function ckep_file_entity_library_form($form, &$form_state, $settings) {
  $form['cke_placeholder_library'] = array(
    '#type' => 'fieldset',
    '#tree' => TRUE,
    '#weight' => 1,
    '#attributes' => array(
      'class' => array('cke-placeholder-library', 'cke-library-fieldset'),
    ),
  );
  // Start with the latest media items displayed. Create a dummy form_state.
  $offset = 0;

  if (empty($form_state['values'])) {
    $params['values']['search'] = '';
    $params['values']['offset'] = 0;
    $params['values']['count'] = variable_get('cke_placeholder_items_per_page', 8);
  }
  else {
    $form_state['rebuild'] = TRUE;
    if ($form_state['triggering_element']['#name'] == 'cke_placeholder_library_next') {
      $offset = $form_state['values']['count'] + $form_state['values']['offset'];
    }
    elseif ($form_state['triggering_element']['#name'] == 'cke_placeholder_library_previous') {
      $offset = $form_state['values']['offset'] - $form_state['values']['count'];
    }
    $form_state['input']['offset'] = $offset;
    $form_state['values']['offset'] = $offset;
    $params = $form_state;
  }

  $form['cke_placeholder_library']['search'] = array(
    '#type' => 'textfield',
    '#size' => 15,
    '#attributes' => array(
      'class' => array('cke-placeholder-form-item cke-placeholder-freetext-search'),
      'placeholder' => t('Search'),
    ),
    '#description' =>
      isset($settings['image'])
        ? t('Image title and file name are accepted in this field.')
        : t('File title and file name are accepted in this field.'),
  );

  $options = array();

  if (is_array($settings)) {
    foreach ($settings as $type) {
      if (!empty($type)) {
        $options[$type] = t($type);
      }
    }
  }

  // We only want a selector if there is more than one option. Otherwise use
  // a hidden field to submit the desired bundle.
  if (count($options) > 1) {
    $options = array_merge(array('all' => t('Select type')), $options);
    $form['cke_placeholder_library']['bundle'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#attributes' => array(
        'class' => array('cke-placeholder-form-item'),
      ),
    );
  }
  else {
    // Take first key from options.
    $bundle = '';

    if (is_array($options)) {
      reset($options);
      $bundle = key($options);
    }

    $form['cke_placeholder_library']['bundle'] = array(
      '#type' => 'hidden',
      '#value' => $bundle,
    );
    $params['values']['cke_placeholder_library']['bundle'] = $bundle;
  }

  $button_ajax = array(
    'wrapper' => 'ckep_file_entity-media-list',
    'callback' => 'ckep_file_entity_source_list',
  );

  $form['cke_placeholder_library']['search_button'] = array(
    '#type' => 'button',
    '#attributes' => array(
      'class' => array('cke-placeholder-library-submit-search'),
    ),
    '#name' => 'cke_placeholder_library_search',
    '#ajax' => $button_ajax,
    '#value' => t('Search'),
  );

  // $latest_media = ckep_file_entity_library_source_list_result($form, $params, 'file');

  // $form['cke_placeholder_library']['results'] = array(
  //   '#type' => 'container',
  //   '#attributes' => array(
  //     'id' => array('cke-placeholder-library-' . $bundle),
  //   ),
  //   '#tree' => FALSE,
  // );

  if ($offset > 0) {
    $form['cke_placeholder_library']['results']['pager_prev'] = array(
      '#type' => 'button',
      '#ajax' => $button_ajax,
      '#value' => t('Previous'),
      '#name' => 'cke_placeholder_library_previous',
      '#attributes' => array(
        'class' => array('cke-placeholder-library-pager'),
      ),
    );
  }
  else {
    // Placeholder element for previous button. This will not be visible.
    $form['cke_placeholder_library']['results']['pager_prev_placeholder'] = array(
      '#type' => 'button',
      '#value' => t('Previous'),
      '#name' => 'cke_placeholder_library_previous_placeholder',
      '#attributes' => array(
        'class' => array('cke-placeholder-library-pager'),
      ),
    );
  }

  if (!empty($latest_media['#markup'])
    && isset($params['cke_placeholder_library']['show_next_button'])
    && $params['cke_placeholder_library']['show_next_button']) {
    $form['cke_placeholder_library']['results']['pager_next'] = array(
      '#type' => 'button',
      '#ajax' => $button_ajax,
      '#value' => t('Next'),
      '#name' => 'cke_placeholder_library_next',
      '#attributes' => array(
        'class' => array('cke-placeholder-library-pager'),
      ),
    );
  }

  // $form['cke_placeholder_library']['results']['items'] = array(
  //   '#markup' => $latest_media['#markup'],
  // );

  $form['cke_placeholder_library']['results']['offset'] = array(
    '#type' => 'hidden',
    '#default_value' => $offset,
  );

  $form['cke_placeholder_library']['results']['count'] = array(
    '#type' => 'hidden',
    '#default_value' => variable_get('cke_placeholder_items_per_page', 8),
  );

  return $form;
}
