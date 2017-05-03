<?php

/**
 * @file
 * Contains \Drupal\linkit\Plugin\CKEditorPlugin\CKEPlaceholder.
 */

namespace Drupal\cke_placeholder\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
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
class CKEPlaceholder extends CKEditorPluginBase implements CKEditorPluginConfigurableInterface, ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  //  $this->linkitProfileStorage = $linkit_profile_storage;
  }

  /**
   * {@inheritdoc}
   */
 /* public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')->getStorage('linkit_profile')
    );
  }*/

  /**
   * {@inheritdoc}
   */
  public function getFile() {
    return drupal_get_path('module', 'cke_placeholder') . '/js/plugins/cke_placeholder/plugin.js';
  }
}
