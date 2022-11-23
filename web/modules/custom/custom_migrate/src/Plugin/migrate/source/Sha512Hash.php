<?php

namespace Drupal\custom_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source Plugin for SHA512 Algorithm migration.
 *
 * @MigrateSource(
 *   id = "sha512_hash_data"
 * )
 */
class Sha512Hash extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $fields = [
      'id',
      'user_login',
      'user_email',
      'user_pass',
      'user_status',
      'role_id',
      'created_at'
    ];
    \Drupal::logger('migrate_source')->info('sha512_hash_data');
    return $this->select('sha512_users', 'su')
      ->fields('su', $fields);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('User ID'),
      'user_login' => $this->t('User Login'),
      'user_email' => $this->t('Email'),
      'user_pass' => $this->t('Password'),
      'user_status' => $this->t('Status'),
      'role_id' => $this->t('Role'),
      'created_at' => $this->t('Add Date'),
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
        'alias' => 'su',
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
    if ($value = $row->getSourceProperty('created_at')) {
      $row->setSourceProperty('created_at', strtotime($value));
    }
    $row->setSourceProperty('user_pass', '$SHA512$' . $row->getSourceProperty('user_pass'));
    // Always call your parent! Essential processing is performed in the base
    // class. Be mindful that prepareRow() returns a boolean status - if FALSE
    // that indicates that the item being processed should be skipped. Unless
    // we're deciding to skip an item ourselves, let the parent class decide.
    return parent::prepareRow($row);
  }

}
