<?php

namespace Drupal\custom_module\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Project Configuration Entity form.
 *
 * @property \Drupal\custom_module\ProjectConfigEntityInterface $entity
 */
class ProjectConfigEntityForm extends EntityForm {

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
      '#description' => $this->t('Label for the project configuration entity.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\custom_module\Entity\ProjectConfigEntity::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['description'] = [
      '#attributes' => ['style' => 'width:510px;'],
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $this->entity->get('description'),
      '#description' => $this->t('Description of the project configuration entity.'),
    ];

    $form['configuration'] = [
      '#type' => 'textarea',
      '#attributes' => ['style' => 'width:510px;'],
      '#title' => $this->t('Configuration'),
      '#default_value' => $this->entity->getConfiguration(),
      '#description' => $this->t('Configuration in text format.'),
    ];

    $form['json_configuration'] = [
      '#attributes' => ['style' => 'width:510px;'],
      '#type' => 'textarea',
      '#title' => $this->t('JSON Configuration'),
      '#default_value' => $this->entity->getJsonConfiguration(),
      '#description' => $this->t('Configuration in the JSON format.'),
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
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new project configuration entity %label.', $message_args)
      : $this->t('Updated project configuration entity %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
