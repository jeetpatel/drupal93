<?php

namespace Drupal\custom_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source Plugin for Portable Phpass migration.
 *
 * @MigrateSource(
 *   id = "portable_phpass_data"
 * )
 */
class PortablePhpass extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $fields = [
      'id',
      'user_login',
      'user_pass',
      'user_email',
      'display_name',
      'user_status',
      'user_registered',
    ];
    \Drupal::logger('migrate_source')->info('portable_phpass_data');
    return $this->select('wp_users', 'wp')
      ->fields('wp', $fields);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('User ID'),
      'user_login' => $this->t('User Name'),
      'user_pass' => $this->t('Password'),
      'user_email' => $this->t('Email'),
      'display_name' => $this->t('Display Name'),
      'user_status' => $this->t('Status'),
      'user_registered' => $this->t('Add Date'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'id' => [
        'type' => 'integer',
        'alias' => 'wp',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // A prepareRow() is the most common place to perform custom run-time
    // processing that isn't handled by an existing process plugin. It is called
    // when the raw data has been pulled from the source, and provides the
    // opportunity to modify or add to that data, creating the canonical set of
    // source data that will be fed into the processing pipeline.
    // In our particular case, the list of a user's favorite beers is a pipe-
    // separated list of beer IDs. The processing pipeline deals with arrays
    // representing multi-value fields naturally, so we want to explode that
    // string to an array of individual beer IDs.
    if ($value = $row->getSourceProperty('user_registered')) {
      $row->setSourceProperty('user_registered', strtotime($value));
    }
    $status = $row->getSourceProperty('user_status');
    $row->setSourceProperty('role_id', 'authenticated');
    $row->setSourceProperty('user_status', ($status == 0) ? 1 : 0);
    // Always call your parent! Essential processing is performed in the base
    // class. Be mindful that prepareRow() returns a boolean status - if FALSE
    // that indicates that the item being processed should be skipped. Unless
    // we're deciding to skip an item ourselves, let the parent class decide.
    return parent::prepareRow($row);
  }

}
