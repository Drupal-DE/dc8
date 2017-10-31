<?php

namespace DrupalCenter\Robo\Task\Environment;

trait loadTasks {

  /**
   * Initialize environment
   *
   * @param string $environment
   *    An environemnt string
   * @return Initialize
   */
  protected function taskEnvironmentInitialize($environment) {
    return $this->task(Initialize::class, $environment);
  }
}