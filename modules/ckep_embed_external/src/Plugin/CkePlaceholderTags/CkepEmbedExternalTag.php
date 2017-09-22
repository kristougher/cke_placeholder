<?php

namespace Drupal\ckep_embed_external\Plugin\CkePlaceholderTags;

use Drupal\cke_placeholder\CkePlaceholderTagsBase;
use Alb\OEmbed\Simple as OEmbedSimple;

/**
 * Provides a CkePlaceholderEmbed plugin.
 *
 * @CkePlaceholderTags(
 *   id = "ckep_embed_external",
 *   description = @Translation("Cke Placeholder extension for embedding external content."),
 *   editables = {
 *     "caption" = {
 *       "label" = @Translation("Caption"),
 *       "allowed_content" = "strong em i",
 *     }
 *   },
 *   key = {
 *     "url",
 *   },
 *   set_alignment = {
 *     "left" = @Translation("Left"),
 *     "right" = @Translation("Right"),
 *   },
 *   set_style = {
 *      "thumbnail" = "100 x 100",
 *   },
 *   set_width = {
 *     "full" = @Translation("Full width"),
 *     "small" = @Translation("Small")
 *   },
 *   module = "ckep_embed_external"
 * )
 */
class CkepEmbedExternalTag extends CkePlaceholderTagsBase {

  /**
   * {@inheritdoc}
   */
  public function process($args) {
    $output = '';

    if ($args['oembed']) {
      $output = $this->processOembed($args['url']);
      drupal_set_message($output, 'status', FALSE);
    }
    elseif (!empty($args['url'])) {
      $output = sprintf('<div class="ckep-embed-external"><iframe src="%s"></iframe></div>', $args['url']);
    }
    $render = [
      '#theme' => 'ckep_embed_external',
      '#content' => $output,
    ];
    if (!empty($js)) {
        $render['#attached']['html_head'][] = [
          // The data.
          [
            '#type' => 'html_tag',
            // The HTML tag to add, in this case a  tag.
            '#tag' => 'script',
            // Set attributes like src to load a file.
            '#attributes' => array('src' => $js, 'async'),

          ],
          // Assuming that providers only.
          $response->getProviderName(),
        ];
      }
  }

  protected function processOembed($url) {
    $response = OEmbedSimple::request($url);
    return $response->getHtml();
  }

  /**
   * {@inheritdoc}
   */
  public function previewProcess($args) {
    if ($args['oembed']) {
      $output = $args['title'] . ' - ' . $args['provider_name'];
    }
    else {
      $output = 'Iframe:' . $args['url'];
    }
    return $output;
  }

}
