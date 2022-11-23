<?php

namespace Drupal\dcs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dcs\Services\DcsService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * Returns responses for Drupal coding standards routes.
 */
class DcsController extends ControllerBase {

  /**
   * Custom Dcs Service.
   *
   * @var \DcsService
   */
  private $dcsService;

  /**
   * Initialize class variables.
   *
   * @param \DcsService $dcsService
   *   Custom dcs.service.
   */
  public function __construct(DcsService $dcsService) {
    $this->dcsService = $dcsService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dcs.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#makup' => 'It works',
    ];
  }

  /**
   * Get status label.
   *
   * @param int $status
   *   Status value.
   *
   * @return string
   *   Return status label.
   */
  private function getStatus($status) {
    $label = '';
    switch ($status) {
      case 0:
        $label = 'Un-Publish';
        break;

      case 1:
        $label = 'Publish';
        break;

      default:
        $label = 'Archive';
    }
    return $label;
  }

  /**
   * Builds the response.
   */
  public function listRules() {
    $this->dcsService->addLog('info', 'Listing rules data.');
    $condition = '';
    $something = $rows = $build = [];
    $something['with']['something']['else']['in']['here'] = 1;
    $something['with']['something']['else']['in']['new'] = 1;
    if ($something['with']['something']['else']['in']['here'] == $something['with']['something']['else']['in']['new']) {
      $condition = 'Long';
    }
    // Fetch rules.
    $results = $this->dcsService->getRulesList();
    // Prepare table rows.
    foreach ($results as $index => $row) {
      $rows[] = [
        ($index + 1),
        $row->title,
        $row->body,
        $this->getStatus($row->status),
        date('Y-m-d H:i:s', $row->created),
      ];
    }
    // Rnder data in table format.
    $build['table'] = [
      '#theme' => 'table',
      '#headers' => [
        '#',
        'Title',
        'Description',
        'Status',
        'Created At',
      ],
      '#rows' => $rows,
    ];
    $build['content'] = [
      '#markup' => $this->t('<a href=":link">Add Rules</a>', [
        ':link' => Url::fromRoute('dcs.rules.create')->toString(),
      ]),
    ];
    $build['content'][] = [
      '#markup' => $this->t('Condition @value', [
        '@value' => $condition,
      ]),
    ];
    return $build;
  }

}
