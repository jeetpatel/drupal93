<?php

namespace Drupal\Tests\Core\Asset;

use Drupal\Core\Asset\CssCollectionRenderer;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\State\StateInterface;

/**
 * Tests the CSS asset collection renderer.
 *
 * @group Asset
 */
class CssCollectionRendererUnitTest extends UnitTestCase
{
  /**
   * A CSS asset renderer.
   *
   * @var \Drupal\Core\Asset\CssCollectionRenderer
   */
    protected $renderer;

    /**
     * A valid file CSS asset group.
     *
     * @var array
     */
    protected $fileCssGroup;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $state = $this->prophesize(StateInterface::class);
        $state->get('system.css_js_query_string', '0')->shouldBeCalledOnce()->willReturn(null);
        $this->renderer = new CssCollectionRenderer($state->reveal());
        $this->fileCssGroup = [
      'group' => -100,
      'type' => 'file',
      'media' => 'all',
      'preprocess' => true,
      'browsers' => ['IE' => true, '!IE' => true],
      'items' => [
        0 => [
          'group' => -100,
          'type' => 'file',
          'weight' => 0.012,
          'media' => 'all',
          'preprocess' => true,
          'data' => 'tests/Drupal/Tests/Core/Asset/foo.css',
          'browsers' => ['IE' => true, '!IE' => true],
          'basename' => 'foo.css',
        ],
        1 => [
          'group' => -100,
          'type' => 'file',
          'weight' => 0.013,
          'media' => 'all',
          'preprocess' => true,
          'data' => 'tests/Drupal/Tests/Core/Asset/bar.css',
          'browsers' => ['IE' => true, '!IE' => true],
          'basename' => 'bar.css',
        ],
      ],
    ];
    }

    /**
     * Provides data for the CSS asset rendering test.
     *
     * @see testRender
     */
    public function providerTestRender()
    {
        $create_link_element = function ($href, $media = 'all', $browsers = [], $custom_attributes = []) {
            $attributes = [
        'rel' => 'stylesheet',
        'media' => $media,
        'href' => $href,
      ];
            return [
        '#type' => 'html_tag',
        '#tag' => 'link',
        '#attributes' => array_replace($attributes, $custom_attributes),
        '#browsers' => $browsers,
      ];
        };

        $create_file_css_asset = function ($data, $media = 'all', $preprocess = true) {
            return ['group' => 0, 'type' => 'file', 'media' => $media, 'preprocess' => $preprocess, 'data' => $data, 'browsers' => []];
        };

        $custom_attributes = ['integrity' => 'sha384-psK1OYPAYjYUhtDYW+Pj2yc', 'crossorigin' => 'anonymous', 'random-attribute' => 'test'];

        return [
      // Single external CSS asset.
      0 => [
        // CSS assets.
        [
          0 => ['group' => 0, 'type' => 'external', 'media' => 'all', 'preprocess' => true, 'data' => 'http://example.com/popular.js', 'browsers' => []],
        ],
        // Render elements.
        [
          0 => $create_link_element('http://example.com/popular.js', 'all'),
        ],
      ],
      // Single file CSS asset.
      1 => [
        [
          0 => ['group' => 0, 'type' => 'file', 'media' => 'all', 'preprocess' => true, 'data' => 'public://css/file-all', 'browsers' => []],
        ],
        [
          0 => $create_link_element(file_url_transform_relative(file_create_url('public://css/file-all')) . '?', 'all'),
        ],
      ],
      // Single file CSS asset with custom attributes.
      2 => [
        [
          0 => ['group' => 0, 'type' => 'file', 'media' => 'all', 'preprocess' => true, 'data' => 'public://css/file-all', 'browsers' => [], 'attributes' => $custom_attributes],
        ],
        [
          0 => $create_link_element(file_url_transform_relative(file_create_url('public://css/file-all')) . '?', 'all', [], $custom_attributes),
        ],
      ],
      // 31 file CSS assets: expect 31 link elements.
      3 => [
        [
          0 => $create_file_css_asset('public://css/1.css'),
          1 => $create_file_css_asset('public://css/2.css'),
          2 => $create_file_css_asset('public://css/3.css'),
          3 => $create_file_css_asset('public://css/4.css'),
          4 => $create_file_css_asset('public://css/5.css'),
          5 => $create_file_css_asset('public://css/6.css'),
          6 => $create_file_css_asset('public://css/7.css'),
          7 => $create_file_css_asset('public://css/8.css'),
          8 => $create_file_css_asset('public://css/9.css'),
          9 => $create_file_css_asset('public://css/10.css'),
          10 => $create_file_css_asset('public://css/11.css'),
          11 => $create_file_css_asset('public://css/12.css'),
          12 => $create_file_css_asset('public://css/13.css'),
          13 => $create_file_css_asset('public://css/14.css'),
          14 => $create_file_css_asset('public://css/15.css'),
          15 => $create_file_css_asset('public://css/16.css'),
          16 => $create_file_css_asset('public://css/17.css'),
          17 => $create_file_css_asset('public://css/18.css'),
          18 => $create_file_css_asset('public://css/19.css'),
          19 => $create_file_css_asset('public://css/20.css'),
          20 => $create_file_css_asset('public://css/21.css'),
          21 => $create_file_css_asset('public://css/22.css'),
          22 => $create_file_css_asset('public://css/23.css'),
          23 => $create_file_css_asset('public://css/24.css'),
          24 => $create_file_css_asset('public://css/25.css'),
          25 => $create_file_css_asset('public://css/26.css'),
          26 => $create_file_css_asset('public://css/27.css'),
          27 => $create_file_css_asset('public://css/28.css'),
          28 => $create_file_css_asset('public://css/29.css'),
          29 => $create_file_css_asset('public://css/30.css'),
          30 => $create_file_css_asset('public://css/31.css'),
        ],
        [
          0 => $create_link_element(file_url_transform_relative(file_create_url('public://css/1.css')) . '?'),
          1 => $create_link_element(file_url_transform_relative(file_create_url('public://css/2.css')) . '?'),
          2 => $create_link_element(file_url_transform_relative(file_create_url('public://css/3.css')) . '?'),
          3 => $create_link_element(file_url_transform_relative(file_create_url('public://css/4.css')) . '?'),
          4 => $create_link_element(file_url_transform_relative(file_create_url('public://css/5.css')) . '?'),
          5 => $create_link_element(file_url_transform_relative(file_create_url('public://css/6.css')) . '?'),
          6 => $create_link_element(file_url_transform_relative(file_create_url('public://css/7.css')) . '?'),
          7 => $create_link_element(file_url_transform_relative(file_create_url('public://css/8.css')) . '?'),
          8 => $create_link_element(file_url_transform_relative(file_create_url('public://css/9.css')) . '?'),
          9 => $create_link_element(file_url_transform_relative(file_create_url('public://css/10.css')) . '?'),
          10 => $create_link_element(file_url_transform_relative(file_create_url('public://css/11.css')) . '?'),
          11 => $create_link_element(file_url_transform_relative(file_create_url('public://css/12.css')) . '?'),
          12 => $create_link_element(file_url_transform_relative(file_create_url('public://css/13.css')) . '?'),
          13 => $create_link_element(file_url_transform_relative(file_create_url('public://css/14.css')) . '?'),
          14 => $create_link_element(file_url_transform_relative(file_create_url('public://css/15.css')) . '?'),
          15 => $create_link_element(file_url_transform_relative(file_create_url('public://css/16.css')) . '?'),
          16 => $create_link_element(file_url_transform_relative(file_create_url('public://css/17.css')) . '?'),
          17 => $create_link_element(file_url_transform_relative(file_create_url('public://css/18.css')) . '?'),
          18 => $create_link_element(file_url_transform_relative(file_create_url('public://css/19.css')) . '?'),
          19 => $create_link_element(file_url_transform_relative(file_create_url('public://css/20.css')) . '?'),
          20 => $create_link_element(file_url_transform_relative(file_create_url('public://css/21.css')) . '?'),
          21 => $create_link_element(file_url_transform_relative(file_create_url('public://css/22.css')) . '?'),
          22 => $create_link_element(file_url_transform_relative(file_create_url('public://css/23.css')) . '?'),
          23 => $create_link_element(file_url_transform_relative(file_create_url('public://css/24.css')) . '?'),
          24 => $create_link_element(file_url_transform_relative(file_create_url('public://css/25.css')) . '?'),
          25 => $create_link_element(file_url_transform_relative(file_create_url('public://css/26.css')) . '?'),
          26 => $create_link_element(file_url_transform_relative(file_create_url('public://css/27.css')) . '?'),
          27 => $create_link_element(file_url_transform_relative(file_create_url('public://css/28.css')) . '?'),
          28 => $create_link_element(file_url_transform_relative(file_create_url('public://css/29.css')) . '?'),
          29 => $create_link_element(file_url_transform_relative(file_create_url('public://css/30.css')) . '?'),
          30 => $create_link_element(file_url_transform_relative(file_create_url('public://css/31.css')) . '?'),
        ],
      ],
      // 32 file CSS assets with the same properties, except for the 10th and
      // 20th files, they have different 'media' properties.
      4 => [
        [
          0 => $create_file_css_asset('public://css/1.css'),
          1 => $create_file_css_asset('public://css/2.css'),
          2 => $create_file_css_asset('public://css/3.css'),
          3 => $create_file_css_asset('public://css/4.css'),
          4 => $create_file_css_asset('public://css/5.css'),
          5 => $create_file_css_asset('public://css/6.css'),
          6 => $create_file_css_asset('public://css/7.css'),
          7 => $create_file_css_asset('public://css/8.css'),
          8 => $create_file_css_asset('public://css/9.css'),
          9 => $create_file_css_asset('public://css/10.css', 'screen'),
          10 => $create_file_css_asset('public://css/11.css'),
          11 => $create_file_css_asset('public://css/12.css'),
          12 => $create_file_css_asset('public://css/13.css'),
          13 => $create_file_css_asset('public://css/14.css'),
          14 => $create_file_css_asset('public://css/15.css'),
          15 => $create_file_css_asset('public://css/16.css'),
          16 => $create_file_css_asset('public://css/17.css'),
          17 => $create_file_css_asset('public://css/18.css'),
          18 => $create_file_css_asset('public://css/19.css'),
          19 => $create_file_css_asset('public://css/20.css', 'print'),
          20 => $create_file_css_asset('public://css/21.css'),
          21 => $create_file_css_asset('public://css/22.css'),
          22 => $create_file_css_asset('public://css/23.css'),
          23 => $create_file_css_asset('public://css/24.css'),
          24 => $create_file_css_asset('public://css/25.css'),
          25 => $create_file_css_asset('public://css/26.css'),
          26 => $create_file_css_asset('public://css/27.css'),
          27 => $create_file_css_asset('public://css/28.css'),
          28 => $create_file_css_asset('public://css/29.css'),
          29 => $create_file_css_asset('public://css/30.css'),
          30 => $create_file_css_asset('public://css/31.css'),
          31 => $create_file_css_asset('public://css/32.css'),
        ],
        [
          0 => $create_link_element(file_url_transform_relative(file_create_url('public://css/1.css')) . '?'),
          1 => $create_link_element(file_url_transform_relative(file_create_url('public://css/2.css')) . '?'),
          2 => $create_link_element(file_url_transform_relative(file_create_url('public://css/3.css')) . '?'),
          3 => $create_link_element(file_url_transform_relative(file_create_url('public://css/4.css')) . '?'),
          4 => $create_link_element(file_url_transform_relative(file_create_url('public://css/5.css')) . '?'),
          5 => $create_link_element(file_url_transform_relative(file_create_url('public://css/6.css')) . '?'),
          6 => $create_link_element(file_url_transform_relative(file_create_url('public://css/7.css')) . '?'),
          7 => $create_link_element(file_url_transform_relative(file_create_url('public://css/8.css')) . '?'),
          8 => $create_link_element(file_url_transform_relative(file_create_url('public://css/9.css')) . '?'),
          9 => $create_link_element(file_url_transform_relative(file_create_url('public://css/10.css')) . '?', 'screen'),
          10 => $create_link_element(file_url_transform_relative(file_create_url('public://css/11.css')) . '?'),
          11 => $create_link_element(file_url_transform_relative(file_create_url('public://css/12.css')) . '?'),
          12 => $create_link_element(file_url_transform_relative(file_create_url('public://css/13.css')) . '?'),
          13 => $create_link_element(file_url_transform_relative(file_create_url('public://css/14.css')) . '?'),
          14 => $create_link_element(file_url_transform_relative(file_create_url('public://css/15.css')) . '?'),
          15 => $create_link_element(file_url_transform_relative(file_create_url('public://css/16.css')) . '?'),
          16 => $create_link_element(file_url_transform_relative(file_create_url('public://css/17.css')) . '?'),
          17 => $create_link_element(file_url_transform_relative(file_create_url('public://css/18.css')) . '?'),
          18 => $create_link_element(file_url_transform_relative(file_create_url('public://css/19.css')) . '?'),
          19 => $create_link_element(file_url_transform_relative(file_create_url('public://css/20.css')) . '?', 'print'),
          20 => $create_link_element(file_url_transform_relative(file_create_url('public://css/21.css')) . '?'),
          21 => $create_link_element(file_url_transform_relative(file_create_url('public://css/22.css')) . '?'),
          22 => $create_link_element(file_url_transform_relative(file_create_url('public://css/23.css')) . '?'),
          23 => $create_link_element(file_url_transform_relative(file_create_url('public://css/24.css')) . '?'),
          24 => $create_link_element(file_url_transform_relative(file_create_url('public://css/25.css')) . '?'),
          25 => $create_link_element(file_url_transform_relative(file_create_url('public://css/26.css')) . '?'),
          26 => $create_link_element(file_url_transform_relative(file_create_url('public://css/27.css')) . '?'),
          27 => $create_link_element(file_url_transform_relative(file_create_url('public://css/28.css')) . '?'),
          28 => $create_link_element(file_url_transform_relative(file_create_url('public://css/29.css')) . '?'),
          29 => $create_link_element(file_url_transform_relative(file_create_url('public://css/30.css')) . '?'),
          30 => $create_link_element(file_url_transform_relative(file_create_url('public://css/31.css')) . '?'),
          31 => $create_link_element(file_url_transform_relative(file_create_url('public://css/32.css')) . '?'),
        ],
      ],
    ];
    }

    /**
     * Tests CSS asset rendering.
     *
     * @dataProvider providerTestRender
     */
    public function testRender(array $css_assets, array $render_elements)
    {
        $this->assertSame($render_elements, $this->renderer->render($css_assets));
    }

    /**
     * Tests a CSS asset group with the invalid 'type' => 'internal'.
     */
    public function testRenderInvalidType()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Invalid CSS asset type.');

        $css_group = [
      'group' => 0,
      'type' => 'internal',
      'media' => 'all',
      'preprocess' => true,
      'browsers' => [],
      'data' => 'http://example.com/popular.js',
    ];
        $this->renderer->render([$css_group]);
    }
}

