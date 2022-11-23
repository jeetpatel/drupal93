<?php

namespace Drupal\learning\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;

/**
 * Configure Learning settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  const SETTINGS = 'learning.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'learning_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    //kint($config->get());
    $form['example'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Configuration'),
      '#description' => $this->t('Enter Learning configuration'),
      '#description_display' => 'before',
      '#required' => TRUE,
      '#required_error' => $this->t('Please enter configuration value'),
      //'#input' => FALSE,
      '#default_value' => $config->get('example'),
    ];
    $form['range'] = [
      '#type' => 'range',
      '#title' => $this->t('Range'),
      '#default_value' => $config->get('range'),
    ];
    $form['address'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Address'),
      '#default_value' => $config->get('address'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config(static::SETTINGS)
      ->set('example', $form_state->getValue('example'))
      ->set('range', $form_state->getValue('range'))
      ->set('address', $form_state->getValue('address'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
