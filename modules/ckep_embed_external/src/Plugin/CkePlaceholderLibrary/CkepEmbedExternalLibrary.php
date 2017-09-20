<?php

namespace Drupal\ckep_embed_external\Plugin\CkePlaceholderLibrary;

use Drupal\cke_placeholder\CkePlaceholderLibraryBase;
use Alb\OEmbed;

/**
 * Provides a CkepFileEntity plugin.
 *
 * @CkePlaceholderLibrary(
 *   id = "ckep_embed_external",
 *   description = @Translation("CKE Placeholder library plugin to embed external content."),
 *   pane_title = @Translation("Embed external"),
 *   list_wrapper_id = "ckep_embed_external",
 *   module = "ckep_embed_external"
 * )
 */
class CkepEmbedExternalLibrary extends CkePlaceholderLibraryBase {

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
        'callback' => 'Drupal\ckep_embed_external\Plugin\CkePlaceholderLibrary\CkepEmbedExternalLibrary::getExternalContent',
      ],

    ];
    $discovery = new OEmbed\Discovery;
    $provider = $discovery->discover('http://vimeo.com/31423544');

    $form['ckep_embed_external']['preview'] = [
      '#markup' => '<div id="ckep-embed-external-preview"></div>' . $provider->getTitle(),
    ];

    return $form;
  }

  /**
   * AJAX callback for processing the submitted URL.
   */
  public static function getExternalContent($form, $form_state) {
    $response = OEmbed\Simple::request('https://vimeo.com/164619178');
    if ($title = $response->getTitle()) {
      return ['#markup' => $title];
    }
    return ['#markup' => print_r($form_state->getValues(), 1)];
  }

  /**
   * Get the content that should be available in the media library.
   *
   * @param FormStateInterface $form_state
   *   The entity type to fetch.
   *
   * @return array
   *   Renderable array for the media library.
   */
  public function getList($form_state) {
    return "";
  }
}
