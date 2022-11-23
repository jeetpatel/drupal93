<?php

namespace Drupal\learning\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a Employee Data block.
 *
 * @Block(
 *   id = "learning_employee_block",
 *   admin_label = @Translation("Employee Block"),
 *   category = @Translation("Custom"),
 * )
 */
class EmployeeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  private $database;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $connection, $entity_type) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $connection;
    $this->entityTypeManager = $entity_type;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $renderer = \Drupal::service('renderer');
    $config = \Drupal::config('system.site');
    // $build = [
    //   '#markup' => $this->t('Hi, welcome back to @site!', [
    //     '@site' => $config->get('name'),
    //   ]),
    // ];
    // $renderer->addCacheableDependency($build, $config);
    // return $build;
    // $node = $this->entityTypeManager->getstorage('node')->load(4);
    // return [
    //   '#cache' => [
    //     'tags' => [],
    //   ],
    //   '#markup' => 'Employee name:' . $node->gettitle(),
    // ];
    // SQL query to fetch data from table.
    $query = $this->database->select('employee_data', 'e');
    $query->fields('e', ['id', 'name', 'email', 'classes']);
    $query->condition('status', 1);
    $results = $query->execute();
    // Table Header.
    $header = ['ID', 'Name', 'Email', 'Classes'];
    $rowsData = [];
    // Prepare rows data.
    foreach ($results as $row) {
      //$rowsData[] = (array) $row;
      $rowsData[] = [
        $row->id,
        $row->name,
        $row->email,
        $row->classes,
      ];
    }
    // Build object.
    $build = [
      '#cache' => [
        'tags' => ['employee_data:list']
      ],
      '#markup' => $this->t('Showing employee data in table format. Time:@date', [
        '@date' => date('H:i:s'),
      ]),
      'table' => [
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $rowsData
      ],
    ];
    return $build;
  }

}
