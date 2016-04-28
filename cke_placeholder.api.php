<?php
/**
 * @file
 * API functions for cke_placeholder.
 */

/**
 * Register new tags.
 *
 * @return array
 *   Associative array multi level with placeholder machine name as key. Each
 *   tag must have a 'process' key that defines a function for rendering the
 *   output of the placeholder.
 *   Optional keys are:
 *     * editables => An array of the editable fields to put on the widget.
 *        Must have the structure:
 *        [MACHINE_NAME] => array(
 *          'label' => [LABEL],
 *          'allowed_content' => [CKEditor ACF],
 *        )
 *     * set_alignment => array([alignment values])
 *     * preview_process => The function to render the editor preview.
 */
function hook_cke_placeholder_tags() {
  $tags = array();

  $tags['cke_placeholder_embed'] = array(
    // Specify the editable fields you want in the widget.
    'editables' => array(
      // caption is the name of the field. Must correspond to a data attribute.
      'caption' => array(
        'label' => t('Caption'),
        // ACF notation.
        'allowed_content' => 'strong em i',
      ),
    ),
    // The attributes from the object that can be used as unique key in the
    // editor.
    'key' => array('id', 'type'),
    //
    'set_alignment' => array('left' => t('Left'), 'right' => t('Right')),
    //
    'set_style' => array('thumbnail' => '100 x 100'),
    'set_width' => array('full' => t('Full width'), 'small' => t('Small')),
    // The function to render the widget in frontend (/in the text filter).
    // Must return markup.
    'process' => 'cke_placeholder_embed_widget_process',
    // Render function for the preview. If not specified, the process function
    // will be used
    'preview_process' => 'cke_placeholder_embed_widget_preview_process',
  );

  return $tags;
}

/**
 * Alter tag information.
 *
 * @param array $tags
 *   Array of existing tags.
 */
function hook_cke_placeholder_tags_alter(&$tags) {
  $tags['cke_placeholder_embed']['editables']['caption']['allowed_content'] .= 'strong i';
}

/**
 * Register a new library tab.
 */
function hook_cke_placeholder_library_tab_info() {
  $tabs = array();

  $tabs['cke_placeholder_media_library'] = array(
    // A function that can return an array of elements for the library tab.
    // Must return an array of associative arrays where each elements contains
    // 'data': the values needed to identify the placeholder upon rendering.
    // 'markup': The markup for the list element.
    // Be sure to have a draggable="no" on images.
    'list_callback' => 'cke_placeholder_media_list',
    // If you want to use the form to replace items in the list, use this to
    // assign an ID. This must obviously be unique or strange things may
    // happen.
    'list_wrapper_id' => 'cke-placeholder-media-list',
    // The name to display on the tab for the pane.
    'pane_title' => t('Media library'),
    // A for to include on the tab. All buttons must be ajax-only.
    'form' => 'cke_placeholder_media_list_search',
  );
}

/**
 * Alter an existing library tab.
 */
function hook_cke_placeholder_library_tab_alter(&$tabs) {
  // Noop.
}
