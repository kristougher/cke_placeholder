<?php

namespace Drupal\cke_placeholder\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a CkePlaceholderTags annotation object.
 *
 * @Annotation
 */
class CkePlaceholderTags extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * A brief, human readable, description of the CkePlaceholderTags type.
   */
  public $description;

  /**
   *
   */
  public $editables;

  /**
   *
   */
  public $key;

  /**
   *
   */
  public $set_alignment;

  /**
   *
   */
  public $module;
}
