<?php

namespace Drupal\Core\Access;

/**
 * Value object indicating a neutral access result, with cacheability metadata.
 */
class AccessResultNeutral extends AccessResult implements AccessResultReasonInterface
{
  /**
   * The reason why access is neutral. For use in messages.
   *
   * @var string|null
   */
    protected $reason;

    /**
     * Constructs a new AccessResultNeutral instance.
     *
     * @param null|string $reason
     *   (optional) A message to provide details about this access result
     */
    public function __construct($reason = null)
    {
        $this->reason = $reason;
    }

    /**
     * {@inheritdoc}
     */
    public function isNeutral()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * {@inheritdoc}
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }
}
