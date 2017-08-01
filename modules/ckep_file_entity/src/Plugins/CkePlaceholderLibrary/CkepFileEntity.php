<?php

namespace Drupal\cke_file_entity\Plugin\CkePlaceholderLibrary;

use Drupal\cke_placeholder\CkePlaceholderLibraryBase;

/**
 * Provides a CkepFileEntity plugin.
 *
 * CkePlaceholderLibrary(
 *   id = "crep_file_entity",
 *   description = @Translation("Crep File Entity plugin for CKE Placeholder library."),
 *   pane_title = @Translation("Media library"),
 *   list_wrapper_id = "ckep_file_entity-media-list",
 *   module = "ckep_file_entity"
 * )
 */
class CrepFileEntity extends CkePlaceholderLibraryBase {

  /**
   * @
   */
  public function buildForm($form, $form_state) {
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
      isset($settings['image']) ? t('Image title and file name are accepted in this field.') : t('File title and file name are accepted in this field.'),
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

    if (!empty($latest_media['#markup']) && isset($params['cke_placeholder_library']['show_next_button']) && $params['cke_placeholder_library']['show_next_button']) {
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

  /**
   * Get the content that should be available in the media library.
   *
   * @param null|string $type
   *   The entity type to fetch.
   *
   * @return array
   *   Renderable array for the media library.
   */
  public function getList(&$form_state, $search = NULL, $type = NULL, $count = 8, $offset = 0) {
    $files = $this->build_query($offset, $count, $search, $type);

    if (count($files) > $count) {
      $form_state['cke_placeholder_library']['show_next_button'] = TRUE;
      end($files);
      $file_key = key($files);
      unset($files[$file_key]);
    }

    foreach ($files as $file) {
      $file_caption = !empty($file->field_caption) ? $file->field_caption['und'][0]['value'] : '';
      $file_title = !empty($file->field_file_image_title_text) ? $file->field_file_image_title_text['und'][0]['value'] : $file->filename;

      if ($file->type == 'image') {
        $thumb = image_style_url('thumbnail', $file->uri);
      }
      else {
        $thumb = drupal_get_path('module', 'ckep_file_entity') . '/assets/document.png';
      }
      $theme_vars = array(
        'title' => $file_title,
        'thumbnail_url' => $thumb,
        'file_url' => file_create_url($file->uri),
        'caption' => $file_caption,
        'editlink' => sprintf('file/%d/edit', $file->fid),
        'type' => $file->type,
      );
      $file_markup = theme('ckep_file_entity_library_item', $theme_vars);

      $result[] = array(
        'data' => array(
          'id' => $file->fid,
          'type' => $file->type,
          'caption' => $file_caption,
        ),
        'markup' => $file_markup,
      );
    }

    return $result;
  }

  public function build_query($offset = 0, $count = 8, $search_term = NULL, $type = NULL) {
    module_load_include('inc', 'file_entity', 'file_entity.admin');

    // Select files from the DB
    $query = db_select('file_managed', 'fm');
    // $query->condition('fm.type', $bundle);
    $query->groupBy('fm.fid');
    $query->groupBy('fm.uid');
    $query->groupBy('fm.timestamp');

    if (!empty($search_term)) {
      $query->condition('fm.filename', '%' . db_like($search_term) . '%', 'LIKE');
    }

    $result = $query
        ->fields('fm', array('fid', 'uid'))
        ->addTag('file_access')
        ->orderBy('fm.timestamp', 'DESC')
        ->range($offset, $count + 1)
        ->execute()
        ->fetchAllAssoc('fid');

    if (empty($result)) {
      return array();
    }

    $files = file_load_multiple(array_keys($result));
    return $files;
  }

}
