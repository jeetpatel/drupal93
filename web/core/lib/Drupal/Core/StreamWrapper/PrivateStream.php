<?php

namespace Drupal\Core\StreamWrapper;

use Drupal\Core\Site\Settings;
use Drupal\Core\Url;

/**
 * Drupal private (private://) stream wrapper class.
 *
 * Provides support for storing privately accessible files with the Drupal file
 * interface.
 */
class PrivateStream extends LocalStream
{
  /**
   * {@inheritdoc}
   */
    public static function getType()
    {
        return StreamWrapperInterface::LOCAL_NORMAL;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return t('Private files');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return t('Private local files served by Drupal.');
    }

    /**
     * {@inheritdoc}
     */
    public function getDirectoryPath()
    {
        return static::basePath();
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalUrl()
    {
        $path = str_replace('\\', '/', $this->getTarget());
        return Url::fromRoute('system.private_file_download', ['filepath' => $path], ['absolute' => true, 'path_processing' => false])->toString();
    }

    /**
     * Returns the base path for private://.
     *
     * Note that this static method is used by \Drupal\system\Form\FileSystemForm
     * so you should alter that form or substitute a different form if you change
     * the class providing the stream_wrapper.private service.
     *
     * @return string
     *   The base path for private://.
     */
    public static function basePath()
    {
        return Settings::get('file_private_path');
    }
}
