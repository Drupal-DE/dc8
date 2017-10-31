<?php

namespace DrupalCenter\Robo\Task\Site;

use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use DrupalCenter\Robo\Utility\Environment;
use Robo\Task\BaseTask;
use DrupalCenter\Robo\Utility\PathResolver;

/**
 * Robo task base: Initialize site.
 */
class Initialize extends BaseTask implements BuilderAwareInterface {

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

    // Is valid environment?
    if (!Environment::isValid($this->environment)) {
      throw new \InvalidArgumentException(get_class($this) . ' - Unknown environment: ' . $this->environment);
    }
  }

  /**
   * Return task collection for this task.
   *
   * @return \Robo\Collection\Collection
   *   The task collection.
   */
  public function collection() {
    $collection = $this->collectionBuilder();

    // Build has to be performed?
    if (Environment::needsBuild($this->environment)) {
      $collection->taskComposerInstall()
        ->dir(PathResolver::root())
        ->option('optimize-autoloader');
    }

    $collection->addTaskList([
      'Initialize.initializeEnvironment' => $this->collectionBuilder()->taskEnvironmentInitialize($this->environment),
      // Ensure settings file for environment.
      'Initialize.ensureSettingsFile' => $this->collectionBuilder()->taskSettingsEnsureSettingsFile($this->environment),
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
