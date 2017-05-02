<?php /**
 * @file
 * Contains \Drupal\cke_placeholder\Controller\DefaultController.
 */

namespace Drupal\cke_placeholder\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Default controller for the cke_placeholder module.
 */
class DefaultController extends ControllerBase {

  public function cke_placeholder_widget_preview($plugin) {
    $args = \Drupal\Component\Utility\UrlHelper::filterQueryParameters();
    $tag = cke_placeholder_tags($plugin);
    $process_function = empty($tag['preview_process']) ? $tag['process'] : $tag['preview_process'];
    $output = $process_function($args, NULL);

    return drupal_json_output(['markup' => $output]);
  }

  public function cke_placeholder_media_status_update($file, $new_status) {
    $file->field_status[\Drupal\Core\Language\Language::LANGCODE_NOT_SPECIFIED][0]['value'] = $new_status;

    file_save($file);
    $message = t('Status set to @status on file ID @fid', [
      '@status' => $new_status,
      '@fid' => $file->fid,
    ]);
    return \Drupal\Component\Serialization\Json::encode(['message' => $message]);
  }

  public function cke_placeholder_update_done($file) {
    if (\Drupal::moduleHandler()->moduleExists('admin_menu')) {
      \Drupal::moduleHandler()->invoke('admin_menu', 'suppress');
    }

    $vars = [
      'fid' => $file->fid,
      'title' => $file->filename,
    ];

    // @FIXME
    // theme() has been renamed to _theme() and should NEVER be called directly.
    // Calling _theme() directly can alter the expected output and potentially
    // introduce security issues (see https://www.drupal.org/node/2195739). You
    // should use renderable arrays instead.
    // 
    // 
    // @see https://www.drupal.org/node/2195739
    // $output = theme('cke_placeholder_update_done', $vars);


    return $output;
  }

}
