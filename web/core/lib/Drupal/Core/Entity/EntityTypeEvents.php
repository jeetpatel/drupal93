<?php

namespace Drupal\Core\Entity;

/**
 * Contains all events thrown while handling entity types.
 */
final class EntityTypeEvents
{
  /**
   * The name of the event triggered when a new entity type is created.
   *
   * This event allows modules to react to a new entity type being created. The
   * event listener method receives a \Drupal\Core\Entity\EntityTypeEvent
   * instance.
   *
   * @Event
   *
   * @see \Drupal\Core\Entity\EntityTypeEvent
   * @see \Drupal\Core\Entity\EntityTypeListenerInterface::onEntityTypeCreate()
   * @see \Drupal\Core\Entity\EntityTypeEventSubscriberTrait
   * @see \Drupal\views\EventSubscriber\ViewsEntitySchemaSubscriber::onEntityTypeCreate()
   *
   * @var string
   */
    public const CREATE = 'entity_type.definition.create';

    /**
     * The name of the event triggered when an existing entity type is updated.
     *
     * This event allows modules to react whenever an existing entity type is
     * updated. The event listener method receives a
     * \Drupal\Core\Entity\EntityTypeEvent instance.
     *
     * @Event
     *
     * @see \Drupal\Core\Entity\EntityTypeEvent
     * @see \Drupal\Core\Entity\EntityTypeListenerInterface::onEntityTypeUpdate()
     * @see \Drupal\Core\Entity\EntityTypeEventSubscriberTrait
     * @see \Drupal\views\EventSubscriber\ViewsEntitySchemaSubscriber::onEntityTypeUpdate()
     *
     * @var string
     */
    public const UPDATE = 'entity_type.definition.update';

    /**
     * The name of the event triggered when an existing entity type is deleted.
     *
     * This event allows modules to react whenever an existing entity type is
     * deleted.  The event listener method receives a
     * \Drupal\Core\Entity\EntityTypeEvent instance.
     *
     * @Event
     *
     * @see \Drupal\Core\Entity\EntityTypeEvent
     * @see \Drupal\Core\Entity\EntityTypeListenerInterface::onEntityTypeDelete()
     * @see \Drupal\Core\Entity\EntityTypeEventSubscriberTrait
     * @see \Drupal\views\EventSubscriber\ViewsEntitySchemaSubscriber::onEntityTypeDelete()
     *
     * @var string
     */
    public const DELETE = 'entity_type.definition.delete';
}
