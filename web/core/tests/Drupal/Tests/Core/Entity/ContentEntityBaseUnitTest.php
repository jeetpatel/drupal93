<?php

namespace Drupal\Tests\Core\Entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\TypedData\TypedDataManagerInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\Language\Language;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @coversDefaultClass \Drupal\Core\Entity\ContentEntityBase
 * @group Entity
 * @group Access
 */
class ContentEntityBaseUnitTest extends UnitTestCase
{
  /**
   * The bundle of the entity under test.
   *
   * @var string
   */
    protected $bundle;

    /**
     * The entity under test.
     *
     * @var \Drupal\Core\Entity\ContentEntityBase|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $entity;

    /**
     * An entity with no defined language to test.
     *
     * @var \Drupal\Core\Entity\ContentEntityBase|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $entityUnd;

    /**
     * The entity type used for testing.
     *
     * @var \Drupal\Core\Entity\EntityTypeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $entityType;

    /**
     * The entity field manager used for testing.
     *
     * @var \Drupal\Core\Entity\EntityFieldManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $entityFieldManager;

    /**
     * The entity type bundle manager used for testing.
     *
     * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $entityTypeBundleInfo;

    /**
     * The entity type manager used for testing.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $entityTypeManager;

    /**
     * The type ID of the entity under test.
     *
     * @var string
     */
    protected $entityTypeId;

    /**
     * The typed data manager used for testing.
     *
     * @var \Drupal\Core\TypedData\TypedDataManager|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $typedDataManager;

    /**
     * The field type manager used for testing.
     *
     * @var \Drupal\Core\Field\FieldTypePluginManager|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $fieldTypePluginManager;

    /**
     * The language manager.
     *
     * @var \Drupal\Core\Language\LanguageManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $languageManager;

    /**
     * The UUID generator used for testing.
     *
     * @var \Drupal\Component\Uuid\UuidInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $uuid;

    /**
     * The entity ID.
     *
     * @var int
     */
    protected $id;

    /**
     * Field definitions.
     *
     * @var \Drupal\Core\Field\BaseFieldDefinition[]
     */
    protected $fieldDefinitions;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->id = 1;
        $values = [
      'id' => $this->id,
      'uuid' => '3bb9ee60-bea5-4622-b89b-a63319d10b3a',
      'defaultLangcode' => [LanguageInterface::LANGCODE_DEFAULT => 'en'],
    ];
        $this->entityTypeId = $this->randomMachineName();
        $this->bundle = $this->randomMachineName();

        $this->entityType = $this->createMock('\Drupal\Core\Entity\EntityTypeInterface');
        $this->entityType->expects($this->any())
      ->method('getKeys')
      ->will($this->returnValue([
        'id' => 'id',
        'uuid' => 'uuid',
    ]));

        $this->entityTypeManager = $this->createMock(EntityTypeManagerInterface::class);
        $this->entityTypeManager->expects($this->any())
      ->method('getDefinition')
      ->with($this->entityTypeId)
      ->will($this->returnValue($this->entityType));

        $this->entityFieldManager = $this->createMock(EntityFieldManagerInterface::class);

        $this->entityTypeBundleInfo = $this->createMock(EntityTypeBundleInfoInterface::class);

        $this->uuid = $this->createMock('\Drupal\Component\Uuid\UuidInterface');

        $this->typedDataManager = $this->createMock(TypedDataManagerInterface::class);
        $this->typedDataManager->expects($this->any())
      ->method('getDefinition')
      ->with('entity')
      ->will($this->returnValue(['class' => '\Drupal\Core\Entity\Plugin\DataType\EntityAdapter']));

