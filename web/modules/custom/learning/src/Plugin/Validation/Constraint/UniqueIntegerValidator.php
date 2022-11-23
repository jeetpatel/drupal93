<?php

namespace Drupal\learning\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the UniqueInteger constraint.
 */
class UniqueIntegerValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    foreach ($items as $item) {
      // First check if the value is an integer.
      if (!is_numeric($item->value)) {
        // The value is not an integer, so a violation, aka error, is applied.
        // The type of violation applied comes from the constraint description
        // in step 1.
        $this->context->addViolation($constraint->notInteger, ['%value' => $item->value]);
      }
      if (strlen($item->value) != $constraint->count) {
//        $this->context->buildViolation($message)
//          ->setParameter($key, $value)
//          ->setParameter($key, $value)
//          ->setPlural($number)
//          ->addViolation();
        $this->context->addViolation($constraint->countMatch, ['%value' => $item->value ,'%count' => $constraint->count]);
      }

      // Next check if the value is unique.
      if (!$this->isValidCareditCard($item->value)) {
        $this->context->addViolation($constraint->notValid, ['%value' => $item->value]);
      }
    }
  }

  /**
   * Is unique?
   *
   * @param string $value
   */
  private function isValidCareditCard($value) {
    \Drupal::logger('isValidCareditCard')->info("Value: $value");
    if (substr($value, 0, 1) == 0) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

}