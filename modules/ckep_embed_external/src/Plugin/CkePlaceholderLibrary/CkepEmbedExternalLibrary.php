<?php

namespace Drupal\ckep_embed_external\Plugin\CkePlaceholderLibrary;

use Drupal\cke_placeholder\CkePlaceholderLibraryBase;

/**
 * Provides a CkepFileEntity plugin.
 *
 * @CkePlaceholderLibrary(
 *   id = "ckep_file_entity",
 *   description = @Translation("Ckep File Entity plugin for CKE Placeholder library."),
 *   pane_title = @Translation("Media library"),
 *   list_wrapper_id = "ckep_file_entity-media-list",
 *   module = "ckep_file_entity"
 * )
 */
class CkepEmbedExternal extends CkePlaceholderLibraryBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm($form, $form_state) {
    $form['ckep_embed_external'] = array(
      '#type' => 'fieldset',
      '#tree' => TRUE,
      '#weight' => 1,
      '#attributes' => array(
        'class' => array('ckep-embed-external', 'cke-library-fieldset'),
      ),
    );
    $form['ckep_embed_external']['url'] = [
      '#type' => 'textfield',
      '#title' => t('Embed URL'),
      '#description' => t('If the URL is not recognized as an Oembed resource, an iframe will be created.'),
    ];
    $form['ckep_embed_external']['fetch_content'] = [
      '#type' => 'button',
      '#value' => t('Fetch content'),
      '#ajax' => [
        'wrapper' => 'ckep-embed-external-preview',
        'callback' => 'Drupal\ckep_embed_external\Plugin\CkePlaceholderLibrary::getExternalContent',
      ],

    ];

    $form['ckep_embed_external']['preview'] = [
      '#markup' => '<div id="ckep-embed-external-preview"></div>',
    ];

    return $form;
  }

  /**
   * AJAX callback for processing the submitted URL.
   */
  public static getExternalContent($form, $form_state) {
    return ['#markup' => print_r($form_state->getValues(), 1)];
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
  public function getList($form_state) {
    return "";
  }
}
