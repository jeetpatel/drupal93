<?php

namespace Drupal\learning\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Learning entity entity.
 *
 * @ingroup learning
 *
 * @ContentEntityType(
 *   id = "learning_entity",
 *   label = @Translation("Learning Entity"),
 *   revision_metadata_keys = {
 *      "revision_user" = "revision_user",
 *      "revision_created" = "revision_created",
 *      "revision_log_message" = "revision_log_message",
 *   },
 *   handlers = {
 *     "storage" = "Drupal\learning\LearningEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\learning\LearningEntityListBuilder",
 *     "views_data" = "Drupal\learning\Entity\LearningEntityViewsData",
 *     "translation" = "Drupal\learning\LearningEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\learning\Form\LearningEntityForm",
 *       "add" = "Drupal\learning\Form\LearningEntityForm",
 *       "edit" = "Drupal\learning\Form\LearningEntityForm",
 *       "delete" = "Drupal\learning\Form\LearningEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\learning\LearningEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\learning\LearningEntityAccessControlHandler",
 *   },
 *   base_table = "learning_entity",
 *   data_table = "learning_entity_field_data",
 *   revision_table = "learning_entity_revision",
 *   revision_data_table = "learning_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer learning entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/learning_entity/{learning_entity}",
 *     "add-form" = "/admin/structure/learning_entity/add",
 *     "edit-form" = "/admin/structure/learning_entity/{learning_entity}/edit",
 *     "delete-form" = "/admin/structure/learning_entity/{learning_entity}/delete",
 *     "version-history" = "/admin/structure/learning_entity/{learning_entity}/revisions",
 *     "revision" = "/admin/structure/learning_entity/{learning_entity}/revisions/{learning_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/learning_entity/{learning_entity}/revisions/{learning_entity_revision}/revert",
 *     "revision_delete" = "/admin/structure/learning_entity/{learning_entity}/revisions/{learning_entity_revision}/delete",
 *     "translation_revert" = "/admin/structure/learning_entity/{learning_entity}/revisions/{learning_entity_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/learning_entity",
 *   },
 *   field_ui_base_route = "learning_entity.settings"
 * )
 */
class LearningEntity extends EditorialContentEntityBase implements LearningEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly,
    // make the learning_entity owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Learning entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Learning entity entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
    
    $fields['credit_card'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Credit Card Number'))
      ->setDescription(t('Enter 16 digits Credit card number.'))
      ->setRevisionable(TRUE)
      ->addConstraint('UniqueInteger', ['count' => 16])
      ->setSettings([
        'max_length' => 16,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
    
    $fields['cvv'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('CVV Number'))
      ->setDescription(t('Enter 3 digits VCC number.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 3,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['subject'] = BaseFieldDefinition::create('string')
        ->setLabel(t('Subject'))
        ->setDescription(t('Learning Subject.'))
        ->setSettings([
          'max_length' => 50,
          'text_processing' => 0,
        ])
        ->setDefaultValue('')
        ->setDisplayOptions('view', [
          'label' => 'above',
          'type' => 'string',
          'weight' => -4,
        ])
        ->setDisplayOptions('form', [
          'type' => 'string_textfield',
          'weight' => -4,
        ])
        ->setDisplayConfigurable('form', TRUE)
        ->setDisplayConfigurable('view', TRUE)
        ->setRequired(TRUE);

      // $fields['value_scheme'] = BaseFieldDefinition::create('decimal')
      // ->setLabel(t('Scheme (`)'))
      // ->setDescription(t('Scheme (`).'))
      // ->setRevisionable(TRUE)
      // ->setSettings([
      //   'decimal_separator' => '.',
      //   'precision' => 10,
      //   'scale' => 4,
      //   ])->setDefaultValue('')
      // ->setDisplayOptions('view', [
      //   'label' => 'above',
      //   'type' => 'number_decimal',
      //   'weight' => -4,
      // ])
      // ->setDisplayOptions('form', [
      //   'type' => 'numeric',
      //   'weight' => -4,
      // ])
      // ->setDisplayConfigurable('form', TRUE)
      // ->setDisplayConfigurable('view', TRUE)
      // ->setRequired(TRUE);

    // $fields['value_benchmark'] = BaseFieldDefinition::create('decimal')
    //   ->setLabel(t('Benchmark #(`)'))
    //   ->setDescription(t('Benchmark #(`).'))
    //   ->setRevisionable(TRUE)
    //   ->setSettings([
    //     'decimal_separator' => '.',
    //     'precision' => 10,
    //     'scale' => 4,
    //     ])->setDefaultValue('')
    //   ->setDisplayOptions('view', [
    //     'label' => 'above',
    //     'type' => 'decimal',
    //     'weight' => -4,
    //   ])
    //   ->setDisplayOptions('form', [
    //     'type' => 'decimal_textfield',
    //     'weight' => -4,
    //   ])
    //   ->setDisplayConfigurable('form', TRUE)
    //   ->setDisplayConfigurable('view', TRUE)
    //   ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Learning entity is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

  public static function link($entity) {
    //kint($entity);
    return $entity;
  }

}
