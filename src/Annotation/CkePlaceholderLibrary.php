<?php

namespace Drupal\cke_placeholder\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a CkePlaceholderLibrary annotation object.
 * @Annotation
 */
class CkePlaceholderLibrary extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * A brief, human readable, description of the CkePlaceholderLibrary type.
   */
  public $description;

  /**
   *
   */
  public $pane_title;

  /**
   *
   */
  public $list_wrapper_id;

  /**
   *
   */
  public $module;

}
