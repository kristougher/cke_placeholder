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
        'method' => 'html',
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
  public static function getExternalContent($form, $form_state) {
    $url = $form_state->getValues()['ckep_embed_external']['url'];
    self::preprocessUrl($url);
    $response = OEmbed\Simple::request($url);

    if ($response) {
      $title = $response->getTitle();
      $thumb = $response->getThumbnailUrl();
      return [
        '#theme' => 'cke_placeholder_library_item',
        '#data' => [
          'type' => $response->getType(),
          'title' => $title,
          'oembed' => TRUE,
          'provider_name' => $response->getProviderName(),
          'thumbnail_url' => $thumb,
          'url' => $url,
        ],
        '#cke_placeholder_tag' => 'ckep_embed_external',
        '#image_url' => $thumb,
        '#descriptoin' => $title . ' - ' . $response->getProviderName(),
      ];
    }
    return [
      '#theme' => 'cke_placeholder_library_item',
      '#data' => [
        'type' => 'iframe',
        'oembed' => FALSE,
        'url' => $url,
      ],
      '#cke_placeholder_tag' => 'ckep_embed_external',
      '#description' => t('Iframe:') . $url,
    ];
  }

  /**
   * Modify URL to oembed endpoint for specific providers.
   */
  public static function preprocessUrl(&$url) {
    switch (parse_url($url)['host']) {
      case 'twitter.com':
        $url = 'https://publish.twitter.com/oembed?url=' . $url;
        break;

      case 'facebook.com':
        $url = 'https://www.facebook.com/plugins/post/oembed.json/?url=' . $url;
        break;

    }

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
