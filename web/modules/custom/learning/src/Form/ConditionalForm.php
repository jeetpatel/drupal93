<?php

namespace Drupal\learning\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Psr\Log\LoggerInterface;
use Drupal\Core\Url;

/**
 * Provides a Learning form.
 */
class ConditionalForm extends FormBase {

  /**
   * Logger factory interface.
   *
   * @var \LoggerInterface
   */
  protected $logger;

  /**
   * Database connection.
   *
   * @var \Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public function __construct(LoggerInterface $logger, Connection $connection) {
    $this->logger = $logger;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.factory')->get('conditional_form'),
      $container->get('database')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'learning_conditional';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $url = Url::fromRoute(
      'learning.feedback',
      [
        'token' => \Drupal::getContainer()->get('csrf_token')->get("learning/feedback"),
      ]);
    
//    $url = Url::fromRoute(
//    'learning.param',
//    ['node' => 2],
//    ['query' => 
//      [
//        'token' => \Drupal::getContainer()->get('csrf_token')->get("node/1/report")
//      ]
//    ]);
    
    $form['field_select_colour'] = [
      '#type' => 'select',
      '#title' => $this->t('Pick a colour'),
      '#required' => TRUE,
      '#options' => [
        'blue' => $this->t('Blue'),
        'white' => $this->t('White'),
        'black' => $this->t('Black'),
        'other' => $this->t('Other'),
        'custom' => $this->t('Custom colour'),
      ],
      '#states' => [
        'enabled' => [
          ':input[name="school_address[country_code]"]' => ['value' => ''],
        //'or',
        //':input[name="choice_select"]' => ['value' => 'no'],
        ],
      ],
    ];
    $form['choice_select'] = [
      '#type' => 'radios',
      '#title' => $this->t('Do you want to enter a custom colour?'),
      //'#default_value' => 'no',
      '#options' => [
        'yes' => $this->t('Yes'),
        'no' => $this->t('No'),
      ],
      '#attributes' => [
        'name' => 'field_choice_select',
      ],
      '#states' => [
        'visible' => [
          ':input[name="field_select_colour"]' => ['value' => 'other'],
        ],
        'enabled' => [
          ':input[name="field_custom_colour"]' => ['value' => ''],
        ],
      ],
    ];
    $form['custom_colour'] = [
      '#type' => 'textfield',
      '#size' => '60',
      '#placeholder' => 'Enter favourite colour',
      '#attributes' => [
        // Also add static name and id to the textbox.
        'id' => 'custom-colour',
        'name' => 'field_custom_colour',
      ],
      '#states' => [
        // Show this textfield if the radio 'other' or 'custom' is selected above.
        'visible' => [
          ':input[name="field_select_colour"]' => ['value' => 'other'],
          'and',
          ':input[name="field_choice_select"]' => ['value' => 'yes'],
        ],
      ]
    ];
    //kint(get_class_methods($url));
    $form['my_select_list'] = [
      '#type' => 'select',
      '#title' => 'User Type',
      '#description' => t('CSRF Link: <a href="@url">Click Here</a>', ['@url' => $url->toString()]),
      '#options' => [
        '' => t('Select'),
        'user' => t('User'),
        'group' => t('Group'),
      ],
    ];

    $form['auto_complete_field_0'] = [
      '#type' => 'textfield',
      '#title' => 'User Autocomplete',
      '#states' => [
        'visible' => [
          [':input[name="my_select_list"]' => ['value' => 'user']],
        ],
        'required' => [
          [':input[name="my_select_list"]' => ['value' => 'user']],
        ],
      ]
    ];

    $form['auto_complete_field_1'] = [
      '#type' => 'textfield',
      '#title' => 'Group Autocomplete',
      '#states' => [
        'visible' => [
          [':input[name="my_select_list"]' => ['value' => 'group']],
        ],
        'required' => [
          [':input[name="my_select_list"]' => ['value' => 'group']],
        ],
      ]
    ];

    

    // Uncheck anonymous field when the name field is filled.
    $form['anonymous'] = [
      '#type' => 'checkbox',
      '#title' => t('I prefer to remain anonymous'),
      '#default_value' => 1,
//      '#states' => [
//        'unchecked' => [
//          ':input[name="name"]' => ['filled' => TRUE),
//        ],
//      ],
    ];
    
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#states' => [
        'invisible' => [
          ':input[name="anonymous"]' => ['checked' => TRUE],
        ],
      ],
    ];    
//
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#states' => [
        'visible' => [
          [
            ':input[name="name"]' => ['filled' => TRUE],
            //':select[name="method"]' => ['value' => 'email'],
          ],
          'Xor',
          [
            ':input[name="anonymous"]' => ['checked' => TRUE],
            //':select[name="method"]' => ['value' => 'email'],
          ],
        ],
      ],
    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    foreach ($form_state->getValues() as $key => $value) {
      if (!empty($value)) {
        //\Drupal::logger('conditional_form')->warning("Key:{$key}, Value: {$value}");
        $this->logger->debug("@key, Value: @value", ['@key' => $key, '@value' => $value]);
        //$this->logger->info("");
      }
    }
  }

}
