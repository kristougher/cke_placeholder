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
   *   A string description of the CkePlaceholderLibrary pane.
   */
  public function description();

  /**
   * Provide a pane title of plugin.
   *
   * @return string
   *   The Label to display on the tab for the pane.
   */
  public function paneTitle();

  /**
   * Provide a list wrapper id.
   *
   * @return string
   *   The id for the markup for use with AJAX functions.
   */
  public function listWrapperId();

  /**
   * Provide the form for pane tabs.
   *
   * @return array
   *   A form array to display on the library pane.
   */
  public function buildForm();

  /**
   * Return the list of items to drag from CkePlaceholderLibrary.
   *
   * @return array
   *   An array of draggable list elements.
   */
  public function getList();

}
