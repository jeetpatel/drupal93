<?php

namespace Drupal\Tests\Component\Serialization;

use PHPUnit\Framework\TestCase;

/**
 * Provides standard data to validate different YAML implementations.
 */
abstract class YamlTestBase extends TestCase
{
  /**
   * Some data that should be able to be serialized.
   */
    public function providerEncodeDecodeTests()
    {
        return [
      [
        'foo' => 'bar',
        'id' => 'schnitzel',
        'ponies' => ['nope', 'thanks'],
        'how' => [
          'about' => 'if',
          'i' => 'ask',
          'nicely',
        ],
        'the' => [
          'answer' => [
            'still' => 'would',
            'be' => 'Y',
          ],
        ],
        'how_many_times' => 123,
        'should_i_ask' => false,
        1,
        false,
        [1, false],
        [10],
        [0 => '123456'],
      ],
      [null],
    ];
    }

    /**
     * Some data that should be able to be de-serialized.
     */
    public function providerDecodeTests()
    {
        $data = [
      // NULL files.
      ['', null],
      ["\n", null],
      ["---\n...\n", null],

      // Node anchors.
      [
        "
jquery.ui:
  version: &jquery_ui 1.10.2

jquery.ui.accordion:
  version: *jquery_ui
",
        [
          'jquery.ui' => [
            'version' => '1.10.2',
          ],
          'jquery.ui.accordion' => [
            'version' => '1.10.2',
          ],
        ],
      ],
    ];

        // 1.2 Bool values.
        foreach ($this->providerBoolTest() as $test) {
            $data[] = ['bool: ' . $test[0], ['bool' => $test[1]]];
        }
        $data = array_merge($data, $this->providerBoolTest());

        return $data;
    }

    /**
     * Tests different boolean serialization and de-serialization.
     */
    public function providerBoolTest()
    {
        return [
      ['true', true],
      ['TRUE', true],
      ['True', true],
      ['y', 'y'],
      ['Y', 'Y'],
      ['false', false],
      ['FALSE', false],
      ['False', false],
      ['n', 'n'],
      ['N', 'N'],
    ];
    }
}
