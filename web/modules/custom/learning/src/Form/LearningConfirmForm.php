<?php

namespace Drupal\learning\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a confirmation form before clearing out the examples.
 */
class LearningConfirmForm extends ConfirmFormBase {

  private $id;
  
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'learning_learning_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete id :id?', [':id' => $this->id]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('learning.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // @DCG Place your code here.
    $this->messenger()->addStatus($this->t('ID :id deleted!', [':id' => $this->id]));
    $form_state->setRedirectUrl(new Url('learning.learning_confirm', ['id' => 3]));
  }

}
