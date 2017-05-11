<?php

namespace Drupal\cke_placeholder;

use Drupal\Component\Plugin\PluginBase;

abstract class CkePlaceholderTags extends PluginBase implements CkePlaceholderTagsInterface {

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
  public function editables() {
    // Retrieve the @editables property from the annotation and return it.
    return $this->pluginDefinition['editables'];
  }

  /**
   * {@inheritdoc}
   */
  public function key() {
    // Retrieve the key property from the annotation and return it.
    return $this->pluginDefinition['key'];
  }

  /**
   * {@inheritdoc}
   */
  public function alignment() {
    // Retrieve the @alignment property from the annotation and return it.
    return $this->pluginDefinition['set_alignment'];
  }

  /**
   * {@inheritdoc}
   */
  public function style() {
    // Retrieve the @style property from the annotation and return it.
    return $this->pluginDefinition['set_style'];
  }

  /**
   * {@inheritdoc}
   */
  public function width() {
    // Retrieve the @width property from the annotation and return it.
    return $this->pluginDefinition['set_width'];
  }

  /**
   * {@inheritdoc}
   */
  abstract public function process();

  /**
   * {@inheritdoc}
   */
  public function preview_process() {
    $this->process();
  }

}
