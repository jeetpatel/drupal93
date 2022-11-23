<?php

namespace Drupal\learning\Plugin\Learning;

use Drupal\learning\LearningPluginBase;

/**
 * Plugin implementation of the learning.
 *
 * @Learning(
 *   id = "foo",
 *   label = @Translation("Foo"),
 *   description = @Translation("Foo description.")
 * )
 */
class Foo extends LearningPluginBase {

  public function label() {
    return 'Foo Plugin';
  }

}
