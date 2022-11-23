<?php

namespace Drupal\Tests\Core\Menu;

use Drupal\Core\Menu\MenuLinkTreeElement;
use Drupal\Tests\UnitTestCase;

/**
 * Tests the menu link tree element value object.
 *
 * @group Menu
 *
 * @coversDefaultClass \Drupal\Core\Menu\MenuLinkTreeElement
 */
class MenuLinkTreeElementTest extends UnitTestCase
{
  /**
   * Tests construction.
   *
   * @covers ::__construct
   */
    public function testConstruction()
    {
        $link = MenuLinkMock::create(['id' => 'test']);
        $item = new MenuLinkTreeElement($link, false, 3, false, []);
        $this->assertSame($link, $item->link);
        $this->assertFalse($item->hasChildren);
        $this->assertSame(3, $item->depth);
        $this->assertFalse($item->inActiveTrail);
        $this->assertSame([], $item->subtree);
    }

    /**
     * Tests count().
     *
     * @covers ::count
     */
    public function testCount()
    {
        $link_1 = MenuLinkMock::create(['id' => 'test_1']);
        $link_2 = MenuLinkMock::create(['id' => 'test_2']);
        $child_item = new MenuLinkTreeElement($link_2, false, 2, false, []);
        $parent_item = new MenuLinkTreeElement($link_1, false, 2, false, [$child_item]);
        $this->assertSame(1, $child_item->count());
        $this->assertSame(2, $parent_item->count());
    }
}
