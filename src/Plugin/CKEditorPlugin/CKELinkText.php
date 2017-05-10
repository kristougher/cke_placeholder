<?php

/**
 * @file
 * Contains \Drupal\linkit\Plugin\CKEditorPlugin\CKELinkText.
 */

namespace Drupal\cke_placeholder\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the "cke_link_text" plugin.
 *
 * @CKEditorPlugin(
 *   id = "cke_link_text",
 *   label = @Translation("CKELinkText"),
 *   module = "cke_placeholder"
 * )
 */
class CKELinkText extends CKEditorPluginBase implements CKEditorPluginConfigurableInterface {

  /**
   * {@inheritdoc}
   * @TODO
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'cke_placeholder') . '/js/plugins/cke_link_text/plugin.js';
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig(Editor $editor) {
    return [];
  }

  /**
   *
   */
  public function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {
    $settings = $editor->getSettings();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getButtons() {
    return array(
      'Linkit' => array(
        'label' => t('Linkit'),
        'image' => drupal_get_path('module', 'linkit') . '/js/plugins/linkit/linkit.png',
      ),
    );
  }

}
