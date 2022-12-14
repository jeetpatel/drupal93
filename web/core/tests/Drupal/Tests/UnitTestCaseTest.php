<?php

namespace Drupal\Tests;

/**
 * Tests for the UnitTestCase class.
 *
 * @group Tests
 */
class UnitTestCaseTest extends UnitTestCase
{
  /**
   * Tests deprecation of the ::assertArrayEquals method.
   *
   * @group legacy
   */
    public function testAssertArrayEquals()
    {
        $this->expectDeprecation('Drupal\Tests\UnitTestCase::assertArrayEquals() is deprecated in drupal:9.1.0 and is removed from drupal:10.0.0. Use ::assertEquals(), ::assertEqualsCanonicalizing(), or ::assertSame() instead. See https://www.drupal.org/node/3136304');
        $this->assertArrayEquals([], []);
    }

    /**
     * Tests the dump() function in a test run in the same process.
     */
    public function testVarDumpSameProcess()
    {
        // Append the stream capturer to the STDOUT stream, so that we can test the
        // dump() output and also prevent it from actually outputting in this
        // particular test.
        stream_filter_register("capture", StreamCapturer::class);
        stream_filter_append(STDOUT, "capture");

        // Dump some variables.
        $object = (object) [
      'foo' => 'bar',
    ];
        dump($object);
        dump('banana');

        $this->assertStringContainsString('bar', StreamCapturer::$cache);
        $this->assertStringContainsString('banana', StreamCapturer::$cache);
    }

    /**
     * Tests the dump() function in a test run in a separate process.
     *
     * @runInSeparateProcess
     */
    public function testVarDumpSeparateProcess()
    {
        // Append the stream capturer to the STDOUT stream, so that we can test the
        // dump() output and also prevent it from actually outputting in this
        // particular test.
        stream_filter_register("capture", StreamCapturer::class);
        stream_filter_append(STDOUT, "capture");

        // Dump some variables.
        $object = (object) [
      'foo' => 'bar',
    ];
        dump($object);
        dump('banana');

        $this->assertStringContainsString('bar', StreamCapturer::$cache);
        $this->assertStringContainsString('banana', StreamCapturer::$cache);
    }
}
