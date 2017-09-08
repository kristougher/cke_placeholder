<?php

namespace Drupal\cke_placeholder;

use Drupal\Component\Plugin\PluginBase;

abstract class CkePlaceholderLibraryBase extends PluginBase implements CkePlaceholderLibraryInterface {

  /**
   * {@inheritdoc}
   */
  public function description() {
    // Retrieve the @description property from the annotation and return it.
    return $this->pluginDefinition['description'];
  }

  /**
   * {@inheritdoc}
   */
  public function paneTitle() {
    // Retrieve the @pane_title property from the annotation and return it.
    return $this->pluginDefinition['pane_title'];
  }

  /**
   * {@inheritdoc}
   */
  public function listWrapperId() {
    // Retrieve the list_wrapper_id property from the annotation and return it.
    return $this->pluginDefinition['list_wrapper_id'];
  }

  /**
   * {@inheritdoc}
   */
  abstract public function buildForm($form, $form_state);

  /**
   * {@inheritdoc}
   */
  abstract public function getList($form_state);

}
