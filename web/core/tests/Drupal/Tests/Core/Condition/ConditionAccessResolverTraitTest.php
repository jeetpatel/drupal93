<?php

/**
 * @file
 * Contains \Drupal\Tests\Core\Condition\ConditionAccessResolverTraitTest.
 */

namespace Drupal\Tests\Core\Condition;

use Drupal\Component\Plugin\Exception\ContextException;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\Core\Condition\ConditionAccessResolverTrait
 * @group Condition
 */
class ConditionAccessResolverTraitTest extends UnitTestCase
{
  /**
   * Tests the resolveConditions() method.
   *
   * @covers ::resolveConditions
   *
   * @dataProvider providerTestResolveConditions
   */
    public function testResolveConditions($conditions, $logic, $expected)
    {
        $trait_object = new TestConditionAccessResolverTrait();
        $this->assertEquals($expected, $trait_object->resolveConditions($conditions, $logic));
    }

    public function providerTestResolveConditions()
    {
        $data = [];

        $condition_true = $this->createMock('Drupal\Core\Condition\ConditionInterface');
        $condition_true->expects($this->any())
      ->method('execute')
      ->will($this->returnValue(true));
        $condition_false = $this->createMock('Drupal\Core\Condition\ConditionInterface');
        $condition_false->expects($this->any())
      ->method('execute')
      ->will($this->returnValue(false));
        $condition_exception = $this->createMock('Drupal\Core\Condition\ConditionInterface');
        $condition_exception->expects($this->any())
      ->method('execute')
      ->will($this->throwException(new ContextException()));
        $condition_exception->expects($this->atLeastOnce())
      ->method('isNegated')
      ->will($this->returnValue(false));
        $condition_negated = $this->createMock('Drupal\Core\Condition\ConditionInterface');
        $condition_negated->expects($this->any())
      ->method('execute')
      ->will($this->throwException(new ContextException()));
        $condition_negated->expects($this->atLeastOnce())
      ->method('isNegated')
      ->will($this->returnValue(true));

        $conditions = [];
        $data[] = [$conditions, 'and', true];
        $data[] = [$conditions, 'or', false];

        $conditions = [$condition_false];
        $data[] = [$conditions, 'or', false];
        $data[] = [$conditions, 'and', false];

        $conditions = [$condition_true];
        $data[] = [$conditions, 'or', true];
        $data[] = [$conditions, 'and', true];

        $conditions = [$condition_true, $condition_false];
        $data[] = [$conditions, 'or', true];
        $data[] = [$conditions, 'and', false];

        $conditions = [$condition_exception];
        $data[] = [$conditions, 'or', false];
        $data[] = [$conditions, 'and', false];

        $conditions = [$condition_true, $condition_exception];
        $data[] = [$conditions, 'or', true];
        $data[] = [$conditions, 'and', false];

        $conditions = [$condition_exception, $condition_true];
        $data[] = [$conditions, 'or', true];
        $data[] = [$conditions, 'and', false];

        $conditions = [$condition_false, $condition_exception];
        $data[] = [$conditions, 'or', false];
        $data[] = [$conditions, 'and', false];

        $conditions = [$condition_exception, $condition_false];
        $data[] = [$conditions, 'or', false];
        $data[] = [$conditions, 'and', false];

        $conditions = [$condition_negated];
        $data[] = [$conditions, 'or', true];
        $data[] = [$conditions, 'and', true];

        $conditions = [$condition_negated, $condition_negated];
        $data[] = [$conditions, 'or', true];
        $data[] = [$conditions, 'and', true];
        return $data;
    }
}

class TestConditionAccessResolverTrait
{
    use \Drupal\Core\Condition\ConditionAccessResolverTrait {
    resolveConditions as public;
  }
}
