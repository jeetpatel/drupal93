<?php

namespace Drupal\custom_module\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Defines 'cm_send_email_queue' queue worker.
 *
 * @QueueWorker(
 *   id = "cm_send_email_queue",
 *   title = @Translation("Send Email Queue"),
 *   cron = {"time" = 60}
 * )
 */
class SendEmailQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    // @todo Process data here.
  }

}
