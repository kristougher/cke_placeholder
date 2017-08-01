<?php

namespace Drupal\cke_placeholder;

/**
 * An interface for all CkePlaceholderLibrary type plugins.
 */
interface CkePlaceholderLibraryInterface {

  /**
   * Provide a description of plugin.
   *
   * @return string
   *   A string description of the CkePlaceholderLibrary.
   */
  public function description();

  /**
   * Provide a pane title of plugin
   *
   * @return string
   */
  public function paneTitle();

  /**
   * Provide a list wrapper id.
   *
   * @return string.
   */
  public function listWrapperId();
  /**
   * Provide the form for pane tabs.
   *
   * @return float
   *   The number of calories per serving.
   */
  public function buildForm();

  /**
   * Place an order for a CkePlaceholderLibrary.
   * @return array()
   */
  public function getList();
}
