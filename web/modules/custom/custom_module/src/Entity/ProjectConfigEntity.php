<?php

namespace Drupal\custom_module\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\custom_module\ProjectConfigEntityInterface;

/**
 * Defines the project configuration entity entity type.
 *
 * @ConfigEntityType(
 *   id = "project_config_entity",
 *   label = @Translation("Project Configuration Entity"),
 *   label_collection = @Translation("Project Configuration Entities"),
 *   label_singular = @Translation("project configuration entity"),
 *   label_plural = @Translation("project configuration entities"),
 *   label_count = @PluralTranslation(
 *     singular = "@count project configuration entity",
 *     plural = "@count project configuration entities",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\custom_module\ProjectConfigEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\custom_module\Form\ProjectConfigEntityForm",
 *       "edit" = "Drupal\custom_module\Form\ProjectConfigEntityForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "project_config_entity",
 *   admin_permission = "administer project_config_entity",
 *   links = {
 *     "collection" = "/admin/structure/project-config-entity",
 *     "add-form" = "/admin/structure/project-config-entity/add",
 *     "edit-form" = "/admin/structure/project-config-entity/{project_config_entity}",
 *     "delete-form" = "/admin/structure/project-config-entity/{project_config_entity}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "description",
 *     "configuration",
 *     "json_configuration"
 *   }
 * )
 */
class ProjectConfigEntity extends ConfigEntityBase implements ProjectConfigEntityInterface {

  /**
   * The project configuration entity ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The project configuration entity label.
   *
   * @var string
   */
  protected $label;

  /**
   * The project configuration entity status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The project_config_entity description.
   *
   * @var string
   */
  protected $description;
  
  /**
   * The project_config_entity configuration field.
   *
   * @var string
   */
  protected $configuration;

  /**
   * The project_config_entity json_configuration field.
   *
   * @var string
   */
  protected $json_configuration;

  /**
   * Get Configuration value.
   *
   * @return string
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * Get Configuration in JSON format.
   *
   * @return json
   */
  public function getJsonConfiguration() {
    return $this->json_configuration;
  }

}
