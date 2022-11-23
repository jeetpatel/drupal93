<?php

namespace Drupal\Tests\Core\Path;

use Drupal\Core\Path\PathMatcher;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\Core\Path\PathMatcher
 * @group Path
 */
class PathMatcherTest extends UnitTestCase
{
  /**
   * The path matcher under test.
   *
   * @var \Drupal\Core\Path\PathMatcher
   */
    protected $pathMatcher;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        // Create a stub config factory with all config settings that will be
        // checked during this test.
        $config_factory_stub = $this->getConfigFactoryStub(
            [
        'system.site' => [
          'page.front' => '/dummy',
        ],
      ]
        );
        $route_match = $this->createMock('Drupal\Core\Routing\RouteMatchInterface');
        $this->pathMatcher = new PathMatcher($config_factory_stub, $route_match);
    }

    /**
     * Tests that standard paths works with multiple patterns.
     *
     * @dataProvider getMatchPathData
     */
    public function testMatchPath($patterns, $paths)
    {
        foreach ($paths as $path => $expected_result) {
            $actual_result = $this->pathMatcher->matchPath($path, $patterns);
            $this->assertEquals($actual_result, $expected_result, "Tried matching the path '$path' to the pattern '$patterns'.");
        }
    }

    /**
     * Provides test path data.
     *
     * @return array
     *   A nested array of pattern arrays and path arrays.
     */
    public function getMatchPathData()
    {
        return [
      [
        // Single absolute paths.
        '/example/1',
        [
          '/example/1' => true,
          '/example/2' => false,
          '/test' => false,
        ],
      ],
      [
        // Single paths with wildcards.
        '/example/*',
        [
          '/example/1' => true,
          '/example/2' => true,
          '/example/3/edit' => true,
          '/example/' => true,
          '/example' => false,
          '/test' => false,
        ],
      ],
      [
        // Single paths with multiple wildcards.
        '/node/*/revisions/*',
        [
          '/node/1/revisions/3' => true,
          '/node/345/revisions/test' => true,
          '/node/23/edit' => false,
          '/test' => false,
        ],
      ],
      [
        // Single paths with '<front>'.
        "<front>",
        [
          '/dummy' => true,
          "/dummy/" => false,
          "/dummy/edit" => false,
          '/node' => false,
          '' => false,
        ],
      ],
      [
        // Paths with both '<front>' and wildcards (should not work).
        "<front>/*",
        [
          '/dummy' => false,
          '/dummy/' => false,
          '/dummy/edit' => false,
          '/node/12' => false,
          '/' => false,
        ],
      ],
      [
        // Multiple paths with the \n delimiter.
        "/node/*\n/node/*/edit",
        [
          '/node/1' => true,
          '/node/view' => true,
          '/node/32/edit' => true,
          '/node/delete/edit' => true,
          '/node/50/delete' => true,
          '/test/example' => false,
        ],
      ],
      [
        // Multiple paths with the \r delimiter.
        "/user/*\r/example/*",
        [
          '/user/1' => true,
          '/example/1' => true,
          '/user/1/example/1' => true,
          '/user/example' => true,
          '/test/example' => false,
          '/user' => false,
          '/example' => false,
        ],
      ],
      [
        // Multiple paths with the \r\n delimiter.
        "/test\r\n<front>",
        [
          '/test' => true,
          '/dummy' => true,
          '/example' => false,
        ],
      ],
      [
        // Test existing regular expressions (should be escaped).
        '[^/]+?/[0-9]',
        [
          '/test/1' => false,
          '[^/]+?/[0-9]' => true,
        ],
      ],
    ];
    }
}
