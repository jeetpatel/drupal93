<?php

namespace Drupal\dcs\Services;

use Drupal\Core\Database\Connection;
use Drupal\Core\Logger\LoggerChannelFactory;

/**
 * Service for the dcs module to write reusable functionality.
 */
class DcsService {

  // Class constant variables.
  const DCS_RULES = 'dcs_rules';

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Logger service object.
   *
   * @var \LoggerChannelFactory
   */
  protected $logger;

  /**
   * Constructs a DcsService object.
   *
   * @param \Connection $connection
   *   The database connection.
   * @param \LoggerChannelFactory $loggerChannelFactory
   *   The logger channel factory service.
   */
  public function __construct(Connection $connection, LoggerChannelFactory $loggerChannelFactory) {
    $this->connection = $connection;
    $this->logger = $loggerChannelFactory->get('dcs_service');
  }

  /**
   * Create new rules.
   *
   * @param array $data
   *   Data for new rules in array.
   *
   * @return bool|int
   *   Return insert ID else FALSE.
   */
  public function createRules(array $data) {
    try {
      $this->logger->error('createRules() inserting data @data', [
        '@data' => print_r($data, TRUE),
      ]);
      /*
       * Insert data into table and return last insert ID
       * If not inserted return FALSE.
       */
      return $this->connection->insert(self::DCS_RULES)
        ->fields($data)
        ->execute();
    }
    catch (\Exception $e) {
      $this->logger->error('createRules() exception @exception', [
        '@exception' => $e->getMessage(),
      ]);
      return FALSE;
    }
  }

  /**
   * Fetch rules list by parameters.
   *
   * @param array $args
   *   Params to filter data.
   *
   * @return object
   *   Return data as object.
   */
  public function getRulesList(array $args = []) {
    $query = $this->connection->select(self::DCS_RULES, 'r')
      ->fields('r');
    // Add all equal condtion in the WHERE clause.
    foreach ($args as $key => $value) {
      $query->condition($key, $value);
    }
    return $query->execute()->fetchAll();
  }

  /**
   * Add log message.
   *
   * @param string $type
   *   Error type.
   * @param string $message
   *   Error log message.
   *
   * @return bool
   *   Return TRUE.
   */
  public function addLog($type, $message) {
    $this->logger->$type('@message', [
      '@message' => $message,
    ]);
    return TRUE;
  }

}
