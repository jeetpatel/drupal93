<?php

namespace Drupal\learning\Plugin\Menu\LocalAction;

use Drupal\Core\Menu\LocalActionDefault;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines a local action plugin with a dynamic title.
 */
class CustomLocalAction extends LocalActionDefault {

  /**
   * {@inheritdoc}
   */
  public function getTitle($request = NULL) {
    $title = new TranslatableMarkup("My Employee @time", array(
      '@time' => date('H:i:s'),
    ));
    return $title;
  }

}