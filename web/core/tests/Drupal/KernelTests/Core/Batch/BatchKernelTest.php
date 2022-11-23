<?php

namespace Drupal\KernelTests\Core\Batch;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests batch functionality.
 *
 * @group Batch
 */
class BatchKernelTest extends KernelTestBase
{
  /**
   * {@inheritdoc}
   */
    protected function setUp(): void
    {
        parent::setUp();

        require_once $this->root . '/core/includes/batch.inc';
    }

    /**
     * Tests _batch_needs_update().
     */
    public function testNeedsUpdate()
    {
        // Before ever being called, the return value should be FALSE.
        $this->assertEquals(false, _batch_needs_update());

        // Set the value to TRUE.
        $this->assertEquals(true, _batch_needs_update(true));
        // Check that without a parameter TRUE is returned.
        $this->assertEquals(true, _batch_needs_update());

        // Set the value to FALSE.
        $this->assertEquals(false, _batch_needs_update(false));
        $this->assertEquals(false, _batch_needs_update());
    }
}
