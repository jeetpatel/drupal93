<?php

namespace Drupal\learning\Exception;

/**
 * Learnign exception class.
 */
class LearningException extends \Exception {

  /**
   * Check empty value.
   *
   * @param string $value
   *   String value.
   */
  public function checkEmpty($value) {
    if (empty($value)) {
      throw new LearningException("Value should not empty.");
    }
  }

}
