<?php

namespace Drupal\learning\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Learning form.
 */
class ReceipeForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'learning_receipe';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#description' => $this->t('Message description'),
      '#required' => TRUE,
    ];
    
    $form['category'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#required' => TRUE,
      '#options' => [
        1 => 'Category 1',
        2 => 'Category 1',
        3 => 'Category 1',
      ]
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];
    
    $headers = ['S.NO', 'Node ID', 'Title'];
    $rows[] = [1, 11, 'my title'];
    $rows[] = [2, 12, 'my title'];
    $rows[] = [3, 13, 'my title'];

    $form['table'] = [
      '#theme' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('message')) < 10) {
      $form_state->setErrorByName('name', $this->t('Message should be at least 10 characters.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }

}
