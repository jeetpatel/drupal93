<?php

namespace Drupal\FunctionalTests\Hal;

use Drupal\FunctionalTests\Rest\EntityFormModeResourceTestBase;
use Drupal\Tests\rest\Functional\CookieResourceTestTrait;

/**
 * @group hal
 */
class EntityFormModeHalJsonCookieTest extends EntityFormModeResourceTestBase
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
