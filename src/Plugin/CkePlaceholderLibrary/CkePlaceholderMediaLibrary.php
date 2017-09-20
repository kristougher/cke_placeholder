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
  public function buildForm($form, $form_state) {
    $form = [
      'search' => [
        '#type' => 'textfield',
        '#title' => t('Pony'),
      ],
      'list' => [
        '#theme' => 'cke_placeholder_library_item_list',
        '#cke_placeholder_tag' => 'ckep_file_entity',
        '#wrapper_id' => 'cke_placeholder_embed',
        '#items' => [
          [
            '#theme' => 'cke_placeholder_library_item',
            '#data' => [
              'id' => 1,
              'caption' => 'hej med dig',
              'type' => 'image',
            ],
            '#cke_placeholder_tag' => 'ckep_file_entity',
            '#markup' => 'Træk denne',
          ],
        ],
      ],
      'eembed' => [
        '#theme' => 'cke_placeholder_library_item_list',
        '#wrapper_id' => 'cke_placeholder_entity_embed',
        '#cke_placeholder_tag' => 'ckep_file_entity_embed',
        '#items' => [
          [
            '#theme' => 'cke_placeholder_entity_embed',
            '#id' => '030825c7-5c92-4ce3-9ffe-b0afb428797d',
            '#caption' => 'hej med dig',
            '#entity_type' => 'media',
            '#uuid' => '030825c7-5c92-4ce3-9ffe-b0afb428797d',
            '#view_mode' => 'poster',
          ],
        ],
      ],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getList($form_state) {
    return [['#markup' => 'jkdhfgkjsfgks']];
  }

}
