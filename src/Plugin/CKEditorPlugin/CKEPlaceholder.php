<?php

/**
 * @file
 * Contains \Drupal\linkit\Plugin\CKEditorPlugin\CKEPlaceholder.
 */

namespace Drupal\cke_placeholder\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\Core\Plugin\PluginBase;
use Drupal\ckeditor\CKEditorPluginContextualInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\editor\Entity\Editor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the "cke_placeholder" plugin.
 *
 * @CKEditorPlugin(
 *   id = "cke_placeholder",
 *   label = @Translation("CKEPlaceholder"),
 *   module = "cke_placeholder"
 * )
 */
class CKEPlaceholder extends CKEditorPluginBase implements CKEditorPluginContextualInterface {

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'cke_placeholder') . '/js/plugins/cke_placeholder/plugin.js';
  }

  /**
   * Implements \Drupal\ckeditor\Plugin\CKEditorPluginInterface::isInternal().
   */
  public function isInternal() {
    return FALSE;
  }

  public function isEnabled(Editor $editor) {
    $format = $editor->getFilterFormat();
    return $format->filters('cke_placeholder_filter')->status;

  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    $plugin = \Drupal::service('plugin.manager.cke_placeholder_library');
    $definitions = $plugin->getDefinitions();
    $settings = $editor->getSettings();
    $ckep_tags = array_keys($definitions);
    array_walk($ckep_tags,
      function(&$item, $index) {
        $item = str_replace('_', '-', $item);
      }
    );
    $extraAllowedContent = sprintf(' div(cke-placeholder-hidden,cke-placeholder,%)', implode(',', $ckep_tags));

    return [
      'ckePlaceholderTags' => implode(',', $ckep_tags),
      'allowedContent' => $extraAllowedContent,
      'contentCss' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getDependencies(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getLibraries(Editor $editor) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {
    $settings = $editor->getSettings();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return [
      'CkePlaceholder' => array(
        'label' => t('Text box'),
        'image' => drupal_get_path('module', 'linkit') . '/js/plugins/linkit/linkit.png',
        'image_alternative' => [
          '#type' => 'inline_template',
          '#template' => '<a href="http://a.bc">her</a>',
          '#context' => [
            'textbox' => t('Text box'),
          ],
        ],
      ),
    ];
  }
}
