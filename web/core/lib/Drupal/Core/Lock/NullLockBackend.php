<?php

namespace Drupal\Core\Lock;

/**
 * Defines a Null lock backend.
 *
 * This implementation won't actually lock anything and will always succeed on
 * lock attempts.
 *
 * @ingroup lock
 */
class NullLockBackend implements LockBackendInterface
{
  /**
   * Current page lock token identifier.
   *
   * @var string
   */
    protected $lockId;

    /**
     * {@inheritdoc}
     */
    public function acquire($name, $timeout = 30.0)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function lockMayBeAvailable($name)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function wait($name, $delay = 30)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function release($name)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function releaseAll($lock_id = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getLockId()
    {
        if (!isset($this->lockId)) {
            $this->lockId = uniqid(mt_rand(), true);
        }
        return $this->lockId;
    }
}
