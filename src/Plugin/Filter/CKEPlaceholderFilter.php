<?php

namespace Drupal\cke_placeholder\Plugin\Filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Drupal\Core\Url;

/**
 * Provides a filter for cke placeholder.
 *
 * @Filter(
 *   id = "cke_placeholder_filter",
 *   module = "cke_placeholder",
 *   title = @Translation("CKE Placeholder Filter"),
 *   description = @Translation("Apply CKEditor Placholder filters."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class CKEPlaceholderFilter extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    if (!empty($text)) {
      /**
       * @TODO this is copy/paste from D7 version.
       */
      $filter_regex = "/(<!--\s*)?\[(?<tag>[^]:]+):(?<args>(?:[^]\\\]|\\\.)*)\](\s*-->)?/";
      $output = preg_replace_callback($filter_regex, 'cke_placeholder_filter_process_callback', $text);
    }
    return new FilterProcessResult($output);
  }

  /**
   * {@inheritdoc}
   */
  public function tips($long = FALSE) {
    /**
     * @TODO
     */
  }
  /**
   * Returns available tags for filter.
   * @return type
   */
  public function getCKEPlaceholderTags() {
    $tags = &drupal_static(__FUNCTION__, array());

    if (!$tags) {
      $tags = \Drupal::moduleHandler()->invokeAll('cke_placeholder_tags');
      \Drupal::moduleHandler()->alter('cke_placeholder_tags', $tags);
    }
    if (!empty($name)) {
      return isset($tags[$name]) ? $tags[$name] : NULL;
    }
  }

}
