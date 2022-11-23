<?php

namespace Drupal\learning\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;

/**
 *
 */
class AccessCheckController extends ControllerBase {

  /**
   *
   */
  public function content() {
    // $account = \Drupal::currentUser();
    //    if (AccessResult::allowed($account->isAnonymous()) == TRUE) {
    //      throw new NotFoundHttpException();
    //    }
    return [
      '#markup' => 'Allowed',
    ];
  }

  /**
   *
   */
  public function access(AccountInterface $account) {
    // Return AccessResult::allowedIf($account->isAuthenticated());
      return AccessResult::allowedIf($account->isAuthenticated());
  }

  /**
   *
   */
  public function getProtectedContent(EntityInterface $node) {
    return [
      '#markup' => 'Node Title ' . $node->getTitle(),
    ];
  }

}
