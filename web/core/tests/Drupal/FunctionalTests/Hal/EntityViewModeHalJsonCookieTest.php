<?php

namespace Drupal\FunctionalTests\Hal;

use Drupal\FunctionalTests\Rest\EntityViewModeResourceTestBase;
use Drupal\Tests\rest\Functional\CookieResourceTestTrait;

/**
 * @group hal
 */
class EntityViewModeHalJsonCookieTest extends EntityViewModeResourceTestBase
{
    use CookieResourceTestTrait;

    /**
     * {@inheritdoc}
     */
    protected static $modules = ['hal'];

    /**
     * {@inheritdoc}
     */
    protected $defaultTheme = 'stark';

    /**
     * {@inheritdoc}
     */
    protected static $format = 'hal_json';

    /**
     * {@inheritdoc}
     */
    protected static $mimeType = 'application/hal+json';

    /**
     * {@inheritdoc}
     */
    protected static $auth = 'cookie';
}
