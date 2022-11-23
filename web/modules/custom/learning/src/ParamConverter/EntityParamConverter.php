<?php

namespace Drupal\learning\ParamConverter;

use Drupal\Core\ParamConverter\ParamConverterInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\Routing\Route;
use Drupal\taxonomy\Entity\Term;


class EntityParamConverter implements ParamConverterInterface {
  
  public function convert($value, $definition, $name, array $defaults) {
    if ($defaults['custom_entity_type'] == 'term') {
      return Term::load($value);
    }
    if ($defaults['custom_entity_type'] == 'node') {
      return Node::load($value);
    }
  }

  public function applies($definition, $name, Route $route) {
    \Drupal::logger('applies')->info(print_r($name, TRUE));
    return (!empty($definition['type']) && $definition['type'] == 'entity_id');
  }

}
