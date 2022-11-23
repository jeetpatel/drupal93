<?php

namespace Drupal\dcs\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dcs\Services\DcsService;
use Drupal\Core\Url;

/**
 * Provides a form to create Rules.
 */
class RulesCreateForm extends FormBase {

  /**
   * Custom Dcs Service.
   *
   * @var \DcsService
   */
  private $dcsService;

  /**
   * Initialize class variables.
   *
   * @param \DcsService $dcsService
   *   Custom dcs.service.
   */
  public function __construct(DcsService $dcsService) {
    $this->dcsService = $dcsService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dcs.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dcs_rules';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rules Title'),
      '#required' => TRUE,
    ];

    $form['body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Rules Description'),
      '#required' => TRUE,
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rules Reference URL'),
      '#required' => FALSE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    $form['content'] = [
      '#markup' => $this->t('<a href=":link">List Rules</a>', [
        ':link' => Url::fromRoute('dcs.rules.list')->toString(),
      ]),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('title')) < 5) {
      $form_state->setErrorByName('name', $this->t('Rules Title should be at least 5 characters.'));
    }
    if (mb_strlen($form_state->getValue('body')) < 20) {
      $form_state->setErrorByName('name', $this->t('Rules Description should be at least 20 characters.'));
    }
    $url = $form_state->getValue('url');
    // Validate Reference URL value.
    if (!empty($url) && strstr($url, 'http') == FALSE) {
      $form_state->setErrorByName('name',
        $this->t('Rules Reference URL @url is not valid.', [
          '@url' => $url,
        ])
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $data = [
      'uid' => $this->currentUser()->id(),
      'title' => $form_state->getValue('title'),
      'body' => $form_state->getValue('body'),
      'url' => $form_state->getValue('url'),
      'status' => TRUE,
      'created' => time(),
    ];
    // Create DCS Rules.
    $this->dcsService->createRules($data);
    $this->messenger()->addStatus($this->t('Coding standards rules added successfully.'));
    $form_state->setRedirect('dcs.rules.list');
  }

}
