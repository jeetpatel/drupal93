<?php

namespace Drupal\learning\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a Learning form.
 */
class ParamForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'learning_param';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $node = NULL) {
    $update_count = 1;
    $message = \Drupal::translation()->formatPlural($update_count, '@type Content Type has been updated.', '@type Contents have been updated.', [
      '@type' => 'Article',
    ]);
    $this->messenger()->addMessage($message);
    $t = check_markup('<strong>Hello</strong>');
    $this->messenger()->addMessage($t);
    
    $url = Url::fromRoute('learning.protected_content', 
      ['node' => 4], 
      [
      'query' => [
        'token' => \Drupal::getContainer()->get('csrf_token')->get('learning/protected/' . 4)
      ]
    ]);
    
    $form['node_url'] = [
      '#type' => 'link',
      '#title' => $this->t('Protected Node'),
      '#url' => $url,
    ];
    
    //check_markup($text, $format_id, $langcode, $form);
    $form['markup'] = [
      '#type' => 'processed_text',
      '#title' => $this->t('Message'),
      '#text' => $t,
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
