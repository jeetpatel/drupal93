<?php

namespace Drupal\Core\PageCache\ResponsePolicy;

use Drupal\Core\PageCache\ResponsePolicyInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A policy evaluating to static::DENY when the kill switch was triggered.
 */
class KillSwitch implements ResponsePolicyInterface
{
  /**
   * A flag indicating whether the kill switch was triggered.
   *
   * @var bool
   */
    protected $kill = false;

    /**
     * {@inheritdoc}
     */
    public function check(Response $response, Request $request)
    {
        if ($this->kill) {
            return static::DENY;
        }
    }

    /**
     * Deny any page caching on the current request.
     */
    public function trigger()
    {
        $this->kill = true;
    }
}
