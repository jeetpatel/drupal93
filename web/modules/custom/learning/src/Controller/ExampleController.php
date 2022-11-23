<?php

namespace Drupal\learning\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\TypedData\ComplexDataInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Psr7\Request;
use Drupal\node\Entity\Node;

/**
 * Controller class file.
 *
 * Class ExampleController.
 */
class ExampleController extends ControllerBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    return $instance;
  }

  /**
   * Test.
   *
   * @return string
   *   Return Hello string
   */
  public function content(Node $product, $brand) {
    $productName = $product->getTitle();
    $body = $product->body->value;
    // Also get from parameters.
    //$brand = \Drupal::routeMatch()->getParameter('brand');
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Brand Name: @brand Product Name: @product, Description: @body', [
        '@brand' => $brand,
        '@product' => $productName,
        '@body' => $this->t('@body', ['@body' => $body]),
        ])
    ];
  }
  
  public function processStateData() {
    $data = [
      'Delhi', 'Uttar Pradesh', 'Maharastra', 'Madhya Pradesh', 'GOA New 3',
    ];
    \Drupal::logger('processStateData')->info('Process');
    $message = $this->t('Welcome <strong>Jeet</strong>. Current Time : @time', ['@time' => date('Y-m-d H:i:s')]);
    $renderer = \Drupal::service('renderer');
    $build = [
      '#theme' => 'state_list',
      //'#type' => 'block',
      '#markup' => 'Hello markup',
      //'#plain_text' => $message,
      '#state_data' => $data,
      '#custom_message' => $message,
      '#cache' => ['max-age' => -1, 'contexts' => ['url.query_args', 'session']],
    ];
    return $build;
  }
  
  public function viewEntity($custom_entity_id, $custom_entity_type = NULL) {
    $title = $custom_entity_id->hasField('title') ? $custom_entity_id->getTitle() : $custom_entity_id->getName();
    return [
      '#markup' => 'Type => ' . $custom_entity_id->bundle() . ', Title => ' . $title
    ];
    \kint::dump($custom_entity_id->bundle(), $custom_entity_id, $custom_entity_type); die;
  }

}
