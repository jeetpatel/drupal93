<?php

namespace Drupal\learning\Services;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;

class CustomService {
  
  /**
   * Database connection object.
   *
   * @var Drupal\Core\Database\Connection
   */
  private $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }
  
  public function getNodes() {
    return $this->database->select('node_field_data', 'n')
      ->fields('n')
      ->range(0, 5)
      ->execute()->fetchAll(\PDO::FETCH_ASSOC);
  }
  
  
}