<?php

/**
 * @file
 * Media library plugin for the CKE Placeholder media pane.
 */

namespace Drupal\cke_placeholder\Plugin\CkePlaceholderLibrary;

use Drupal\cke_placeholder\CkePlaceholderLibraryBase;

/**
 * Class CkePlaceholderMediaLibrary
 * @package Drupal\cke_placeholder\CkePlaceholderMediaLibrary
 *
 * @CkePlaceholderLibrary(
 *   id="ckeplaceholder_library_upload",
 *   description=@Translation("Media Library for CKE Placeholder pane"),
 *   pane_title=@Translation("Media"),
 *   list_wrapper_id="media-library",
 *   module="cke_placeholder"
 * )
 */
class CkePlaceholderLibraryUpload extends CkePlaceholderLibraryBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm() {
    return [
      '#type' => 'managed_file',
      '#title' => t('Upload file'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getList() {
    return [[
      '#markup' => 'jkdhfgkjsfgks',
    ]];
  }
}
