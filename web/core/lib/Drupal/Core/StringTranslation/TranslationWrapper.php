<?php

namespace Drupal\Core\StringTranslation;

/**
 * Provides translatable string class.
 *
 * @deprecated in drupal:8.0.0 and is removed from drupal:10.0.0.
 *   Use the \Drupal\Core\StringTranslation\TranslatableMarkup class instead.
 *
 * @see https://www.drupal.org/node/2571255
 */
class TranslationWrapper extends TranslatableMarkup
{
  /**
   * {@inheritdoc}
   */
    public function __construct($string, array $arguments = [], array $options = [], TranslationInterface $string_translation = null)
    {
        @trigger_error(__CLASS__ . ' is deprecated in drupal:8.0.0 and is removed from drupal:10.0.0. Use the \Drupal\Core\StringTranslation\TranslatableMarkup class instead. See https://www.drupal.org/node/2571255', E_USER_DEPRECATED);
        parent::__construct($string, $arguments, $options, $string_translation);
    }
}
