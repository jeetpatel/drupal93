<?php

namespace Drupal\learning\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Cache\Cache;

/**
 * Provides a Config block.
 *
 * @Block(
 *   id = "config_block",
 *   admin_label = @Translation("Config Block"),
 *   category = @Translation("Custom")
 * )
 */
class ConfigBlock extends BlockBase implements ContainerFactoryPluginInterface {

  private $database;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $connection) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition, $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'title' => $this->t('Configured New'),
      'news_api' => 'google',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Config Block Title'),
      '#default_value' => isset($config['title']) ? $config['title'] : '',
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
  public function blockValidate($form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    if (empty($title)) {
      $form_state->setErrorByName('title', $this->t('Required field can not be empty'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('title', $form_state->getValue('title'));
    $this->setConfigurationValue('news_api', $form_state->getValue('news_api'));
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $newsApi = isset($config['news_api']) ? $config['news_api'] : 'google';
    $learning = \Drupal::service('learning.get.learning');
    $build['content'] = [
      '#markup' => $this->t('<h2><strong>@title</strong></h2><p>@news_body</p><p>Cache time: @time</p>', [
        '@title' => ucwords($newsApi) . ' ' . $config['title'],
        '@news_body' => $learning->getNews($newsApi),
        '@time' => date('Y-m-d H:i:s'),
      ]),
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['config:config_block_jeet']);
  }

}
