<?php

/**
 * @file
 * Media library plugin for the CKE Placeholder media pane.
 */

namespace Drupal\cke_placeholder\Plugin\CkePlaceholderLibrary;

use Drupal\cke_placeholder\CkePlaceholderLibraryBase;
use Drupal\cke_placeholder\CkePlaceholderLibraryInterface;

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
class CkePlaceholderMediaLibrary extends CkePlaceholderLibraryBase implements CkePlaceholderLibraryInterface {

  /**
   * {@inheritdoc}
   */
  public function buildForm() {
    $form = [
      'search' => [
        '#type' => 'textfield',
        '#title' => t('Pony'),
      ],
      'list' => [
        '#theme' => 'cke_placeholder_library_item',
        '#cke_placeholder_tag' => 'cke_placeholder_embed',
        '#wrapper_id' => 'cke_placeholder_embed',
        '#items' => [
          [
            'data' => ['id' => 1, 'caption' => 'hej med dig'],
            'markup' => 'Træk denne',
          ],
        ],
      ],
    ];
    return $form;
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
