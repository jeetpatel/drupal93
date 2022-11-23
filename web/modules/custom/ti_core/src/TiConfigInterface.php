<?php

namespace Drupal\ti_core;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a Teachit configuration entity type.
 */
interface TiConfigInterface extends ConfigEntityInterface {

  /**
   * Declare abstract method.
   */
  public function getConfiguration();

  /**
   * Declare abstract method.
   */
  public function getJsonConfiguration();

  /**
   * Declare abstract method.
   */
  public function getOptionalValue1();

  /**
   * Declare abstract method.
   */
  public function getOptionalValue2();

}
