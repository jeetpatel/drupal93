<?php

namespace Drupal\Tests\Component\Utility;

use Drupal\Component\Utility\Number;
use PHPUnit\Framework\TestCase;

/**
 * Tests number manipulation utilities.
 *
 * @group Utility
 *
 * @coversDefaultClass \Drupal\Component\Utility\Number
 *
 * @see \Drupal\Component\Utility\Number
 */
class NumberTest extends TestCase
{
  /**
   * Tests Number::validStep() without offset.
   *
   * @dataProvider providerTestValidStep
   * @covers ::validStep
   *
   * @param numeric $value
   *   The value argument for Number::validStep().
   * @param numeric $step
   *   The step argument for Number::validStep().
   * @param bool $expected
   *   Expected return value from Number::validStep().
   */
    public function testValidStep($value, $step, $expected)
    {
        $return = Number::validStep($value, $step);
        $this->assertEquals($expected, $return);
    }

    /**
     * Tests Number::validStep() with offset.
     *
     * @dataProvider providerTestValidStepOffset
     * @covers ::validStep
     *
     * @param numeric $value
     *   The value argument for Number::validStep().
     * @param numeric $step
     *   The step argument for Number::validStep().
     * @param numeric $offset
     *   The offset argument for Number::validStep().
     * @param bool $expected
     *   Expected return value from Number::validStep().
     */
    public function testValidStepOffset($value, $step, $offset, $expected)
    {
        $return = Number::validStep($value, $step, $offset);
        $this->assertEquals($expected, $return);
    }

    /**
     * Provides data for self::testNumberStep().
     *
     * @see \Drupal\Tests\Component\Utility\Number::testValidStep
     */
    public static function providerTestValidStep()
    {
        return [
      // Value and step equal.
      [10.3, 10.3, true],

      // Valid integer steps.
      [42, 21, true],
      [42, 3, true],

      // Valid float steps.
      [42, 10.5, true],
      [1, 1 / 3, true],
      [-100, 100 / 7, true],
      [1000, -10, true],

      // Valid and very small float steps.
      [1000.12345, 1e-10, true],
      [3.9999999999999, 1e-13, true],

      // Invalid integer steps.
      [100, 30, false],
      [-10, 4, false],

      // Invalid float steps.
      [6, 5 / 7, false],
      [10.3, 10.25, false],

      // Step mismatches very close to being valid.
      [70 + 9e-7, 10 + 9e-7, false],
      [1936.5, 3e-8, false],
    ];
    }

    /**
     * Data provider for \Drupal\Tests\Component\Utility\NumberTest::testValidStepOffset().
     *
     * @see \Drupal\Tests\Component\Utility\NumberTest::testValidStepOffset()
     */
    public static function providerTestValidStepOffset()
    {
        return [
      // Try obvious fits.
      [11.3, 10.3, 1, true],
      [100, 10, 50, true],
      [-100, 90 / 7, -10, true],
      [2 / 7 + 5 / 9, 1 / 7, 5 / 9, true],

      // Ensure a small offset is still invalid.
      [10.3, 10.3, 0.0001, false],
      [1 / 5, 1 / 7, 1 / 11, false],

      // Try negative values and offsets.
      [1000, 10, -5, false],
      [-10, 4, 0, false],
      [-10, 4, -4, false],
    ];
    }

    /**
     * Tests the alphadecimal conversion functions.
     *
     * @dataProvider providerTestConversions
     * @covers ::intToAlphadecimal
     * @covers ::alphadecimalToInt
     *
     * @param int $value
     *   The integer value.
     * @param string $expected
     *   The expected alphadecimal value.
     */
    public function testConversions($value, $expected)
    {
        $this->assertSame(Number::intToAlphadecimal($value), $expected);
        $this->assertSame($value, Number::alphadecimalToInt($expected));
    }

    /**
     * Data provider for testConversions().
     *
     * @see testConversions()
     *
     * @return array
     *   An array containing:
     *     - The integer value.
     *     - The alphadecimal value.
     */
    public function providerTestConversions()
    {
        return [
      [0, '00'],
      [1, '01'],
      [10, '0a'],
      [20, '0k'],
      [35, '0z'],
      [36, '110'],
      [100, '12s'],
    ];
    }
}