        $english = new Language(['id' => 'en']);
        $not_specified = new Language(['id' => LanguageInterface::LANGCODE_NOT_SPECIFIED, 'locked' => true]);
        $this->languageManager = $this->createMock('\Drupal\Core\Language\LanguageManagerInterface');
        $this->languageManager->expects($this->any())
      ->method('getLanguages')
      ->will($this->returnValue(['en' => $english, LanguageInterface::LANGCODE_NOT_SPECIFIED => $not_specified]));
        $this->languageManager->expects($this->any())
      ->method('getLanguage')
      ->with('en')
      ->will($this->returnValue($english));
        $this->languageManager->expects($this->any())
      ->method('getLanguage')
      ->with(LanguageInterface::LANGCODE_NOT_SPECIFIED)
      ->will($this->returnValue($not_specified));

        $this->fieldTypePluginManager = $this->getMockBuilder('\Drupal\Core\Field\FieldTypePluginManager')
      ->disableOriginalConstructor()
      ->getMock();
        $this->fieldTypePluginManager->expects($this->any())
      ->method('getDefaultStorageSettings')
      ->will($this->returnValue([]));
        $this->fieldTypePluginManager->expects($this->any())
      ->method('getDefaultFieldSettings')
      ->will($this->returnValue([]));
        $this->fieldTypePluginManager->expects($this->any())
      ->method('createFieldItemList')
      ->will($this->returnValue($this->createMock('Drupal\Core\Field\FieldItemListInterface')));

        $container = new ContainerBuilder();
        $container->set('entity_field.manager', $this->entityFieldManager);
        $container->set('entity_type.bundle.info', $this->entityTypeBundleInfo);
        $container->set('entity_type.manager', $this->entityTypeManager);
        $container->set('uuid', $this->uuid);
        $container->set('typed_data_manager', $this->typedDataManager);
        $container->set('language_manager', $this->languageManager);
        $container->set('plugin.manager.field.field_type', $this->fieldTypePluginManager);
        \Drupal::setContainer($container);

        $this->fieldDefinitions = [
      'id' => BaseFieldDefinition::create('integer'),
      'revision_id' => BaseFieldDefinition::create('integer'),
    ];

        $this->entityFieldManager->expects($this->any())
      ->method('getFieldDefinitions')
      ->with($this->entityTypeId, $this->bundle)
      ->will($this->returnValue($this->fieldDefinitions));