/**
 * Temporary mock for file_create_url(), until that is moved into
 * Component/Utility.
 */
if (!function_exists('Drupal\Tests\Core\Asset\file_create_url')) {
    function file_create_url($uri)
    {
        return 'file_create_url:' . $uri;
    }
}

/**
 * Temporary mock of file_url_transform_relative, until that is moved into
 * Component/Utility.
 */
if (!function_exists('Drupal\Tests\Core\Asset\file_url_transform_relative')) {
    function file_url_transform_relative($uri)
    {
        return 'file_url_transform_relative:' . $uri;
    }
}

/**
 * CssCollectionRenderer uses file_create_url() & file_url_transform_relative(),
 * which *are* available when using the Simpletest test runner, but not when
 * using the PHPUnit test runner; hence this hack.
 */

namespace Drupal\Core\Asset;

if (!function_exists('Drupal\Core\Asset\file_create_url')) {

  /**
   * Temporary mock for file_create_url(), until that is moved into
   * Component/Utility.
   */
    function file_create_url($uri)
    {
        return \Drupal\Tests\Core\Asset\file_create_url($uri);
    }
}
if (!function_exists('Drupal\Core\Asset\file_url_transform_relative')) {

  /**
   * Temporary mock of file_url_transform_relative, until that is moved into
   * Component/Utility.
   */
    function file_url_transform_relative($uri)
    {
        return \Drupal\Tests\Core\Asset\file_url_transform_relative($uri);
    }
}
