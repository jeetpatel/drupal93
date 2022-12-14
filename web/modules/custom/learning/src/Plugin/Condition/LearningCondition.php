<?php

namespace Drupal\learning\Plugin\Condition;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Learning Condition' condition.
 *
 * @Condition(
 *   id = "learning_learning_condition",
 *   label = @Translation("Learning Condition"),
 *   context_definitions = {
 *     "node" = @ContextDefinition(
 *       "entity:node",
 *        label = @Translation("Node")
 *      )
 *   }
 * )
 *
 * @DCG prior to Drupal 8.7 the 'context_definitions' key was called 'context'.
 */
class LearningCondition extends ConditionPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Creates a new LearningCondition instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DateFormatterInterface $date_formatter, TimeInterface $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->dateFormatter = $date_formatter;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('date.formatter'),
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['age' => NULL] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $form['age'] = [
      '#title' => $this->t('Node age, sec'),
      '#type' => 'number',
      '#min' => 0,
      '#default_value' => $this->configuration['age'],
    ];

    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['age'] = $form_state->getValue('age');
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return $this->t(
      'Node age: @age',
      ['@age' => $this->dateFormatter->formatInterval($this->configuration['age'])]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate() {
    if (!$this->configuration['age'] && !$this->isNegated()) {
      return TRUE;
    }
    $age = $this->time->getRequestTime() - $this->getContextValue('node')->getCreatedTime();
    return $age < $this->configuration['age'];
  }

}
