<?php

namespace Drupal\Tests\Core\Ajax;

use Drupal\Core\Ajax\OpenOffCanvasDialogCommand;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\Core\Ajax\OpenOffCanvasDialogCommand
 * @group Ajax
 */
class OpenOffCanvasDialogCommandTest extends UnitTestCase
{
  /**
   * @covers ::render
   *
   * @dataProvider dialogPosition
   */
    public function testRender($position)
    {
        $command = new OpenOffCanvasDialogCommand('Title', '<p>Text!</p>', ['url' => 'example'], null, $position);

        $expected = [
      'command' => 'openDialog',
      'selector' => '#drupal-off-canvas',
      'settings' => null,
      'data' => '<p>Text!</p>',
      'dialogOptions' => [
        'url' => 'example',
        'title' => 'Title',
        'modal' => false,
        'autoResize' => false,
        'resizable' => 'w',
        'draggable' => false,
        'drupalAutoButtons' => false,
        'buttons' => [],
        'dialogClass' => 'ui-dialog-off-canvas ui-dialog-position-' . $position,
        'width' => 300,
        'drupalOffCanvasPosition' => $position,
      ],
      'effect' => 'fade',
      'speed' => 1000,
    ];
        $this->assertEquals($expected, $command->render());
    }

    /**
     * The data provider for potential dialog positions.
     *
     * @return array
     */
    public static function dialogPosition()
    {
        return [
      ['side'],
      ['top'],
    ];
    }
}
