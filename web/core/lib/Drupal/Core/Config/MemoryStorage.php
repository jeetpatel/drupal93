<?php

namespace Drupal\Core\Config;

/**
 * Provides an in memory configuration storage.
 */
class MemoryStorage implements StorageInterface
{
  /**
   * The configuration, an object shared by reference across collections.
   *
   * @var \ArrayAccess
   */
    protected $config;

    /**
     * The storage collection.
     *
     * @var string
     */
    protected $collection;

    /**
     * Constructs a new MemoryStorage.
     *
     * @param string $collection
     *   (optional) The collection to store configuration in. Defaults to the
     *   default collection.
     */
    public function __construct($collection = StorageInterface::DEFAULT_COLLECTION)
    {
        $this->collection = $collection;
        $this->config = new \ArrayObject();
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        return isset($this->config[$this->collection][$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function read($name)
    {
        if ($this->exists($name)) {
            return $this->config[$this->collection][$name];
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function readMultiple(array $names)
    {
        return array_intersect_key($this->config[$this->collection], array_flip($names));
    }

    /**
     * {@inheritdoc}
     */
    public function write($name, array $data)
    {
        $this->config[$this->collection][$name] = $data;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($name)
    {
        if (isset($this->config[$this->collection][$name])) {
            unset($this->config[$this->collection][$name]);
            // Remove the collection if it is empty.
            if (empty($this->config[$this->collection])) {
                $this->config->offsetUnset($this->collection);
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rename($name, $new_name)
    {
        if (!$this->exists($name)) {
            return false;
        }
        $this->config[$this->collection][$new_name] = $this->config[$this->collection][$name];
        unset($this->config[$this->collection][$name]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($raw)
    {
        return $raw;
    }

    /**
     * {@inheritdoc}
     */
    public function listAll($prefix = '')
    {
        if (empty($this->config[$this->collection])) {
            // If the collection is empty no keys are set.
            return [];
        }
        $names = array_keys($this->config[$this->collection]);
        if ($prefix !== '') {
            $names = array_filter($names, function ($name) use ($prefix) {
                return strpos($name, $prefix) === 0;
            });
        }
        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAll($prefix = '')
    {
        if (!$this->config->offsetExists($this->collection)) {
            // There's nothing to delete.
            return false;
        }
        if ($prefix === '') {
            $this->config->offsetUnset($this->collection);
            return true;
        }
        $success = false;
        foreach (array_keys($this->config[$this->collection]) as $name) {
            if (strpos($name, $prefix) === 0) {
                $success = true;
                unset($this->config[$this->collection][$name]);
            }
        }
        // Remove the collection if it is empty.
        if (empty($this->config[$this->collection])) {
            $this->config->offsetUnset($this->collection);
        }

        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function createCollection($collection)
    {
        $collection = new static($collection);
        $collection->config = $this->config;
        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllCollectionNames()
    {
        $collection_names = [];
        foreach ($this->config as $collection_name => $data) {
            // Exclude the default collection and empty collections.
            if ($collection_name !== StorageInterface::DEFAULT_COLLECTION && !empty($data)) {
                $collection_names[] = $collection_name;
            }
        }
        sort($collection_names);

        return $collection_names;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollectionName()
    {
        return $this->collection;
    }
}
