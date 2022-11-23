<?php

namespace Drupal\learning\Controller;

use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\TypedData\ComplexDataInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\learning\Entity\LearningEntity;
use Drupal\user\Entity\User;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\learning\Exception\LearningException;
use Drupal\Core\Logger\LoggerChannelTrait;

/**
 * Controller class file.
 *
 * Class TestController.
 */
class TestController extends ControllerBase {

  use LoggerChannelTrait;

  /**
   * Entity type manager object.
   *
   * @var \EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Custom variable.
   *
   * @var string
   */
  public $value;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    return $instance;
  }

  /**
   * Fetch value of array by key.
   *
   * @param array $array
   *   Array value.
   * @param string $key
   *   Array key to get value.
   *
   * @return string|array|object
   *   Return string if success else return array.
   */
  public function calculateData(array $array, $key) {
    // Option 1.
    $value = $array[$key];
    return $value;
    // Option 2.
    $value = !empty($array[$key]) ? $array[$key] : NULL;
    return $value;
    // Option 3.
    return !empty($array[$key]) ? $array[$key] : NULL;
  }

  public function doStuff() {
    $logger = $this->getLogger('controller');
    $logger->error("Custom logger channel");
  }

  /**
   * Test.
   *
   * @return string
   *   Return Hello string
   */
  public function test() {
    Cache::invalidateTags(['config:config_block_jeet']);
    $redirect = \Drupal::service('redirect.checker');
    kint($redirect->canRedirect(\Drupal::request(), 'learning.example.process_state_list')); die;
    //$this->doStuff();
    \Drupal::service('config.factory')->getEditable('learning.settings')->delete();
    $learning = new LearningException();
    try {
      $learning->checkEmpty(NULL);
    }
    catch (\LearningException $e) {
      print_r($e->getMessage());
    }
    catch (\Exception $e) {
      print_r('Default::' . $e->getMessage());
    }
    die;
    echo number_format(10500.5, 2, '.', '');die;
    $data = ['school_data' => ['Not empty value'], 'school_id' => 'B Data'];
    $new = [
      $data['school_data'] ?? ['Empty value'],
      $data['school_id'] ?? [],
    ];
    echo '<pre>';
    print_r($new);
    die;
    // $this->serializer = \Drupal::service('serializer');
    // $entity = $this->entityTypeManager->getStorage('node');
    // $entity = $entity->load(1);
    // $data = $entity->toArray();
    // $output = $this->serializer->serialize($entity, 'xml');
    // print_r($output);
    // die;
    \kint::dump($output);die;
    // Create a new field definition.
    $benchmarkField = BaseFieldDefinition::create('decimal')
            ->setLabel(t('Scheme (`)'))
            ->setDescription(t('Scheme (`).'))
            ->setRevisionable(TRUE)
            ->setSettings([
                'decimal_separator' => '.',
                'precision' => 10,
                'scale' => 4,
            ])->setDefaultValue('')
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'number_decimal',
                'weight' => -4,
            ])
            ->setDisplayOptions('form', [
                'type' => 'numeric',
                'weight' => -4,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE)
            ->setRequired(TRUE);
    // $new_status_field = BaseFieldDefinition::create('string')
    //   ->setLabel(t('Status field'))
    //   ->setDescription(t('The status - either no, yes or skip.'))
    //   ->setDefaultValue('no')
    //   ->setRevisionable(FALSE)
    //   ->setTranslatable(FALSE);
    // Install the new definition.
    $bundle_of = 'learning_entity';
    $definition_manager = \Drupal::entityDefinitionUpdateManager();
    $definition_manager->installFieldStorageDefinition('benchmark', 'learning_entity', 'learning_entity', $benchmarkField);
    //$definition_manager->installFieldStorageDefinition('benchmark', $bundle_of, $bundle_of, $benchmarkField);

    return new JsonResponse([
        'status' => 'Completed',
    ]);

    $database = \Drupal::database();
    $database1 = \Drupal::database();
    \kint::dump($database, $database1);
    die;

    // Get the site name without overrides.
    $site_name = \Drupal::config('system.site')->getOriginal('name', FALSE);
    echo '<pre>';
    print_r($site_name);
    echo '</pre>';
    // Note that mutable config is always override free.
    $site_name = \Drupal::configFactory()->getEditable('system.site')->getOriginal('name', FALSE);
    echo '<pre>';
    print_r($site_name);
    echo '</pre>';
    die;

    // $layoutManager = \Drupal::service('plugin.manager.core.layout');
    //      $layoutDefinitions = $layoutManager->getDefinitions();
    //      foreach ($layoutDefinitions as $layout) {
    //        $list[] = $layout->getLabel();
    //      }
    $node = Node::load(1);
    // Clear cache of EmployeeBlock cache.
    Cache::invalidateTags(['employee_data:list']);
    $node->save();
    return [
        '#markup' => 'Cache cleared',
    ];
    // $cacheTgas = $node->getCacheTags();
    // kint($cacheTgas); die;
    // \Drupal\Core\Url::
    $link = Url::fromUri('internal:/learning/learning-confirm', ['external' => TRUE]);
    // kint($link->toString());
    // return ['#markup' => $this->t('<a href="@url">Link</a>', ['@url' => $link->toString()])];
    // echo '<pre>'; print_r($link); echo '</pre>';
    // echo '<pre>'; print_r($list); echo '</pre>';
    // die;
    //    $connection = \Drupal\Core\Database\Database::getConnection('replica');
    //    $result = $connection->query('SELECT * FROM employee');
    //    while ($row = $result->fetchAssoc()) {
    //      kint($row);
    //    }
    //    die;
    $connection = \Drupal::service('database.replica');
    $result = $connection->query('SELECT * FROM employee', [], [
        'fetch' => \PDO::FETCH_ASSOC,
    ]);
    while ($row = $result->fetchAssoc()) {
      // Echo '<pre>';
      // print_r($row);
      // echo '</pre>';.
    }
    // die;.
    $flag = $this->entityTypeManager->getAccessControlHandler('node')->createAccess('article');
    $entity = $this->entityTypeManager->getStorage('learning_entity')->load(1);
    $entity = $this->entityTypeManager->getStorage('node')->load(1);
    $flag = $entity->access('view');
    $url = $entity->toUrl('edit-form')->toString();
    // Echo '<pre>'; print_r($entity->bundle()); echo '</pre>';
    // $view_builder = Drupal::entityTypeManager()->getViewBuilder('node');
    $entityFlag = $entity instanceof ComplexDataInterface;
    // $entityFlag = $entity->get('field_image') instanceof ListInterface;
    // $entityFlag = $entity->get('field_image')->offsetGet(0) instanceof ComplexDataInterface;
    // $entityFlag = $entity->get('field_image')->offsetGet(0)->get('alt') instanceof TypedDataInterface;
    // $entityFlag = is_string($entity->get('field_image')->offsetGet(0)->get('alt')->getValue());
    // dump($entityFlag);
    // $property_definitions = $entity->field_image->getFieldDefinition()->getFieldStorageDefinition()->getPropertyDefinition('alt');
    // kint($entity);
    //    if (!$entity->hasTranslation('fr')) {
    //      $translation = $entity->addTranslation('fr', array('title' => 'Hinti Article 1'))->save();
    //    }
    $translation = $entity->getTranslation('en');
    // $value = $translation->title->value;
    // $handler = \Drupal::entityTypeManager()->getHandler('learning_entity', 'storage');
    // $node = $handler->load(1);
    // $node->setName('New Learning');
    // $node->save();
    // $handler = \Drupal::entityTypeManager()->getHandler('learning_entity', 'view_builder');
    // $handler->view($node, 'default');
    // $build = \Drupal::entityTypeManager()->getViewBuilder('learning_entity')->view($node, 'default');
    // $tags = get_class_methods($node->field_tags->entity);
    // $tags = $node->field_tags->entity->getEntityTypeId();
    // dump($tags);
    // $build = $view_builder->view($node, 'teaser');
    // kint($entity->getFieldDefinitions());
    return [
        '#type' => 'markup',
        '#markup' => $this->t(
                'Implement method: test. Permission:: @flag, URL: @url',
                [
                    '@flag' => $flag,
                    '@url' => $url,
                ]
        ),
    ];
  }

  /**
   *
   */
  public function paramTest($name) {
    return ['#markup' => $this->t('Hello @name', ['@name' => $name])];
  }

  /**
   *
   */
  public function dynamicTest($name) {
    return ['#markup' => $this->t('Dynamic value: @name', ['@name' => $name])];
  }

}