        $this->entity = $this->getMockForAbstractClass(ContentEntityBase::class, [$values, $this->entityTypeId, $this->bundle], '', true, true, true, ['isNew']);
        $values['defaultLangcode'] = [LanguageInterface::LANGCODE_DEFAULT => LanguageInterface::LANGCODE_NOT_SPECIFIED];
        $this->entityUnd = $this->getMockForAbstractClass(ContentEntityBase::class, [$values, $this->entityTypeId, $this->bundle]);
    }

    /**
     * @covers ::isNewRevision
     * @covers ::setNewRevision
     */
    public function testIsNewRevision()
    {
        // Set up the entity type so that on the first call there is no revision key
        // and on the second call there is one.
        $this->entityType->expects($this->exactly(4))
      ->method('hasKey')
      ->with('revision')
      ->willReturnOnConsecutiveCalls(false, true, true, true);
        $this->entityType->expects($this->exactly(2))
      ->method('getKey')
      ->with('revision')
      ->will($this->returnValue('revision_id'));

        $field_item_list = $this->getMockBuilder('\Drupal\Core\Field\FieldItemList')
      ->disableOriginalConstructor()
      ->getMock();
        $field_item = $this->getMockBuilder('\Drupal\Core\Field\FieldItemBase')
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();

        $this->fieldTypePluginManager->expects($this->any())
      ->method('createFieldItemList')
      ->with($this->entity, 'revision_id', null)
      ->will($this->returnValue($field_item_list));

        $this->fieldDefinitions['revision_id']->getItemDefinition()->setClass(get_class($field_item));

        $this->assertFalse($this->entity->isNewRevision());
        $this->assertTrue($this->entity->isNewRevision());
        $this->entity->setNewRevision(true);
        $this->assertTrue($this->entity->isNewRevision());
    }

    /**
     * @covers ::setNewRevision
     */
    public function testSetNewRevisionException()
    {
        $this->entityType->expects($this->once())
      ->method('hasKey')
      ->with('revision')
      ->will($this->returnValue(false));
        $this->expectException('LogicException');
        $this->expectExceptionMessage('Entity type ' . $this->entityTypeId . ' does not support revisions.');
        $this->entity->setNewRevision();
    }

    /**
     * @covers ::isDefaultRevision
     */
    public function testIsDefaultRevision()
    {
        // The default value is TRUE.
        $this->assertTrue($this->entity->isDefaultRevision());
        // Change the default revision, verify that the old value is returned.
        $this->assertTrue($this->entity->isDefaultRevision(false));
        // The last call changed the return value for this call.
        $this->assertFalse($this->entity->isDefaultRevision());
        // The revision for a new entity should always be the default revision.
        $this->entity->expects($this->any())
      ->method('isNew')
      ->will($this->returnValue(true));
        $this->entity->isDefaultRevision(false);
        $this->assertTrue($this->entity->isDefaultRevision());
    }

    /**
     * @covers ::getRevisionId
     */
    public function testGetRevisionId()
    {
        // The default getRevisionId() implementation returns NULL.
        $this->assertNull($this->entity->getRevisionId());
    }

    /**
     * @covers ::isTranslatable
     */
    public function testIsTranslatable()
    {
        $this->entityTypeBundleInfo->expects($this->any())
      ->method('getBundleInfo')
      ->with($this->entityTypeId)
      ->will($this->returnValue([
        $this->bundle => [
          'translatable' => true,
        ],
      ]));
        $this->languageManager->expects($this->any())
      ->method('isMultilingual')
      ->will($this->returnValue(true));
        $this->assertSame('en', $this->entity->language()->getId());
        $this->assertFalse($this->entity->language()->isLocked());
        $this->assertTrue($this->entity->isTranslatable());

        $this->assertSame(LanguageInterface::LANGCODE_NOT_SPECIFIED, $this->entityUnd->language()->getId());
        $this->assertTrue($this->entityUnd->language()->isLocked());
        $this->assertFalse($this->entityUnd->isTranslatable());
    }

    /**
     * @covers ::isTranslatable
     */
    public function testIsTranslatableForMonolingual()
    {
        $this->languageManager->expects($this->any())
      ->method('isMultilingual')
      ->will($this->returnValue(false));
        $this->assertFalse($this->entity->isTranslatable());
    }

    /**
     * @covers ::preSaveRevision
     */
    public function testPreSaveRevision()
    {
        // This method is internal, so check for errors on calling it only.
        $storage = $this->createMock('\Drupal\Core\Entity\EntityStorageInterface');
        $record = new \stdClass();
        // Our mocked entity->preSaveRevision() returns NULL, so assert that.
        $this->assertNull($this->entity->preSaveRevision($storage, $record));
    }

    /**
     * @covers ::validate
     */
    public function testValidate()
    {
        $validator = $this->createMock(ValidatorInterface::class);
        /** @var \Symfony\Component\Validator\ConstraintViolationList|\PHPUnit\Framework\MockObject\MockObject $empty_violation_list */
        $empty_violation_list = $this->getMockBuilder('\Symfony\Component\Validator\ConstraintViolationList')
      ->onlyMethods([])
      ->getMock();
        $non_empty_violation_list = clone $empty_violation_list;
        $violation = $this->createMock('\Symfony\Component\Validator\ConstraintViolationInterface');
        $non_empty_violation_list->add($violation);
        $validator->expects($this->exactly(2))
      ->method('validate')
      ->with($this->entity->getTypedData())
      ->willReturnOnConsecutiveCalls($empty_violation_list, $non_empty_violation_list);
        $this->typedDataManager->expects($this->exactly(2))
      ->method('getValidator')
      ->will($this->returnValue($validator));
        $this->assertCount(0, $this->entity->validate());
        $this->assertCount(1, $this->entity->validate());
    }

    /**
     * Tests required validation.
     *
     * @covers ::validate
     * @covers ::isValidationRequired
     * @covers ::setValidationRequired
     * @covers ::save
     * @covers ::preSave
     */
    public function testRequiredValidation()
    {
        $validator = $this->createMock(ValidatorInterface::class);
        /** @var \Symfony\Component\Validator\ConstraintViolationList|\PHPUnit\Framework\MockObject\MockObject $empty_violation_list */
        $empty_violation_list = $this->getMockBuilder('\Symfony\Component\Validator\ConstraintViolationList')
      ->onlyMethods([])
      ->getMock();
        $validator->expects($this->once())
      ->method('validate')
      ->with($this->entity->getTypedData())
      ->will($this->returnValue($empty_violation_list));
        $this->typedDataManager->expects($this->any())
      ->method('getValidator')
      ->will($this->returnValue($validator));

        /** @var \Drupal\Core\Entity\EntityStorageInterface|\PHPUnit\Framework\MockObject\MockObject $storage */
        $storage = $this->createMock('\Drupal\Core\Entity\EntityStorageInterface');
        $storage->expects($this->any())
      ->method('save')
      ->willReturnCallback(function (ContentEntityInterface $entity) use ($storage) {
          $entity->preSave($storage);
      });

        $this->entityTypeManager->expects($this->any())
      ->method('getStorage')
      ->with($this->entityTypeId)
      ->will($this->returnValue($storage));

        // Check that entities can be saved normally when validation is not
        // required.
        $this->assertFalse($this->entity->isValidationRequired());
        $this->entity->save();

        // Make validation required and check that if the entity is validated, it
        // can be saved normally.
        $this->entity->setValidationRequired(true);
        $this->assertTrue($this->entity->isValidationRequired());
        $this->entity->validate();
        $this->entity->save();

        // Check that the "validated" status is reset after saving the entity and
        // that trying to save a non-validated entity when validation is required
        // results in an exception.
        $this->assertTrue($this->entity->isValidationRequired());
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Entity validation was skipped.');
        $this->entity->save();
    }

    /**
     * @covers ::bundle
     */
    public function testBundle()
    {
        $this->assertSame($this->bundle, $this->entity->bundle());
    }

    /**
     * @covers ::access
     */
    public function testAccess()
    {
        $access = $this->createMock('\Drupal\Core\Entity\EntityAccessControlHandlerInterface');
        $operation = $this->randomMachineName();
        $access->expects($this->exactly(2))
      ->method('access')
      ->with($this->entity, $operation)
      ->willReturnOnConsecutiveCalls(true, AccessResult::allowed());
        $access->expects($this->exactly(2))
      ->method('createAccess')
      ->willReturnOnConsecutiveCalls(true, AccessResult::allowed());
        $this->entityTypeManager->expects($this->exactly(4))
      ->method('getAccessControlHandler')
      ->will($this->returnValue($access));
        $this->assertTrue($this->entity->access($operation));
        $this->assertEquals(AccessResult::allowed(), $this->entity->access($operation, null, true));
        $this->assertTrue($this->entity->access('create'));
        $this->assertEquals(AccessResult::allowed(), $this->entity->access('create', null, true));
    }

    /**
     * Data provider for testGet().
     *
     * @returns
     *   - Expected output from get().
     *   - Field name parameter to get().
     *   - Language code for $activeLanguage.
     *   - Fields array for $fields.
     */
    public function providerGet()
    {
        return [
      // Populated fields array.
      ['result', 'field_name', 'langcode', ['field_name' => ['langcode' => 'result']]],
      // Incomplete fields array.
      ['getTranslatedField_result', 'field_name', 'langcode', ['field_name' => 'no_langcode']],
      // Empty fields array.
      ['getTranslatedField_result', 'field_name', 'langcode', []],
    ];
    }

    /**
     * @covers ::get
     * @dataProvider providerGet
     */
    public function testGet($expected, $field_name, $active_langcode, $fields)
    {
        // Mock ContentEntityBase.
        $mock_base = $this->getMockBuilder('Drupal\Core\Entity\ContentEntityBase')
      ->disableOriginalConstructor()
      ->onlyMethods(['getTranslatedField'])
      ->getMockForAbstractClass();

        // Set up expectations for getTranslatedField() method. In get(),
        // getTranslatedField() is only called if the field name and language code
        // are not present as keys in the fields array.
        if (isset($fields[$field_name][$active_langcode])) {
            $mock_base->expects($this->never())
        ->method('getTranslatedField');
        } else {
            $mock_base->expects($this->once())
        ->method('getTranslatedField')
        ->with(
            $this->equalTo($field_name),
            $this->equalTo($active_langcode)
        )
        ->willReturn($expected);
        }

        // Poke in activeLangcode.
        $ref_langcode = new \ReflectionProperty($mock_base, 'activeLangcode');
        $ref_langcode->setAccessible(true);
        $ref_langcode->setValue($mock_base, $active_langcode);

        // Poke in fields.
        $ref_fields = new \ReflectionProperty($mock_base, 'fields');
        $ref_fields->setAccessible(true);
        $ref_fields->setValue($mock_base, $fields);

        // Exercise get().
        $this->assertEquals($expected, $mock_base->get($field_name));
    }

    /**
     * Data provider for testGetFields().
     *
     * @returns array
     *   - Expected output from getFields().
     *   - $include_computed value to pass to getFields().
     *   - Value to mock from all field definitions for isComputed().
     *   - Array of field names to return from mocked getFieldDefinitions(). A
     *     Drupal\Core\Field\FieldDefinitionInterface object will be mocked for
     *     each name.
     */
    public function providerGetFields()
    {
        return [
      [[], false, false, []],
      [['field' => 'field', 'field2' => 'field2'], true, false, ['field', 'field2']],
      [['field3' => 'field3'], true, true, ['field3']],
      [[], false, true, ['field4']],
    ];
    }

    /**
     * @covers ::getFields
     * @dataProvider providerGetFields
     */
    public function testGetFields($expected, $include_computed, $is_computed, $field_definitions)
    {
        // Mock ContentEntityBase.
        $mock_base = $this->getMockBuilder('Drupal\Core\Entity\ContentEntityBase')
      ->disableOriginalConstructor()
      ->onlyMethods(['getFieldDefinitions', 'get'])
      ->getMockForAbstractClass();

        // Mock field definition objects for each element of $field_definitions.
        $mocked_field_definitions = [];
        foreach ($field_definitions as $name) {
            $mock_definition = $this->getMockBuilder('Drupal\Core\Field\FieldDefinitionInterface')
        ->onlyMethods(['isComputed'])
        ->getMockForAbstractClass();
            // Set expectations for isComputed(). isComputed() gets called whenever
            // $include_computed is FALSE, but not otherwise. It returns the value of
            // $is_computed.
            $mock_definition->expects($this->exactly(
                $include_computed ? 0 : 1
            ))
        ->method('isComputed')
        ->willReturn($is_computed);
            $mocked_field_definitions[$name] = $mock_definition;
        }

        // Set up expectations for getFieldDefinitions().
        $mock_base->expects($this->once())
      ->method('getFieldDefinitions')
      ->willReturn($mocked_field_definitions);

        // How many time will we call get()? Since we are rigging all defined fields
        // to be computed based on $is_computed, then if $include_computed is FALSE,
        // get() will never be called.
        $get_count = 0;
        if ($include_computed) {
            $get_count = count($field_definitions);
        }

        // Set up expectations for get(). It simply returns the name passed in.
        $mock_base->expects($this->exactly($get_count))
      ->method('get')
      ->willReturnArgument(0);

        // Exercise getFields().
        $this->assertEquals(
            $expected,
            $mock_base->getFields($include_computed)
        );
    }

    /**
     * @covers ::set
     */
    public function testSet()
    {
        // Exercise set(), check if it returns $this
        $this->assertSame(
            $this->entity,
            $this->entity->set('id', 0)
        );
    }
}
