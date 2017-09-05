<?php

namespace Drupal\cke_placeholder;

/**
 * An interface for all CkePlaceholderLibrary type plugins.
 */
interface CkePlaceholderTagsInterface {

  /**
   * Provide a description of plugin.
   *
   * @return string
   *   A string description of the CkePlaceholderLibrary.
   */
  public function description();

  /**
   * Specify the editable fields you want in the widget.
   * @return @array.
   */
  public function editables();

  /**
   * Provide key of tags.
   *
   * @return array.
   */
  public function key();

  /**
   * Provide alighment attribute.
   * @return array.
   */
  public function alignment();

  /**
   *
   * @return array.
   */
  public function style();

  /**
   *
   * @return array.
   */
  public function width();

  /**
   * Render widget in frontend (in text filter).
   *
   * @param array $args
   *   The values from the placeholder.
   *
   * @return string
   *  Markup of the widget.
   */
  public function process($args);

  /**
   * Render for the preview. If it is not override, use the process one.
   *
   * @param array $args
   *   The values from the placeholder.
   *
   * @return string
   *  Markup of the widget.
   */
  public function previewProcess($args);

}
