<?php

namespace Drupal\FunctionalTests\Hal;

use Drupal\Tests\rest\Functional\BasicAuthResourceTestTrait;

/**
 * @group hal
 */
class EntityViewDisplayHalJsonBasicAuthTest extends EntityViewDisplayHalJsonAnonTest
{
    use BasicAuthResourceTestTrait;

    /**
     * {@inheritdoc}
     */
    protected static $modules = ['basic_auth'];

    /**
     * {@inheritdoc}
     */
    protected $defaultTheme = 'stark';

    /**
     * {@inheritdoc}
     */
    protected static $auth = 'basic_auth';
}
