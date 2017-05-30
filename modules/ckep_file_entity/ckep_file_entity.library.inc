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
