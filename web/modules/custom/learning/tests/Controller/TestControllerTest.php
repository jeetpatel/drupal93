<?php

namespace Drupal\learning\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides automated tests for the learning module.
 */
class TestControllerTest extends WebTestBase {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "learning TestController's controller functionality",
      'description' => 'Test Unit for module learning and controller TestController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests learning functionality.
   */
  public function testTestController() {
    // Check that the basic functions of module learning.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
