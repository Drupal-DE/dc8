<?php

namespace DrupalCenter\Robo\Task\Site;

use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use DrupalCenter\Robo\Task\FileSystem\EnsurePrivateFilesDirectory;
use DrupalCenter\Robo\Task\FileSystem\EnsurePublicFilesDirectory;
use DrupalCenter\Robo\Task\FileSystem\EnsureTemporaryFilesDirectory;
use DrupalCenter\Robo\Task\FileSystem\EnsureTranslationFilesDirectory;
use Robo\Collection\Collection;
use Robo\Task\BaseTask;

/**
 * Robo task base: Set up file system.
 */
class SetupFileSystem extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Environment.
   *
   * @var string
   */
  protected $environment;

  /**
   * Constructor.
   *
   * @param string $environment
   *   An environment string.
   */
  public function __construct($environment) {
    $this->environment = $environment;
  }

  /**
   * Return task collection for this task.
   *
   * @return \Robo\Collection\Collection
   *   The task collection.
   */
  public function collection() {
    $collection = $this->collectionBuilder();

    $collection->addTaskList([
      // Ensure private files directory.
      'Setup.ensurePrivateFilesDirectory' => $this->collectionBuilder()->taskFileSystemEnsurePrivateFilesDirectory($this->environment),
      // Ensure public files directory.
      'Setup.ensurePublicFilesDirectory' => $this->collectionBuilder()->taskFileSystemEnsurePublicFilesDirectory($this->environment),
      // Ensure temporary files directory.
      'Setup.ensureTemporaryFilesDirectory' => $this->collectionBuilder()->taskFileSystemEnsureTemporaryFilesDirectory($this->environment),
      // Ensure translation files directory.
      'Setup.ensureTranslationFilesDirectory' => $this->collectionBuilder()->taskFileSystemEnsureTranslationFilesDirectory($this->environment),
    ]);

    return $collection->original();
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    return $this->collection()->run();
  }

}
