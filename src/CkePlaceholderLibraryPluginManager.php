<?php

namespace Drupal\cke_placeholder;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\cke_placeholder\Annotation\CkePlaceholderLibrary;

/**
 * A plugin manager for CkePlaceholderLibrary plugins.
 *
 */
class CkePlaceholderLibraryPluginManager extends DefaultPluginManager {

  /**
   * Creates the discovery object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {

    $subdir = 'Plugin/CkePlaceholderLibrary';

    // The name of the interface that plugins should adhere to.
    $plugin_interface = CkePlaceholderLibraryInterface::class;

    // The name of the annotation class that contains the plugin definition.
    $plugin_definition_annotation_name = CkePlaceholderLibrary::class;

    parent::__construct($subdir, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);

    // This allows the plugin definitions to be altered by an alter hook. The
    // parameter defines the name of the hook, thus: hook_cke_placeholder_library_info_alter().
    // @see cke_placeholder.api.php for example
    // hook -> cke_placeholder_cke_placeholder_library_info_alter().

    $this->alterInfo('cke_placeholder_library_info');
    $this->setCacheBackend($cache_backend, 'cke_placeholder_library_info');
  }

}
