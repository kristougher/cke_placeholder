<?php

namespace Drupal\ckep_file_entity\Plugin\CkePlaceholderTags;

use Drupal\cke_placeholder\CkePlaceholderTagsBase;
use Drupal\image\Entity\ImageStyle;

/**
 * Provides a CkeFileEntity plugin.
 *
 * @CkePlaceholderTags(
 *   id = "ckep_file_entity",
 *   description = @Translation("Cke File Entity for CKE Placeholder Tags."),
 *   editables = {
 *     "caption" = {
 *       "label" = @Translation("Caption"),
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
 *   module = "ckep_file_entity"
 * )
 */
class CkeFileEntity extends CkePlaceholderTagsBase {

  /**
   * {@inheritdoc}
   */
  public function process($args) {

    // Loads the file.
    $output = [];
    if (empty($args['id'])) {
      return $output;
    }
    $alignment = empty($args['alignment']) ? '' : trim($args['alignment']);
    $output['#theme'] = 'ckep_file_entity_inline_image';
    $output['#caption'] = urldecode($args['caption']);
    $output['#alignment_class'] = $alignment;
    $output['#picture_tag'] = $this->processWrap($args['id'], 'preview', $alignment);

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function previewProcess($args) {
    // Loads the file.
    $output = [];
    if (empty($args['id'])) {
      return $output;
    }

    $output = $this->processWrap($args['id'], 'preview', trim($args['alignment']));
    return $output;
  }

  /**
   * Process function wrapper.
   */
  protected function processWrap($id, $viewmode = 'default', $alignment = 'full') {
    $file = \Drupal::entityTypeManager()->getStorage('media')->load($id);

    if (empty($file)) {
      return [];
    }

    $view_builder = \Drupal::entityManager()->getViewBuilder('media');

    $output = $view_builder->view($file, 'preview');
    return $output;
  }

}
