<?php

/**
 * @file
 * Contains \Drupal\Tests\Core\Authentication\AuthenticationManagerTest.
 */

namespace Drupal\Tests\Core\Authentication;

use Drupal\Core\Authentication\AuthenticationCollector;
use Drupal\Core\Authentication\AuthenticationManager;
use Drupal\Core\Authentication\AuthenticationProviderFilterInterface;
use Drupal\Core\Authentication\AuthenticationProviderInterface;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

/**
 * @coversDefaultClass \Drupal\Core\Authentication\AuthenticationManager
 * @group Authentication
 */
class AuthenticationManagerTest extends UnitTestCase
{
  /**
   * @covers ::defaultFilter
   * @covers ::applyFilter
   *
   * @dataProvider providerTestDefaultFilter
   */
    public function testDefaultFilter($applies, $has_route, $auth_option, $provider_id, $global)
    {
        $auth_provider = $this->createMock('Drupal\Core\Authentication\AuthenticationProviderInterface');
        $auth_collector = new AuthenticationCollector();
        $auth_collector->addProvider($auth_provider, $provider_id, 0, $global);
        $authentication_manager = new AuthenticationManager($auth_collector);

        $request = new Request();
        if ($has_route) {
            $route = new Route('/example');
            if ($auth_option) {
                $route->setOption('_auth', $auth_option);
            }
            $request->attributes->set(RouteObjectInterface::ROUTE_OBJECT, $route);
        }

        $this->assertSame($applies, $authentication_manager->appliesToRoutedRequest($request, false));
    }

    /**
     * @covers ::applyFilter
     */
    public function testApplyFilterWithFilterprovider()
    {
        $auth_provider = $this->createMock('Drupal\Tests\Core\Authentication\TestAuthenticationProviderInterface');
        $auth_provider->expects($this->once())
      ->method('appliesToRoutedRequest')
      ->willReturn(true);

        $authentication_collector = new AuthenticationCollector();
        $authentication_collector->addProvider($auth_provider, 'filtered', 0);

        $authentication_manager = new AuthenticationManager($authentication_collector);

        $request = new Request();
        $this->assertTrue($authentication_manager->appliesToRoutedRequest($request, false));
    }

    /**
     * Provides data to self::testDefaultFilter().
     */
    public function providerTestDefaultFilter()
    {
        $data = [];
        // No route, cookie is global, should apply.
        $data[] = [true, false, [], 'cookie', true];
        // No route, cookie is not global, should not apply.
        $data[] = [false, false, [], 'cookie', false];
        // Route, no _auth, cookie is global, should apply.
        $data[] = [true, true, [], 'cookie', true];
        // Route, no _auth, cookie is not global, should not apply.
        $data[] = [false, true, [], 'cookie', false];
        // Route, with _auth and non-matching provider, should not apply.
        $data[] = [false, true, ['basic_auth'], 'cookie', true];
        // Route, with _auth and matching provider should not apply.
        $data[] = [true, true, ['basic_auth'], 'basic_auth', true];
        return $data;
    }
}

/**
 * Helper interface to mock two interfaces at once.
 */
interface TestAuthenticationProviderInterface extends AuthenticationProviderFilterInterface, AuthenticationProviderInterface
{
}
