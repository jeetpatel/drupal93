<?php

namespace Drupal\KernelTests\Core\Test\Comparator;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\KernelTests\KernelTestBase;
use Drupal\TestTools\Comparator\MarkupInterfaceComparator;
use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\Error\Warning;
use SebastianBergmann\Comparator\Factory;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Tests \Drupal\TestTools\Comparator\MarkupInterfaceComparator.
 *
 * We need to test the class with a kernel test since casting MarkupInterface
 * objects to strings can require an initialized container.
 *
 * @group Test
 *
 * @coversDefaultClass \Drupal\TestTools\Comparator\MarkupInterfaceComparator
 */
class MarkupInterfaceComparatorTest extends KernelTestBase
{
  /**
   * @var \Drupal\TestTools\Comparator\MarkupInterfaceComparator
   */
    protected $comparator;

    /**
     * @var \SebastianBergmann\Comparator\Factory
     */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new Factory();
        $this->comparator = new MarkupInterfaceComparator();
        $this->comparator->setFactory($this->factory);
    }

    /**
     * Provides test data for the comparator.
     *
     * @return array
     *   Each array entry has:
     *   - test expected value,
     *   - test actual value,
     *   - a bool indicating the expected return value of ::accepts,
     *   - a value indicating the expected result of ::assertEquals, TRUE if
     *     comparison should match, FALSE if error, or a class name of an object
     *     thrown.
     */
    public function dataSetProvider()
    {
        return [
      'FormattableMarkup vs FormattableMarkup, equal' => [
        new FormattableMarkup('goldfinger', []),
        new FormattableMarkup('goldfinger', []),
        true,
        true,
      ],
      'FormattableMarkup vs FormattableMarkup, not equal' => [
        new FormattableMarkup('goldfinger', []),
        new FormattableMarkup('moonraker', []),
        true,
        ComparisonFailure::class,
      ],
      'FormattableMarkup vs string, equal' => [
        new FormattableMarkup('goldfinger', []),
        'goldfinger',
        true,
        true,
      ],
      'string vs FormattableMarkup, equal' => [
        'goldfinger',
        new FormattableMarkup('goldfinger', []),
        true,
        true,
      ],
      'TranslatableMarkup vs FormattableMarkup, equal' => [
        new TranslatableMarkup('goldfinger'),
        new FormattableMarkup('goldfinger', []),
        true,
        true,
      ],
      'TranslatableMarkup vs string, not equal' => [
        new TranslatableMarkup('goldfinger'),
        'moonraker',
        true,
        ComparisonFailure::class,
      ],
      'TranslatableMarkup vs int, equal' => [
        new TranslatableMarkup('1234'),
        1234,
        true,
        true,
      ],
      'int vs TranslatableMarkup, equal' => [
        1234,
        new TranslatableMarkup('1234'),
        true,
        true,
      ],
      'FormattableMarkup vs array' => [
        new FormattableMarkup('goldfinger', []),
        ['goldfinger'],
        false,
        PHP_VERSION_ID >= 80000 ? Warning::class : Notice::class,
      ],
      'stdClass vs TranslatableMarkup' => [
        (object) ['goldfinger'],
        new TranslatableMarkup('goldfinger'),
        false,
        false,
      ],
      'string vs string, equal' => [
        'goldfinger',
        'goldfinger',
        false,
        true,
      ],
    ];
    }

    /**
     * @covers ::accepts
     * @dataProvider dataSetProvider
     */
    public function testAccepts($expected, $actual, $accepts_result, $equals_result)
    {
        if ($accepts_result) {
            $this->assertTrue($this->comparator->accepts($expected, $actual));
        } else {
            $this->assertFalse($this->comparator->accepts($expected, $actual));
        }
    }

    /**
     * @covers ::assertEquals
     * @dataProvider dataSetProvider
     */
    public function testAssertEquals($expected, $actual, $accepts_result, $equals_result)
    {
        try {
            $this->assertNull($this->comparator->assertEquals($expected, $actual));
            $this->assertTrue($equals_result);
        } catch (\Throwable $e) {
            if ($equals_result === false) {
                $this->assertNotNull($e->getMessage());
            } else {
                $this->assertInstanceOf($equals_result, $e);
            }
        }
    }
}