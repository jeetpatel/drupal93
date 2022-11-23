<?php

namespace Drupal\learning\Services;

use Drupal\Core\File\FileSystemInterface;

/**
 * UploadExcelService service.
 */
class UploadExcelService {

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructs an UploadExcelService object.
   *
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(FileSystemInterface $file_system) {
    $this->fileSystem = $file_system;
  }

  /**
   * Method description.
   */
  public function upload() {
    // @DCG place your code here.
  }

}
