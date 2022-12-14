<?php

namespace Drupal\Core\File;

use Drupal\Component\FileSecurity\FileSecurity;
use Drupal\Core\Site\Settings;
use Drupal\Core\StreamWrapper\PrivateStream;
use Drupal\Core\StreamWrapper\StreamWrapperManager;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Provides functions to manage Apache .htaccess files.
 */
class HtaccessWriter implements HtaccessWriterInterface
{
  /**
   * The stream wrapper manager.
   *
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface
   */
    protected $streamWrapperManager;

    /**
     * The logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Htaccess constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *   The logger.
     * @param \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $stream_wrapper_manager
     *   The stream wrapper manager.
     */
    public function __construct(LoggerInterface $logger, StreamWrapperManagerInterface $stream_wrapper_manager)
    {
        $this->logger = $logger;
        $this->streamWrapperManager = $stream_wrapper_manager;
    }

    /**
     * {@inheritdoc}
     */
    public function ensure()
    {
        try {
            foreach ($this->defaultProtectedDirs() as $protected_dir) {
                $this->write($protected_dir->getPath(), $protected_dir->isPrivate());
            }

            $staging = Settings::get('config_sync_directory', false);
            if ($staging) {
                // Note that we log an error here if we can't write the .htaccess file.
                // This can occur if the staging directory is read-only. If it is then
                // it is the user's responsibility to create the .htaccess file.
                $this->write($staging, true);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Creates a .htaccess file in the given directory.
     *
     * @param string $directory
     *   The directory.
     * @param bool $deny_public_access
     *   (Optional) FALSE indicates that $directory should be a web-accessible
     *   directory. Defaults to TRUE which indicates a private directory.
     * @param bool $force_overwrite
     *   (Optional) Set to TRUE to attempt to overwrite the existing .htaccess
     *   file if one is already present. Defaults to FALSE.
     *
     * @internal
     *
     * @return bool
     *   TRUE if the .htaccess file was saved or already exists, FALSE otherwise.
     *
     * @see \Drupal\Component\FileSecurity\FileSecurity::writeHtaccess()
     */
    public function write($directory, $deny_public_access = true, $force_overwrite = false)
    {
        if (StreamWrapperManager::getScheme($directory)) {
            $directory = $this->streamWrapperManager->normalizeUri($directory);
        } else {
            $directory = rtrim($directory, '/\\');
        }

        if (FileSecurity::writeHtaccess($directory, $deny_public_access, $force_overwrite)) {
            return true;
        }

        $this->logger->error("Security warning: Couldn't write .htaccess file. Please create a .htaccess file in your %directory directory which contains the following lines: <pre><code>@htaccess</code></pre>", ['%directory' => $directory, '@htaccess' => FileSecurity::htaccessLines($deny_public_access)]);
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function defaultProtectedDirs()
    {
        $protected_dirs[] = new ProtectedDirectory('Public files directory', 'public://');
        if (PrivateStream::basePath()) {
            $protected_dirs[] = new ProtectedDirectory('Private files directory', 'private://', true);
        }
        $protected_dirs[] = new ProtectedDirectory('Temporary files directory', 'temporary://');
        return $protected_dirs;
    }
}
