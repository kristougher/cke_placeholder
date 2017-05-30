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
  public function process($media_values, $filter) {
    $file = file_load($media_values['id']);
    $output = '';

    if ($file->type == 'image') {
      $variables = array(
        'style_name' => 'large',
        'path' => $file->uri,
      );

      $picture_tag = theme('image_style', $variables);

      $output = theme('ckep_file_entity_inline_image', array(
        'picture_tag' => $picture_tag,
        'caption' => urldecode($media_values['caption']),
      ));
    }
    else {
      $output = render(file_view($file, 'teaser'));
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function preview_process($media_values, $filter) {
// Loads the file.
    $output = '';
    $file = file_load(intval($media_values['id']));

    $image_url = image_style_url('thumbnail', $file->uri);

    $wrap_style = 'full';

    if (!empty($media_values['alignment']) && ($media_values['alignment'] != 'full')) {
      $wrap_style = trim($media_values['alignment']);
    }

    if ($file->type == 'image') {
      $output = theme('ckep_file_entity_inline_image', array(
        'picture_tag' => theme('image', array('path' => $image_url)),
        'alignment_class' => 'body-text__picture--',
      ));
    }
    else {
      $output = render(file_view($file, 'teaser'));
    }
    return $output;
  }

}
