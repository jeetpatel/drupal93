<?php

namespace Drupal\FunctionalTests\Hal;

use Drupal\FunctionalTests\Rest\EntityViewModeResourceTestBase;
use Drupal\Tests\rest\Functional\AnonResourceTestTrait;

/**
 * @group hal
 */
class EntityViewModeHalJsonAnonTest extends EntityViewModeResourceTestBase
{
    use AnonResourceTestTrait;

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
}
