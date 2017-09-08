<?php

/**
 * @file
 * Contains \Drupal\cke_placeholder\Controller\DefaultController.
 */

namespace Drupal\cke_placeholder\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Default controller for the cke_placeholder module.
 */
class DefaultController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'cke_placeholder';
  }

  /**
   * Constructs a Widget preview.
   *
   * @param string $plugin
   *   Name of the cke_placeholder tag/plugin.
   */
  public function cke_placeholder_widget_preview(Request $request, $plugin) {
    $args = \Drupal\Component\Utility\UrlHelper::filterQueryParameters($request->query->all());
    $pluginManager = \Drupal::service('plugin.manager.cke_placeholder_tags');

    $pluginClass = $pluginManager->createInstance($plugin);
    $output = $pluginClass->previewProcess($args);

    return new JsonResponse(['markup' => render($output)]);
  }

  public function source_pane() {

  }

  /**
   * Update the status of a media entity.
   */
  public function cke_placeholder_media_status_update($file, $new_status) {
    $file->field_status[\Drupal\Core\Language\Language::LANGCODE_NOT_SPECIFIED][0]['value'] = $new_status;

    file_save($file);
    $message = t('Status set to @status on file ID @fid', [
      '@status' => $new_status,
      '@fid' => $file->fid,
    ]);
    return \Drupal\Component\Serialization\Json::encode(['message' => $message]);
  }

  /**
   * Close dialog and modify parent window markup.
   *
   * @todo Create a destination page for an edit form dialog to close the dialog
   *  and update the parent window.
   */
  public function cke_placeholder_update_done($file) {
    if (\Drupal::moduleHandler()->moduleExists('admin_menu')) {
      \Drupal::moduleHandler()->invoke('admin_menu', 'suppress');
    }

 /*   $output = [
      '#theme' => 'cke_placeholder_update_done',
      'fid' => $file->fid,
      'title' => $file->filename
    ];
  *
  */

    return $output;
  }

}
