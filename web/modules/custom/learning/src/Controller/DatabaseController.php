<?php

namespace Drupal\learning\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Kint\Kint;

/**
 * Controller class file.
 *
 * Class DatabaseController.
 */
class DatabaseController extends ControllerBase {

  /**
   * Database Connection object.
   *
   * @var Drupal\Core\Database\Connection
   */
  protected $replicaConnection;

  /**
   * Default database connection.
   *
   * @var \Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->replicaConnection = $container->get('database');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * Test.
   *
   * @return string
   *   Return Hello string
   */
  public function replicaExamples() {
    //$nodeEntity = $this->entityTypeManager()->getStorage('node');
    //$node = $nodeEntity->load(1);
    //Kint::dump($node);
    //die;
    $this->replicaConnection = \Drupal\Core\Database\Database::getConnection('replica');
    $select = $query = $this->replicaConnection->select('node_field_data', 'n');
    $query->fields('n', ['uid']);
    $query->exists($select, 'revision_translation_affected');
    $result = $query->execute();
    echo '<pre>';
    print_r($result->fetchAll());
    echo '</pre>';
    die;
    $result = $this->replicaConnection->query('SELECT * FROM {employee} WHERE status=:status ',[
      ':status' => 1,
    ],
    [
      'fetch' => \PDO::FETCH_ASSOC,
      'target' => 'replica'
    ]);
    echo '<pre>'; print_r($result->getQueryString()); echo '</pre>';   
    echo '<pre>'; print_r('Query method::'); echo '</pre>';
    foreach ($result as $row) {
      echo '<pre>'; print_r($row); echo '</pre>';
    }
    die;
    
//    while ($row = $result->fetch()) {
//      echo '<pre>';
//      print_r($row);
//      echo '</pre>';
//    }
    echo '<hr>Select statement with Execute & fetchAll()::';
    $query = $this->replicaConnection->select('employee', 'e')
      ->fields('e', ['first_name', 'last_name', 'classes']);
    //$query->addField('e', 'id', 'emp_id');
    //$query->addField('e', 'first_name', 'emp_id');
    echo '<pre>CountQuery()::'; print_r($query->countQuery()->execute()->fetchField()); echo '</pre>';
    $results = $query->execute();
    foreach ($results as $row) {
      echo '<pre>'; print_r($row); echo '</pre>';
    }
    echo '<pre>'; print_r('Select condition()::'); echo '</pre>';
    $query = $this->connection->select('node_field_data', 'n')
      ->fields('n', ['nid', 'title', 'vid']);
    $results = $query->execute()->fetchAllKeyed(0, 2);
    //echo '<pre>'; print_r($query->__toString()); echo '</pre>';
    echo '<pre>'; print_r($results); echo '</pre>';

    echo '<hr>Select with Execute()::';
    $results = $this->replicaConnection->select('employee', 'e')
      ->fields('e')->condition('status', 1);
    echo '<pre>Arguments::'; print_r($results->arguments()); echo '</pre>';
    $results = $results->execute();
    foreach ($results as $row) {
      echo '<pre>'; print_r($row); echo '</pre>';
    }
    
    echo '<pre><hr>'; print_r('Right Join Examples'); echo '</pre>';
    $query = $this->replicaConnection->select('employee', 'e');
    $query->addJoin('INNER', 'department', 'd', 'e.id = d.emp_id');
    //$query->rightJoin('department', 'd', 'e.id = d.emp_id');
    $query->fields('e');
    $query->fields('d');
    $results = $query->execute();
    foreach ($results as $row) {
      echo '<pre>'; print_r($row); echo '</pre>';
    }
    echo '<pre>'; print_r('<hr>'); echo '</pre>';
    echo '<pre>'; print_r('Upsert Example'); echo '</pre>';
    $query = $this->replicaConnection->upsert('employee')
      ->fields(['id', 'first_name','last_name', 'roll_number'])
      ->key('id')
      ->values(['id' => 5, 'first_name' => 'Manoj', 'last_name' => 'Mishra', 'roll_number' => rand(999, 9999)]);
    $n = $query->execute();
    echo '<pre>'; print_r($n); echo '</pre>';
    die;
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Database API examples'),
    ];
  }

}
