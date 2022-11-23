<?php

namespace Drupal\KernelTests\Core\Installer;

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Database;
use Drupal\Core\Database\DatabaseExceptionWrapper;
use Drupal\Core\Database\DatabaseNotFoundException;
use Drupal\Core\Database\Schema;
use Drupal\Core\Installer\InstallerRedirectTrait;
use Drupal\KernelTests\KernelTestBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @coversDefaultClass \Drupal\Core\Installer\InstallerRedirectTrait
 *
 * @group Installer
 */
class InstallerRedirectTraitTest extends KernelTestBase
{
  /**
   * Data provider for testShouldRedirectToInstaller().
   *
   * @return array
   *   - Expected result from shouldRedirectToInstaller().
   *   - Exceptions to be handled by shouldRedirectToInstaller()
   *   - Whether or not there is a database connection.
   *   - Whether or not there is database connection info.
   *   - Whether or not there exists a sessions table in the database.
   */
    public function providerShouldRedirectToInstaller()
    {
        return [
      [true, DatabaseNotFoundException::class, false, false],
      [true, DatabaseNotFoundException::class, true, false],
      [true, DatabaseNotFoundException::class, false, true],
      [true, DatabaseNotFoundException::class, true, true],
      [true, DatabaseNotFoundException::class, true, true, false],

      [true, \PDOException::class, false, false],
      [true, \PDOException::class, true, false],
      [false, \PDOException::class, false, true],
      [false, \PDOException::class, true, true],
      [true, \PDOException::class, true, true, false],

      [true, DatabaseExceptionWrapper::class, false, false],
      [true, DatabaseExceptionWrapper::class, true, false],
      [false, DatabaseExceptionWrapper::class, false, true],
      [false, DatabaseExceptionWrapper::class, true, true],
      [true, DatabaseExceptionWrapper::class, true, true, false],

      [true, NotFoundHttpException::class, false, false],
      [true, NotFoundHttpException::class, true, false],
      [false, NotFoundHttpException::class, false, true],
      [false, NotFoundHttpException::class, true, true],
      [true, NotFoundHttpException::class, true, true, false],

      [false, \Exception::class, false, false],
      [false, \Exception::class, true, false],
      [false, \Exception::class, false, true],
      [false, \Exception::class, true, true],
      [false, \Exception::class, true, true, false],
    ];
    }

    /**
     * @covers ::shouldRedirectToInstaller
     * @dataProvider providerShouldRedirectToInstaller
     */
    public function testShouldRedirectToInstaller($expected, $exception, $connection, $connection_info, $session_table_exists = true)
    {
        try {
            throw new $exception();
        } catch (\Exception $e) {
            // Mock the trait.
            $trait = $this->getMockBuilder(InstallerRedirectTrait::class)
        ->onlyMethods(['isCli'])
        ->getMockForTrait();

            // Make sure that the method thinks we are not using the cli.
            $trait->expects($this->any())
        ->method('isCli')
        ->willReturn(false);

            // Un-protect the method using reflection.
            $method_ref = new \ReflectionMethod($trait, 'shouldRedirectToInstaller');
            $method_ref->setAccessible(true);

            // Mock the database connection info.
            $db = $this->getMockForAbstractClass(Database::class);
            $property_ref = new \ReflectionProperty($db, 'databaseInfo');
            $property_ref->setAccessible(true);
            $property_ref->setValue($db, ['default' => $connection_info]);

            if ($connection) {
                // Mock the database connection.
                $connection = $this->getMockBuilder(Connection::class)
          ->disableOriginalConstructor()
          ->onlyMethods(['schema'])
          ->getMockForAbstractClass();

                if ($connection_info) {
                    // Mock the database schema class.
                    $schema = $this->getMockBuilder(Schema::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['tableExists'])
            ->getMockForAbstractClass();

                    $schema->expects($this->any())
            ->method('tableExists')
            ->with('sessions')
            ->willReturn($session_table_exists);

                    $connection->expects($this->any())
            ->method('schema')
            ->willReturn($schema);
                }
            } else {
                // Set the database connection if there is none.
                $connection = null;
            }

            // Call shouldRedirectToInstaller.
            $this->assertSame($expected, $method_ref->invoke($trait, $e, $connection));
        }
    }
}
