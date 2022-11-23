<?php

namespace Drupal\Tests\Core\Validation\Plugin\Validation\Constraint;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\Plugin\DataType\BooleanData;
use Drupal\Core\TypedData\Plugin\DataType\FloatData;
use Drupal\Core\TypedData\Plugin\DataType\IntegerData;
use Drupal\Core\TypedData\Plugin\DataType\StringData;
use Drupal\Core\TypedData\Plugin\DataType\Uri;
use Drupal\Core\TypedData\PrimitiveInterface;
use Drupal\Core\Validation\Plugin\Validation\Constraint\PrimitiveTypeConstraint;
use Drupal\Core\Validation\Plugin\Validation\Constraint\PrimitiveTypeConstraintValidator;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @coversDefaultClass \Drupal\Core\Validation\Plugin\Validation\Constraint\PrimitiveTypeConstraintValidator
 * @group validation
 */
class PrimitiveTypeConstraintValidatorTest extends UnitTestCase
{
  /**
   * @covers ::validate
   *
   * @dataProvider provideTestValidate
   */
    public function testValidate(PrimitiveInterface $typed_data, $value, $valid)
    {
        $context = $this->createMock(ExecutionContextInterface::class);
        $context->expects($this->any())
      ->method('getObject')
      ->willReturn($typed_data);

        if ($valid) {
            $context->expects($this->never())
        ->method('addViolation');
        } else {
            $context->expects($this->once())
        ->method('addViolation');
        }

        $constraint = new PrimitiveTypeConstraint();

        $validate = new PrimitiveTypeConstraintValidator();
        $validate->initialize($context);
        $validate->validate($value, $constraint);
    }

    public function provideTestValidate()
    {
        $data = [];
        $data[] = [new BooleanData(DataDefinition::create('boolean')), null, true];

        $data[] = [new BooleanData(DataDefinition::create('boolean')), 1, true];
        $data[] = [new BooleanData(DataDefinition::create('boolean')), 'test', false];
        $data[] = [new FloatData(DataDefinition::create('float')), 1.5, true];
        $data[] = [new FloatData(DataDefinition::create('float')), 'test', false];
        $data[] = [new IntegerData(DataDefinition::create('integer')), 1, true];
        $data[] = [new IntegerData(DataDefinition::create('integer')), 1.5, false];
        $data[] = [new IntegerData(DataDefinition::create('integer')), 'test', false];
        $data[] = [new StringData(DataDefinition::create('string')), 'test', true];
        $data[] = [new StringData(DataDefinition::create('string')), new TranslatableMarkup('test'), true];
        // It is odd that 1 is a valid string.
        // $data[] = [$this->createMock('Drupal\Core\TypedData\Type\StringInterface'), 1, FALSE];
        $data[] = [new StringData(DataDefinition::create('string')), [], false];
        $data[] = [new Uri(DataDefinition::create('uri')), 'http://www.drupal.org', true];
        $data[] = [new Uri(DataDefinition::create('uri')), 'https://www.drupal.org', true];
        $data[] = [new Uri(DataDefinition::create('uri')), 'Invalid', false];
        $data[] = [new Uri(DataDefinition::create('uri')), 'entity:node/1', true];
        $data[] = [new Uri(DataDefinition::create('uri')), 'base:', true];
        $data[] = [new Uri(DataDefinition::create('uri')), 'base:node', true];
        $data[] = [new Uri(DataDefinition::create('uri')), 'internal:', true];
        $data[] = [new Uri(DataDefinition::create('uri')), 'public://', false];
        $data[] = [new Uri(DataDefinition::create('uri')), 'public://foo.png', true];
        $data[] = [new Uri(DataDefinition::create('uri')), 'private://', false];
        $data[] = [new Uri(DataDefinition::create('uri')), 'private://foo.png', true];
        $data[] = [new Uri(DataDefinition::create('uri')), 'drupal.org', false];

        return $data;
    }
}
