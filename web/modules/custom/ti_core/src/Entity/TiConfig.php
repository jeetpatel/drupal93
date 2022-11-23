<?php

namespace Drupal\ti_core\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\ti_core\TiConfigInterface;

/**
 * Defines the teachit configuration entity entity type.
 *
 * @ConfigEntityType(
 *   id = "ti_config",
 *   label = @Translation("Teachit Configuration Entity"),
 *   label_collection = @Translation("Teachit Configuration Entities"),
 *   label_singular = @Translation("teachit configuration entity"),
 *   label_plural = @Translation("teachit configuration entities"),
 *   label_count = @PluralTranslation(
 *     singular = "@count teachit configuration entity",
 *     plural = "@count teachit configuration entities",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\ti_core\TiConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ti_core\Form\TiConfigForm",
 *       "edit" = "Drupal\ti_core\Form\TiConfigForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "ti_config",
 *   admin_permission = "administer ti_config",
 *   links = {
 *     "collection" = "/admin/structure/ti-config",
 *     "add-form" = "/admin/structure/ti-config/add",
 *     "edit-form" = "/admin/structure/ti-config/{ti_config}",
 *     "delete-form" = "/admin/structure/ti-config/{ti_config}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "configuration",
 *     "json_configuration",
 *     "optional_value1",
 *     "optional_value2",
 *     "description"
 *   }
 * )
 */
class TiConfig extends ConfigEntityBase implements TiConfigInterface {

  /**
   * The Teachit configuration entity ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Teachit configuration entity label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Teachit configuration entity status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The Teachit configuration description.
   *
   * @var string
   */
  protected $description;

  /**
   * The ti_config configuration.
   *
   * @var string
   */
  protected $configuration;

  /**
   * The ti_config json_configuration.
   *
   * @var string
   */
  protected $json_configuration;

  /**
   * The ti_config optional_value1.
   *
   * @var string
   */
  protected $optional_value1;

  /**
   * The ti_config optional_value2.
   *
   * @var string
   */
  protected $optional_value2;

  /**
   *  Get configuration value.
   *
   * @return string
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * Get JSON configuration value.
   *
   * @return string
   */
  public function getJsonConfiguration() {
    return $this->json_configuration;
  }

  /**
   * Get Optional Value 1.
   *
   * @return string
   */
  public function getOptionalValue1() {
    return $this->optional_value1;
  }

  /**
   * Get optional value 2.
   *
   * @return string
   */
  public function getOptionalValue2() {
    return $this->optional_value2;
  }

}
