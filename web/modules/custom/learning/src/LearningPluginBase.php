<?php

namespace Drupal\learning;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for learning plugins.
 */
abstract class LearningPluginBase extends PluginBase implements LearningInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

}
