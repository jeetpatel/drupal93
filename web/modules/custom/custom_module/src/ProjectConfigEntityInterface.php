<?php

namespace Drupal\custom_module;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a project configuration entity entity type.
 */
interface ProjectConfigEntityInterface extends ConfigEntityInterface {

  /**
   * Declare function to get configuration.
   */
  public function getConfiguration();

  /**
   * Declare function to get JSON configuration.
   */
  public function getJsonConfiguration();
}
