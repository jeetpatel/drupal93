<?php

namespace Drupal\learning\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Feature News block.
 *
 * @Block(
 *   id = "learning_featurenews",
 *   admin_label = @Translation("Feature News"),
 *   category = @Translation("Custom")
 * )
 */
class FeatureNewsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'title' => $this->t('Featured New'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block Title'),
      '#default_value' => $this->configuration['title'],
    ];
    $form['news_api'] = [
      '#type' => 'radios',
      '#title' => $this->t('NEWS API'),
      '#options' => [
        'google' => 'Google',
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
      ],
      '#default_value' => 'google',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['title'] = $form_state->getValue('title');
    $this->configuration['news_api'] = $form_state->getValue('news_api');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $api = NULL;
    $newsApi = isset($this->configuration['news_api']) ? $this->configuration['news_api'] : 'google';
    if ($newsApi == 'google') {
      $api = 'Google news is here';
    }
    elseif ($newsApi == 'facebook') {
      $api = 'Facebook feed is here';
    }
    elseif ($newsApi == 'twitter') {
      $api = 'Twitter feeds is here';
    }
    $build['content'] = [
      '#markup' => $this->t('<h2>@title</h2><p>@api</p>', [
        '@title' => $this->configuration['title'],
        '@api' => $api,
      ]),
    ];
    return $build;
  }

}
