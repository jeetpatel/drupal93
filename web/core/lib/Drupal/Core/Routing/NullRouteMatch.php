<?php

namespace Drupal\Core\Routing;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Stub implementation of RouteMatchInterface for when there's no matched route.
 */
class NullRouteMatch implements RouteMatchInterface
{
  /**
   * {@inheritdoc}
   */
    public function getRouteName()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteObject()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($parameter_name)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        return new ParameterBag();
    }

    /**
     * {@inheritdoc}
     */
    public function getRawParameter($parameter_name)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawParameters()
    {
        return new ParameterBag();
    }
}
