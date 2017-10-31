<?php

namespace DrupalCenter\Robo\Task\FileSystem;

trait loadTasks {

  /**
   * Ensure private files directory.
   *
   * @param string $environment
   *   An environment string.
   *
   * @return EnsurePrivateFilesDirectory
   */
  protected function taskFileSystemEnsurePrivateFilesDirectory($environment) {
    return $this->task(EnsurePrivateFilesDirectory::class, $environment);
  }

  /**
   * Ensure public files directory.
   *
   * @param string $environment
   *   An environment string.
   *
   * @return EnsurePublicFilesDirectory
   */
  protected function taskFileSystemEnsurePublicFilesDirectory($environment) {
    return $this->task(EnsurePublicFilesDirectory::class, $environment);
  }

  /**
   * Ensure temporary files directory.
   *
   * @param string $environment
   *   An environment string.
   *
   * @return EnsureTemporaryFilesDirectory
   */
  protected function taskFileSystemEnsureTemporaryFilesDirectory($environment) {
    return $this->task(EnsureTemporaryFilesDirectory::class, $environment);
  }

  /**
   * Ensure translation files directory.
   *
   * @param string $environment
   *   An environment string.
   *
   * @return EnsureTranslationFilesDirectory
   */
  protected function taskFileSystemEnsureTranslationFilesDirectory($environment) {
    return $this->task(EnsureTranslationFilesDirectory::class, $environment);
  }

}
