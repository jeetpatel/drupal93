<?php

namespace Drupal\custom_module\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the cm_newsletter_queue queueworker.
 *
 * @QueueWorker (
 *   id = "cm_newsletter_queue",
 *   title = @Translation("Send newsletter to user"),
 *   cron = {"time" = 30}
 * )
 */
class NewsletterQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface {

    /**
   * The mail manager.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * RenewSubscriptionsQueue constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   A date time instance.
   * @param \Drupal\Core\Session\AccountSwitcherInterface $switcher
   *   An account switcher instance.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\ti_trigger_email\Services\TriggerEmailSender $trigger_email_sender
   *   The trigger email sender.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MailManagerInterface $mail_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->mailManager = $mail_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.mail')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    try {
      $params = [
        'from' => 'jeet.lal1@tothenew.com',
        'subject' => 'Newsletter email',
        'body' => 'Hello' . $data['name'] . ', This is newsletter body content',
      ];
      \Drupal::logger('processItem')->info('Mail Body::' . $params['body']);
      $result = $this->mailManager->mail('custom_module', 'custom_module', $data['email_id'], 'en', $params, NULL, TRUE);
      if (!$result['result']) {
        \Drupal::logger('processItem')->error('Unable to send email::' . $data['email_id']);
        return FALSE;
      }
      else {
        \Drupal::logger('processItem')->info('Mail send to email::' . $data['email_id']);
      }
    }
    catch (\Exception $ex) {
      \Drupal::logger('processItem')->info('Exception Message. ' . $ex->getMessage() . '.Email id ' . $data['email_id']);
    }
  }

}
