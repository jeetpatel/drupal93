<?php

namespace Drupal\Core\Database;

@trigger_error('\Drupal\Core\Database\StatementEmpty is deprecated in drupal:9.2.0 and is removed from drupal:10.0.0. There is no replacement. Use mocked StatementInterface classes in tests if needed. See https://www.drupal.org/node/3201283', E_USER_DEPRECATED);

/**
 * Empty implementation of a database statement.
 *
 * This class satisfies the requirements of being a database statement/result
 * object, but does not actually contain data.  It is useful when developers
 * need to safely return an "empty" result set without connecting to an actual
 * database.  Calling code can then treat it the same as if it were an actual
 * result set that happens to contain no records.
 *
 * @see \Drupal\search\SearchQuery
 *
 * @deprecated in drupal:9.2.0 and is removed from drupal:10.0.0. There is no
 *   replacement. Use mocked StatementInterface classes in tests if needed.
 *
 * @see https://www.drupal.org/node/1234567
 */
class StatementEmpty implements \Iterator, StatementInterface
{
  /**
   * Is rowCount() execution allowed.
   *
   * @var bool
   */
    public $allowRowCount = false;

    /**
     * {@inheritdoc}
     */
    public function execute($args = [], $options = [])
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rowCount()
    {
        if ($this->allowRowCount) {
            return 0;
        }
        throw new RowCountException();
    }

    /**
     * {@inheritdoc}
     */
    public function setFetchMode($mode, $a1 = null, $a2 = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($mode = null, $cursor_orientation = null, $cursor_offset = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchField($index = 0)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchObject(string $class_name = null, array $constructor_arguments = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAssoc()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($mode = null, $column_index = null, $constructor_arguments = null)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function fetchCol($index = 0)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllKeyed($key_index = 0, $value_index = 1)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllAssoc($key, $fetch = null)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        // Nothing to do: our DatabaseStatement can't be rewound.
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        // Do nothing, since this is an always-empty implementation.
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return false;
    }
}
