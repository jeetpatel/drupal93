<?php

namespace Drupal\learning\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Learning entity entities.
 *
 * @ingroup learning
 */
interface LearningEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Learning entity name.
   *
   * @return string
   *   Name of the Learning entity.
   */
  public function getName();

  /**
   * Sets the Learning entity name.
   *
   * @param string $name
   *   The Learning entity name.
   *
   * @return \Drupal\learning\Entity\LearningEntityInterface
   *   The called Learning entity entity.
   */
  public function setName($name);

  /**
   * Gets the Learning entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Learning entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Learning entity creation timestamp.
   *
   * @param int $timestamp
   *   The Learning entity creation timestamp.
   *
   * @return \Drupal\learning\Entity\LearningEntityInterface
   *   The called Learning entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Learning entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Learning entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\learning\Entity\LearningEntityInterface
   *   The called Learning entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Learning entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Learning entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\learning\Entity\LearningEntityInterface
   *   The called Learning entity entity.
   */
  public function setRevisionUserId($uid);

}
