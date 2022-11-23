<?php

namespace Drupal\custom_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Source Plugin for MD5 Hash Algorithm.
 *
 * @MigrateSource(
 *   id = "md5_hash_data"
 * )
 */
class Md5Hash extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $fields = [
      'id',
      'user_name',
      'password',
      'mobile',
      'contact_person',
      'status',
      'add_date',
      'role_id',
    ];
    \Drupal::logger('migrate_source')->info('md5_hash_data');
    return $this->select('md5_hash', 'mh')
      ->fields('mh', $fields);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'id' => $this->t('User ID'),
      'user_name' => $this->t('User Name'),
      'password' => $this->t('Account password (raw)'),
      'mobile' => $this->t('Mobile'),
      'contact_person' => $this->t('Display Name'),
      'status' => $this->t('Status'),
      'add_date' => $this->t('Add Date'),
      'role_id' => $this->t('Role'),
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
        'alias' => 'mh',
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
    if ($value = $row->getSourceProperty('add_date')) {
      $row->setSourceProperty('add_date', strtotime($value));
    }
    $row->setSourceProperty('mail', $row->getSourceProperty('user_name'));
    $row->setSourceProperty('user_name', explode('@', $row->getSourceProperty('user_name'))[0]);
    $role_id = $row->getSourceProperty('role_id');
    $roles = ['1' => 'administrator', '2' => 'authenticated', '3' => 'editor'];
    $row->setSourceProperty('role_id', $roles[$role_id]);
    // Always call your parent! Essential processing is performed in the base
    // class. Be mindful that prepareRow() returns a boolean status - if FALSE
    // that indicates that the item being processed should be skipped. Unless
    // we're deciding to skip an item ourselves, let the parent class decide.
    return parent::prepareRow($row);
  }

}
