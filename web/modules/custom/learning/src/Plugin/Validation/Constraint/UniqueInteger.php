<?php

namespace Drupal\learning\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that the submitted value is a unique integer.
 *
 * @Constraint(
 *   id = "UniqueInteger",
 *   label = @Translation("Unique Integer", context = "Validation"),
 *   type = "string"
 * )
 */
class UniqueInteger extends Constraint {

  // The message that will be shown if the value is not an integer.
  public $notInteger = '%value is not an integer';

  // The message that will be shown if the value is not unique.
  public $notValid = '%value is not valid credit card';
  public $countMatch = '%value should have %count digits';
  public $count;

}