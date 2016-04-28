<?php
/**
 * @file
 * Callbacks for editor functionality.
 */

/**
 * Update the status of a media entity.
 */
function cke_placeholder_media_status_update($file, $new_status) {
  $file->field_status[LANGUAGE_NONE][0]['value'] = $new_status;

  file_save($file);
  $message = t('Status set to @status on file ID @fid', array('@status' => $new_status, '@fid' => $file->fid));
  return drupal_json_encode(array('message' => $message));
}

/**
 * Close dialog and modify parent window markup.
 *
 * @todo Create a destination page for an edit form dialog to close the dialog
 *  and update the parent window.
 */
function cke_placeholder_update_done($file) {
  if (module_exists('admin_menu')) {
    module_invoke('admin_menu', 'suppress');
  }

  $vars = array(
    'fid' => $file->fid,
    'title' => $file->filename,
  );

  $output = theme('cke_placeholder_update_done', $vars);

  return $output;
}
