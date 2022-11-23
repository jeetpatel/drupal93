<?php

namespace Drupal\learning;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class LearningEntityStorage extends SqlContentEntityStorage implements LearningEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(LearningEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {learning_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {learning_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(LearningEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {learning_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('learning_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
