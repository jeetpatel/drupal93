<?php

namespace Drupal\Core\Entity\KeyValueStore;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\TranslatableInterface;

/**
 * Provides a key value backend for content entities.
 */
class KeyValueContentEntityStorage extends KeyValueEntityStorage implements ContentEntityStorageInterface
{
  /**
   * {@inheritdoc}
   */
    public function createTranslation(ContentEntityInterface $entity, $langcode, array $values = [])
    {
        // @todo Complete the content entity storage implementation in
    //   https://www.drupal.org/node/2618436.
    }

    /**
     * {@inheritdoc}
     */
    public function hasStoredTranslations(TranslatableInterface $entity)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function createRevision(RevisionableInterface $entity, $default = true, $keep_untranslatable_fields = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function createWithSampleValues($bundle = false, array $values = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function loadMultipleRevisions(array $revision_ids)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestRevisionId($entity_id)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestTranslationAffectedRevisionId($entity_id, $langcode)
    {
        return null;
    }
}
