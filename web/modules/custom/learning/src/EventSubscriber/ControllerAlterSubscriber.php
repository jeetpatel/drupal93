<?php

namespace Drupal\learning\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ControllerAlterSubscriber.
 */
class ControllerAlterSubscriber implements EventSubscriberInterface {

  use \Drupal\Core\StringTranslation\StringTranslationTrait;

  /**
   * Alters the controller output.
   */
  public function onView(GetResponseForControllerResultEvent $event) {
    $request = $event->getRequest();
    $route = $request->attributes->get('_route');
    if ($route == 'learning.example.process_state_list') {
      $build = $event->getControllerResult();
      $message = $this->t('This is alter message');
      if (is_array($build)) {
        $data = $build['#state_data'];
        $data[] = 'HP';
        $build['#state_data'] = $data;
        $build['#custom_message'] = $message;
        $event->setControllerResult($build);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Priority > 0 so that it runs before the controller output
    // is rendered by \Drupal\Core\EventSubscriber\MainContentViewSubscriber.
    $events[KernelEvents::VIEW][] = ['onView', 50];
    return $events;
  }

}