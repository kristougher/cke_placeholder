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
 *   id="ckeplaceholder_library_upload",
 *   description=@Translation("Upload pane for CKE Placeholder library."),
 *   pane_title=@Translation("Upload"),
 *   list_wrapper_id="ckeplaceholder-upload",
 *   module="cke_placeholder"
 * )
 */
class CkePlaceholderLibraryUpload extends CkePlaceholderLibraryBase implements CkePlaceholderLibraryInterface {

  /**
   * {@inheritdoc}
   */
  public function buildForm($form, $form_state) {
    return [
      'images' => [
        '#type' => 'managed_file',
        '#title' => t('Upload file'),
        '#default_value' => NULL,
        '#upload_location' => 'public://cke_placeholder_upload/',
        '#multiple' => TRUE,
        '#upload_validators' => [
          'file_validate_extensions' => ['png gif jpg jpeg pdf'],
          'file_validate_size' => [25600000],
        ],
      ],
      'list' => [
        '#markup' => '<div id="library-upload">HER</div>',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getList($form_state) {
    return [[
      '#markup' => 'jkdhfgkjsfgks',
    ]];
  }
}
