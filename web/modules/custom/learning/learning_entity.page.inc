<?php

/**
 * @file
 * Contains learning_entity.page.inc.
 *
 * Page callback for Learning entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Learning entity templates.
 *
 * Default template: learning_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_learning_entity(array &$variables) {
  // Fetch LearningEntity Entity Object.
  $learning_entity = $variables['elements']['#learning_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
