<?php

namespace Drupal\learning\Services;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\HttpFoundation\Request;
use Psr\Http\Message\ServerRequestInterface;

class LearningService {

  private $database;
  
  /**
   * {@inheritdoc}
   */
  public function __construct(Connection $connection) {
    $this->database = $connection;
  }

  /**
   * Get news by news API.
   *
   * @param string $newsApi
   *   News API.
   *
   * @return string
   *   Return news body.
   */
  public function getNews($newsApi) {
    $query = $this->database->select('news', 'n');
    $query->fields('n', ['body']);
    $query->condition('news_type', $newsApi);
    return $query->execute()->fetchField();
  }

  public function canRedirect($request, $route_name = NULL) {
    if (!empty($route_name)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  public function getName($term, EntityInterface $node, Request $request, RouteMatchInterface $routeMatch, ServerRequestInterface $server) {
    return [
      '#markup' => t('Method called from services Node:@node, Term:@term', [
        '@node' => $node->getTitle(),
        '@term' => $term,
      ])
    ];
  }

}
