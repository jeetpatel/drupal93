<?php

namespace Drupal\learning;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Learning plugin manager.
 */
class LearningPluginManager extends DefaultPluginManager {

  /**
   * Constructs LearningPluginManager object.
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
    parent::__construct(
      'Plugin/Learning',
      $namespaces,
      $module_handler,
      'Drupal\learning\LearningInterface',
      'Drupal\learning\Annotation\Learning'
    );
    $this->alterInfo('learning_info');
    $this->setCacheBackend($cache_backend, 'learning_plugins');
  }

}
