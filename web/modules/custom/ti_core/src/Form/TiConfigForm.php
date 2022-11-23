<?php

namespace Drupal\ti_core\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Teachit Configuration Entity form.
 *
 * @property \Drupal\ti_core\TiConfigInterface $entity
 */
class TiConfigForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the teachit configuration entity.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\ti_core\Entity\TiConfig::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];
    $form['configuration'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Configuration'),
      '#default_value' => $this->entity->get('configuration'),
      '#description' => $this->t('Configuration (Plain Format) of the teachit configuration entity.'),
    ];
    $form['json_configuration'] = [
      '#type' => 'textarea',
      '#title' => $this->t('JSON Configuration'),
      '#default_value' => $this->entity->get('json_configuration'),
      '#description' => $this->t('JSON Format Configuration of the teachit configuration entity.'),
    ];
    $form['optional_value1'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Optional Value 1'),
      '#default_value' => $this->entity->get('optional_value1'),
      '#description' => $this->t('Optional Value 1 of the teachit configuration entity.'),
    ];
    $form['optional_value2'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Optional Value 2'),
      '#default_value' => $this->entity->get('optional_value2'),
      '#description' => $this->t('Optional Value 2 of the teachit configuration entity.'),
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $this->entity->get('description'),
      '#description' => $this->t('Description of the teachit configuration entity.'),
      '#required' => TRUE,
    ];
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $this->entity->status(),
    ];
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    // Validate JSON configuration value.
    json_decode($form_state->getValue('json_configuration'), 0);
    if (json_last_error() !== JSON_ERROR_NONE) {
      $form_state->setErrorByName('json_configuration', 'Invalid JSON value');
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new teachit configuration entity %label.', $message_args)
      : $this->t('Updated teachit configuration entity %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
