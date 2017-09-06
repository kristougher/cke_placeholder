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
  public function process($media_values) {
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
  public function previewProcess($args) {
    // Loads the file.
    $output = '';
    $file = file_load(intval($args['id']));
    $image_url = ImageStyle::load('thumbnail')->buildUrl($file->getFileUri());

    $wrap_style = 'full';

    if (!empty($args['alignment']) && ($args['alignment'] != 'full')) {
      $wrap_style = trim($args['alignment']);
    }
    $render = [
      '#theme' => 'ckep_file_entity_inline_image',
      '#picture_tag' => [
        '#theme' => 'image',
        '#path' => $image_url,
      ],
      '#alignment_class' => 'body-text__picture--' . $wrap_style,
      '#caption' => trim($args['caption']),
    ];
    $output = render($render);

    return $output;
  }

}
