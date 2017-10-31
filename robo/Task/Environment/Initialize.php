<?php

namespace DrupalCenter\Robo\Task\Environment;

use Robo\Common\IO;
use Robo\Result;
use Robo\Task\BaseTask;
use DrupalCenter\Robo\Utility\Environment;
use DrupalCenter\Robo\Utility\PathResolver;

/**
 * Robo task base: Initialize Environment.
 */
class Initialize extends BaseTask {

  use IO;

  /**
   * Environment.
   *
   * @var string
   */
  protected $environment;

  /**
   * Initialize constructor.
   *
   * @param string $environment
   *  An environment string
   */
  public function __construct($environment) {
    $this->environment = $environment;
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    Environment::set($this->environment);

    if (Environment::isDevdesktop()) $this->ensureDevdesktopPath();

    return Result::success($this);
  }

  /**
   * Ensures that the path to Acquia DevDesktop is set correctly
   */
  public function ensureDevdesktopPath() {
    $path = PathResolver::getDevdesktopPath();
    while (!file_exists($path)) {
      $path = $this->ask('Path to DevDesktop');
      if ($path == "") {
        $this->say('WARNING: Will use default binarys!');
        $path = NULL;
        break;
      }
    }
    PathResolver::setDevdesktopPath($path);
  }

}
