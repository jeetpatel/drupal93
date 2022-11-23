<?php

namespace Drupal\learning\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Alter routes class.
 */
class AlterRouteSubscriber extends RouteSubscriberBase {

  /**
   * Alter routes.
   *
   * @param \RouteCollection $collection
   *   RouteCollection object.
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('user.login')) {
      $route->setPath('/login');
    }
  }

}
