<?php

namespace Drupal\learning;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\learning\Entity\LearningEntityInterface;

/**
 * Defines the storage handler class for Learning entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Learning entity entities.
 *
 * @ingroup learning
 */
interface LearningEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Learning entity revision IDs for a specific Learning entity.
   *
   * @param \Drupal\learning\Entity\LearningEntityInterface $entity
   *   The Learning entity entity.
   *
   * @return int[]
   *   Learning entity revision IDs (in ascending order).
   */
  public function revisionIds(LearningEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Learning entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Learning entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\learning\Entity\LearningEntityInterface $entity
   *   The Learning entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(LearningEntityInterface $entity);

  /**
   * Unsets the language for all Learning entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
