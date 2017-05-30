<?php

namespace Drupal\cke_placeholder\Plugin\CkePlaceholderTags;

use Drupal\cke_placeholder\CkePlaceholderTagsBase;

/**
 * Provides a CkePlaceholderEmbed plugin.
 *
 * CkePlaceholderTags(
 *   id = "cke_placeholder_embed",
 *   description = @Translation("Cke Placeholder Embed for CKE Placeholder Tags."),
 *   editables = {
 *     "caption" = {
 *       "label" = @Translation('Caption'),
 *       "allowed_content" = "strong em i",
 *     }
 *   },
 *   key = {
 *     "id",
 *     "type",
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
 *   module = "cke_placeholder"
 * )
 */
class CkePlaceholderEmbed extends CkePlaceholderTagsBase {

  /**
   * {@inheritdoc}
   */
  public function process() {

  }

  /**
   * {@inheritdoc}
   */
  public function preview_process() {

  }

}
