<?php

namespace Drupal\learning\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxy;

/**
 * Provides a Learning form.
 */
class FeedbackForm extends FormBase {

  /**
   * Current user object.
   *
   * @var \AccountProxy
   */
  protected $currentUser;
  
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    $instance = parent::create($container);
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }
  
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'learning_feedback';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $token = \Drupal::request()->query->get('token');
    $flag = \Drupal::getContainer()->get('csrf_token')->validate($token, "learning/feedback");
    if ($flag == FALSE) {
      \Drupal::messenger()->addWarning('Invalid CSRF token');
      //echo '<pre>'; print_r('Invalid CSRF token'); echo '</pre>';
    }
    $form['item'] = [
      '#type' => 'item',
      '#markup' => $this->t('Current User <strong>@name</strong>', ['@name' => $this->currentUser->getDisplayName()]),
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
