<?php

namespace Drupal\learning\TwigExtension;

use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Database\Driver\mysql\Connection;
use Twig\Extension\AbstractExtension;
#use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Drupal\node\Entity\Node;
use Twig\TwigFunction;

/**
 * Class FilterTwigExtension.
 */
class FilterTwigExtension extends \Twig_Extension {

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var Connection
   */
  protected $database;

  /**
   * Constructs a new FilterTwigExtension object.
   */
  public function __construct(RendererInterface $renderer, Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [
      new TwigFilter('replace_spaces', [$this, 'replaceSpaces']),
    ];
  }

  public function getFunctions() {
    return [
      new TwigFunction('print_author', [$this, 'printAuthor']),
    ];
  }

  public function printAuthor($node) {
    if (!($node instanceof Node)) {
      return;
    }
    $user = $node->getOwner();
    return $user->getDisplayName();
  }

  /**
   * {@inheritdoc}
   */
  public function replaceSpaces($string) {
    return preg_replace("/[\s_]/", "_", strtoupper($string));
  }

  /**
   * {@inheritdoc}
   */
//    public function getName() {
//      return 'learning.twig.extension';
//    }
}
