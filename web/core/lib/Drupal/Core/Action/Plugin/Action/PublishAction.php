<?php

namespace Drupal\Core\Action\Plugin\Action;

use Drupal\Core\Session\AccountInterface;

/**
 * Publishes an entity.
 *
 * @Action(
 *   id = "entity:publish_action",
 *   action_label = @Translation("Publish"),
 *   deriver = "Drupal\Core\Action\Plugin\Action\Derivative\EntityPublishedActionDeriver",
 * )
 */
class PublishAction extends EntityActionBase
{
  /**
   * {@inheritdoc}
   */
    public function execute($entity = null)
    {
        $entity->setPublished()->save();
    }

    /**
     * {@inheritdoc}
     */
    public function access($object, AccountInterface $account = null, $return_as_object = false)
    {
        $key = $object->getEntityType()->getKey('published');

        /** @var \Drupal\Core\Entity\EntityInterface $object */
        $result = $object->access('update', $account, true)
      ->andIf($object->$key->access('edit', $account, true));

        return $return_as_object ? $result : $result->isAllowed();
    }
}
