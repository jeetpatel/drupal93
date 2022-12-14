<?php

namespace Drupal\FunctionalTests\Rest;

use Drupal\Core\Field\Entity\BaseFieldOverride;
use Drupal\node\Entity\NodeType;
use Drupal\Tests\rest\Functional\EntityResource\EntityResourceTestBase;

abstract class BaseFieldOverrideResourceTestBase extends EntityResourceTestBase
{
  /**
   * {@inheritdoc}
   */
    protected static $modules = ['field', 'node'];

    /**
     * {@inheritdoc}
     */
    protected static $entityTypeId = 'base_field_override';

    /**
     * @var \Drupal\Core\Field\Entity\BaseFieldOverride
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    protected function setUpAuthorization($method)
    {
        $this->grantPermissionsToTestedRole(['administer node fields']);
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity()
    {
        $camelids = NodeType::create([
      'name' => 'Camelids',
      'type' => 'camelids',
    ]);
        $camelids->save();

        $entity = BaseFieldOverride::create([
      'field_name' => 'promote',
      'entity_type' => 'node',
      'bundle' => 'camelids',
    ]);
        $entity->save();

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedNormalizedEntity()
    {
        return [
      'bundle' => 'camelids',
      'default_value' => [],
      'default_value_callback' => '',
      'dependencies' => [
        'config' => [
          'node.type.camelids',
        ],
      ],
      'description' => '',
      'entity_type' => 'node',
      'field_name' => 'promote',
      'field_type' => 'boolean',
      'id' => 'node.camelids.promote',
      'label' => null,
      'langcode' => 'en',
      'required' => false,
      'settings' => [
        'on_label' => 'On',
        'off_label' => 'Off',
      ],
      'status' => true,
      'translatable' => true,
      'uuid' => $this->entity->uuid(),
    ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getNormalizedPostEntity()
    {
        // @todo Update in https://www.drupal.org/node/2300677.
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedCacheContexts()
    {
        return [
      'user.permissions',
    ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedUnauthorizedAccessMessage($method)
    {
        return "The 'administer node fields' permission is required.";
    }
}
