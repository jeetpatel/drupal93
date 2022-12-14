<?php

namespace Drupal\Tests\Core\Http;

use Drupal\Core\Http\ClientFactory;
use Drupal\Core\Site\Settings;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\Core\Http\ClientFactory
 * @group Http
 */
class ClientFactoryTest extends UnitTestCase
{
  /**
   * The client factory under test.
   *
   * @var \Drupal\Core\Http\ClientFactory
   */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $stack = $this->getMockBuilder('GuzzleHttp\HandlerStack')
      ->disableOriginalConstructor()
      ->getMock();
        $this->factory = new ClientFactory($stack);
    }

    /**
     * @covers ::fromOptions
     * @dataProvider providerTestCreateFromOptions
     *
     * @param array $settings_config
     * @param array $parameter_config
     * @param array $expected_config_keys
     */
    public function testCreateFromOptions($settings_config, $parameter_config, $expected_config_keys)
    {
        if ($settings_config) {
            new Settings(['http_client_config' => $settings_config]);
        } else {
            new Settings([]);
        }

        $client = $this->factory->fromOptions($parameter_config);

        foreach ($expected_config_keys as $key => $expected) {
            $this->assertSame($expected, $client->getConfig($key));
        }
    }

    /**
     * Data provider for testCreateFromOptions.
     *
     * @return array
     */
    public function providerTestCreateFromOptions()
    {
        return [
      [[], [], ['verify' => true, 'timeout' => 30]],
      [['timeout' => 40], [], ['verify' => true, 'timeout' => 40]],
      [[], ['timeout' => 50], ['verify' => true, 'timeout' => 50]],
      [['timeout' => 40], ['timeout' => 50], ['verify' => true, 'timeout' => 50]],
    ];
    }
}
