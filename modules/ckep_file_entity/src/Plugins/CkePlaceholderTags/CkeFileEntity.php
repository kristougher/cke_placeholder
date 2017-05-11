<?php

namespace Drupal\cke_file_entity\Plugin\CkePlaceholderTags;

use Drupal\cke_placeholder\CkePlaceholderTagsBase;

/**
 * Provides a CkeFileEntity plugin.
 *
 * CkePlaceholderTags(
 *   id = "cke_file_entity",
 *   description = @Translation("Cke File Entity for CKE Placeholder Tags."),
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
 *     "full" = @Translation("Full"),
 *   },
 *   module = "cke_placeholder"
 * )
 */
class CkeFileEntity extends CkePlaceholderTagsBase {

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
