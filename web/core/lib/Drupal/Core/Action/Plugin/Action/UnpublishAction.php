<?php

namespace Drupal\Core\Action\Plugin\Action;

use Drupal\Core\Session\AccountInterface;

/**
 * Unpublishes an entity.
 *
 * @Action(
 *   id = "entity:unpublish_action",
 *   action_label = @Translation("Unpublish"),
 *   deriver = "Drupal\Core\Action\Plugin\Action\Derivative\EntityPublishedActionDeriver",
 * )
 */
class UnpublishAction extends EntityActionBase
{
  /**
   * {@inheritdoc}
   */
    public function execute($entity = null)
    {
        $entity->setUnpublished()->save();
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
