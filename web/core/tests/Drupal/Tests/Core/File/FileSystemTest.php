<?php

namespace Drupal\Tests\Core\File;

use Drupal\Core\File\Exception\FileException;
use Drupal\Core\File\FileSystem;
use Drupal\Core\Site\Settings;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Drupal\Tests\UnitTestCase;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \Drupal\Core\File\FileSystem
 *
 * @group File
 */
class FileSystemTest extends UnitTestCase
{
  /**
   * @var \Drupal\Core\File\FileSystemInterface
   */
    protected $fileSystem;

    /**
     * The file logger channel.
     *
     * @var \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $logger;

    /**
     * The stream wrapper manager.
     *
     * @var \Drupal\Core\StreamWrapper\StreamWrapperInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $streamWrapperManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $settings = new Settings([]);
        $this->streamWrapperManager = $this->createMock(StreamWrapperManagerInterface::class);
        $this->logger = $this->createMock('Psr\Log\LoggerInterface');
        $this->fileSystem = new FileSystem($this->streamWrapperManager, $settings, $this->logger);
    }

    /**
     * @covers ::chmod
     */
    public function testChmodFile()
    {
        vfsStream::setup('dir');
        vfsStream::create(['test.txt' => 'asdf']);
        $uri = 'vfs://dir/test.txt';

        $this->assertTrue($this->fileSystem->chmod($uri));
        $this->assertFilePermissions(FileSystem::CHMOD_FILE, $uri);
        $this->assertTrue($this->fileSystem->chmod($uri, 0444));
        $this->assertFilePermissions(0444, $uri);
    }

    /**
     * @covers ::chmod
     */
    public function testChmodDir()
    {
        vfsStream::setup('dir');
        vfsStream::create(['nested_dir' => []]);
        $uri = 'vfs://dir/nested_dir';

        $this->assertTrue($this->fileSystem->chmod($uri));
        $this->assertFilePermissions(FileSystem::CHMOD_DIRECTORY, $uri);
        $this->assertTrue($this->fileSystem->chmod($uri, 0444));
        $this->assertFilePermissions(0444, $uri);
    }

    /**
     * @covers ::chmod
     */
    public function testChmodUnsuccessful()
    {
        vfsStream::setup('dir');
        $this->logger->expects($this->once())
      ->method('error');
        $this->assertFalse($this->fileSystem->chmod('vfs://dir/test.txt'));
    }

    /**
     * @covers ::unlink
     */
    public function testUnlink()
    {
        vfsStream::setup('dir');
        vfsStream::create(['test.txt' => 'asdf']);
        $uri = 'vfs://dir/test.txt';

        $this->streamWrapperManager->expects($this->once())
      ->method('isValidUri')
      ->willReturn(true);

        $this->assertFileExists($uri);
        $this->fileSystem->unlink($uri);
        $this->assertFileDoesNotExist($uri);
    }

    /**
     * @covers ::basename
     *
     * @dataProvider providerTestBasename
     */
    public function testBasename($uri, $expected, $suffix = null)
    {
        $this->assertSame($expected, $this->fileSystem->basename($uri, $suffix));
    }

    public function providerTestBasename()
    {
        $data = [];
        $data[] = [
      'public://nested/dir',
      'dir',
    ];
        $data[] = [
      'public://dir/test.txt',
      'test.txt',
    ];
        $data[] = [
      'public://dir/test.txt',
      'test',
      '.txt',
    ];
        return $data;
    }

    /**
     * Asserts that the file permissions of a given URI matches.
     *
     * @param int $expected_mode
     * @param string $uri
     * @param string $message
     */
    protected function assertFilePermissions($expected_mode, $uri, $message = '')
    {
        // Mask out all but the last three octets.
        $actual_mode = fileperms($uri) & 0777;

        // PHP on Windows has limited support for file permissions. Usually each of
        // "user", "group" and "other" use one octal digit (3 bits) to represent the
        // read/write/execute bits. On Windows, chmod() ignores the "group" and
        // "other" bits, and fileperms() returns the "user" bits in all three
        // positions. $expected_mode is updated to reflect this.
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            // Reset the "group" and "other" bits.
            $expected_mode = $expected_mode & 0700;
            // Shift the "user" bits to the "group" and "other" positions also.
            $expected_mode = $expected_mode | $expected_mode >> 3 | $expected_mode >> 6;
        }
        $this->assertSame($expected_mode, $actual_mode, $message);
    }

    /**
     * Tests that invalid UTF-8 results in an exception.
     *
     * @covers ::createFilename
     */
    public function testInvalidUTF8()
    {
        vfsStream::setup('dir');
        $filename = "a\xFFsdf\x80€" . '.txt';
        $this->expectException(FileException::class);
        $this->expectExceptionMessage("Invalid filename '$filename'");
        $this->fileSystem->createFilename($filename, 'vfs://dir');
    }
}
