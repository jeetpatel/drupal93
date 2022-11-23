<?php

namespace Drupal\learning\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines dynamic routes.
 */
class ExampleRoutes {

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $collection = new RouteCollection();
    $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $terms = $storage->loadByProperties(['vid' => 'brand']);
    //foreach (\Drupal::entityTypeManager()->getDefinitions() as $entity_type_name => $entity_type) { }
    foreach ($terms as $term) {
      $brand_name = strtolower($term->getName());
      $route = new Route(
      $brand_name . '/{product}', [
          '_title' => 'Product Details',
          '_controller' => '\Drupal\learning\Controller\ExampleController::content',
          'brand' => $brand_name,
        ],
        [
          '_permission' => 'access content'
        ],
        [
          'parameters' => [
            'product' => ['type' => 'entity:node']
          ]
        ]
      );
      $collection->add("product.$brand_name", $route);
    }
    return $collection;
  }

}
