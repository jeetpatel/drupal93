<?php

namespace Drupal\drupal_bootcamp\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the employee entity edit forms.
 */
class EmployeeForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New employee %label has been created.', $message_arguments));
      $this->logger('drupal_bootcamp')->notice('Created new employee %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The employee %label has been updated.', $message_arguments));
      $this->logger('drupal_bootcamp')->notice('Updated new employee %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.employee.canonical', ['employee' => $entity->id()]);
  }

}
