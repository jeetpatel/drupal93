<?php

namespace Drupal\learning;

/**
 * Interface for learning plugins.
 */
interface LearningInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

}
