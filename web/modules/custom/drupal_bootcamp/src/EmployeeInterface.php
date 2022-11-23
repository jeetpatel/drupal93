<?php

namespace Drupal\drupal_bootcamp;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining an employee entity type.
 */
interface EmployeeInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the employee title.
   *
   * @return string
   *   Title of the employee.
   */
  public function getTitle();

  /**
   * Sets the employee title.
   *
   * @param string $title
   *   The employee title.
   *
   * @return \Drupal\drupal_bootcamp\EmployeeInterface
   *   The called employee entity.
   */
  public function setTitle($title);

  /**
   * Gets the employee creation timestamp.
   *
   * @return int
   *   Creation timestamp of the employee.
   */
  public function getCreatedTime();

  /**
   * Sets the employee creation timestamp.
   *
   * @param int $timestamp
   *   The employee creation timestamp.
   *
   * @return \Drupal\drupal_bootcamp\EmployeeInterface
   *   The called employee entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the employee status.
   *
   * @return bool
   *   TRUE if the employee is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets the employee status.
   *
   * @param bool $status
   *   TRUE to enable this employee, FALSE to disable.
   *
   * @return \Drupal\drupal_bootcamp\EmployeeInterface
   *   The called employee entity.
   */
  public function setStatus($status);

}
