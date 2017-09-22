<?php

namespace Drupal\ckep_embed_external\Plugin\CkePlaceholderLibrary;

use Drupal\cke_placeholder\CkePlaceholderLibraryBase;
use Alb\OEmbed\Simple as OEmbedSimple;
use Alb\OEmbed\Provider as OEmbedProvider;

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
    $provider = self::getProvider($url);

    if ($provider) {
      $response = $provider->request($url);
      $title = $response->getTitle();
      $thumb = $response->getThumbnailUrl();
      $html = $response->getHtml();
      $js = self::processHtml($html);
      $render = [
        '#theme' => 'cke_placeholder_library_item',
        '#data' => [
          'type' => $response->getType(),
          'title' => $title,
          'oembed' => TRUE,
          'provider_name' => $response->getProviderName(),
          'thumbnail_url' => $thumb,
          'url' => $url,
          'html' => $html,
        ],
        '#cke_placeholder_tag' => 'ckep_embed_external',
        '#image_url' => $thumb,
        '#descriptoin' => $title . ' - ' . $response->getProviderName(),
      ];
      if (!empty($js)) {
        $render['#data']['js'] = $js;
      }
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
  public static function getProvider($url) {
    switch (parse_url($url)['host']) {

      case 'facebook.com':
        $endpoint = 'https://www.facebook.com/';
        break;

    }
    if (!empty($endpoint)) {
      $provider = new OEmbedProvider($endpoint, 'json');
      return $provider;
    }
    elseif ($provider = OEmbedSimple::getProvider($url)) {
      return $provider;
    }

    return NULL;
  }

  /**
   * Remove script tags from HTML to include it appropriately.
   */
  public static function processHtml(&$html) {
    $regex_file = '/<script(\w|\s)+src="(([a-zA-Z]|[0-9]|\.|-|_)+\.js)"([^>])+>/';
    $regex_tag = '/<script([^>])*><\/script>/';
    $matches = [];
    preg_match($regex, $html, $matches);
    $html = preg_replace($regex_tag, '', $html);
    return $matches[2];
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
