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
 *   id="ckeplaceholder_medialibrary",
 *   description=@Translation("Media Library for CKE Placeholder pane"),
 *   pane_title=@Translation("Media"),
 *   list_wrapper_id="media-library",
 *   module="cke_placeholder"
 * )
 */
class CkePlaceholderMediaLibrary extends CkePlaceholderLibraryBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm() {
    return [
      '#type' => 'textfield',
      '#title' => t('Pony'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getList() {
    return [
      '#markup' => 'jkdhfgkjsfgks',
    ];
  }
}