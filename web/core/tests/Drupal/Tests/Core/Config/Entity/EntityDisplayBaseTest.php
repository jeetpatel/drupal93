<?php

namespace Drupal\Tests\Core\Config\Entity;

use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\Core\Entity\EntityDisplayBase
 *
 * @group Config
 */
class EntityDisplayBaseTest extends UnitTestCase
{
  /**
   * @covers ::getTargetEntityTypeId
   */
    public function testGetTargetEntityTypeId()
    {
        $mock = $this->getMockForAbstractClass('\Drupal\Core\Entity\EntityDisplayBase', [], '', false);
        $reflection = new \ReflectionProperty($mock, 'targetEntityType');
        $reflection->setAccessible(true);
        $reflection->setValue($mock, 'test');
        $this->assertEquals('test', $mock->getTargetEntityTypeId());
    }

    /**
     * @covers ::getMode
     */
    public function testGetMode()
    {
        $mock = $this->getMockForAbstractClass('\Drupal\Core\Entity\EntityDisplayBase', [], '', false);
        $reflection = new \ReflectionProperty($mock, 'mode');
        $reflection->setAccessible(true);
        $reflection->setValue($mock, 'test');
        $this->assertEquals('test', $mock->getMode());
    }

    /**
     * @covers ::getOriginalMode
     */
    public function testGetOriginalMode()
    {
        $mock = $this->getMockForAbstractClass('\Drupal\Core\Entity\EntityDisplayBase', [], '', false);
        $reflection = new \ReflectionProperty($mock, 'originalMode');
        $reflection->setAccessible(true);
        $reflection->setValue($mock, 'test');
        $this->assertEquals('test', $mock->getOriginalMode());
    }

    /**
     * @covers ::getTargetBundle
     */
    public function testGetTargetBundle()
    {
        $mock = $this->getMockForAbstractClass('\Drupal\Core\Entity\EntityDisplayBase', [], '', false);
        $reflection = new \ReflectionProperty($mock, 'bundle');
        $reflection->setAccessible(true);
        $reflection->setValue($mock, 'test');
        $this->assertEquals('test', $mock->getTargetBundle());
    }

    /**
     * @covers ::setTargetBundle
     */
    public function testSetTargetBundle()
    {
        $mock = $this->getMockForAbstractClass('\Drupal\Core\Entity\EntityDisplayBase', [], '', false);
        $reflection = new \ReflectionProperty($mock, 'bundle');
        $reflection->setAccessible(true);
        $mock->setTargetBundle('test');
        $this->assertEquals('test', $reflection->getValue($mock));
    }
}
