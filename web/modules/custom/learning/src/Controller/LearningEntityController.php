<?php

namespace Drupal\learning\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\learning\Entity\LearningEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Link;

/**
 * Class LearningEntityController.
 *
 *  Returns responses for Learning entity routes.
 */
class LearningEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Learning entity revision.
   *
   * @param int $learning_entity_revision
   *   The Learning entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($learning_entity_revision) {
    $learning_entity = $this->entityTypeManager()->getStorage('learning_entity')
      ->loadRevision($learning_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('learning_entity');

    return $view_builder->view($learning_entity);
  }

  /**
   * Page title callback for a Learning entity revision.
   *
   * @param int $learning_entity_revision
   *   The Learning entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($learning_entity_revision) {
    $learning_entity = $this->entityTypeManager()->getStorage('learning_entity')
      ->loadRevision($learning_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $learning_entity->label(),
      '%date' => $this->dateFormatter->format($learning_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Learning entity.
   *
   * @param \Drupal\learning\Entity\LearningEntityInterface $learning_entity
   *   A Learning entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(LearningEntityInterface $learning_entity) {
    $account = $this->currentUser();
    $learning_entity_storage = $this->entityTypeManager()->getStorage('learning_entity');

    $langcode = $learning_entity->language()->getId();
    $langname = $learning_entity->language()->getName();
    $languages = $learning_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $learning_entity->label()]) : $this->t('Revisions for %title', ['%title' => $learning_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all learning entity revisions") || $account->hasPermission('administer learning entity entities')));
    $delete_permission = (($account->hasPermission("delete all learning entity revisions") || $account->hasPermission('administer learning entity entities')));

    $rows = [];

    $vids = $learning_entity_storage->revisionIds($learning_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\learning\LearningEntityInterface $revision */
      $revision = $learning_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $learning_entity->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.learning_entity.revision', [
            'learning_entity' => $learning_entity->id(),
            'learning_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $learning_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.learning_entity.translation_revert', [
                'learning_entity' => $learning_entity->id(),
                'learning_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.learning_entity.revision_revert', [
                'learning_entity' => $learning_entity->id(),
                'learning_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.learning_entity.revision_delete', [
                'learning_entity' => $learning_entity->id(),
                'learning_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['learning_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
