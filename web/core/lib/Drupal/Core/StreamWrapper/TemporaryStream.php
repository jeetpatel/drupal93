<?php

namespace Drupal\Core\StreamWrapper;

use Drupal\Core\Url;

/**
 * Defines a Drupal temporary (temporary://) stream wrapper class.
 *
 * Provides support for storing temporarily accessible files with the Drupal
 * file interface.
 */
class TemporaryStream extends LocalStream
{
  /**
   * {@inheritdoc}
   */
    public static function getType()
    {
        return StreamWrapperInterface::LOCAL_HIDDEN;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return t('Temporary files');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return t('Temporary local files for upload and previews.');
    }

    /**
     * {@inheritdoc}
     */
    public function getDirectoryPath()
    {
        return \Drupal::service('file_system')->getTempDirectory();
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalUrl()
    {
        $path = str_replace('\\', '/', $this->getTarget());
        return Url::fromRoute('system.temporary', [], ['absolute' => true, 'query' => ['file' => $path]])->toString();
    }
}
