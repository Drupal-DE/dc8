<?php

namespace DrupalCenter\Robo\Utility;

use Robo\Collection\CollectionBuilder;
use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use Robo\Task\Base\Exec;

/**
 * A helper class for Drush command execution.
 */
class Drush implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /** @var \Robo\Collection\CollectionBuilder  */
  protected $collectionBuilder;

  /**
   * Drush constructor.
   *
   * @param CollectionBuilder $collectionBuilder
   */
  public function __construct($collectionBuilder) {
    $this->setBuilder($collectionBuilder);
  }


  /**
   * Return Drush executable.
   *
   * @return \Robo\Task\Base\Exec
   */
  public function exec() {
    $execCollection = $this->collectionBuilder()->build(Exec::class, [PathResolver::drush()]);

    $exec = $execCollection
      // Set working directory to docroot.
      ->dir(PathResolver::docroot());

    return $exec;
  }

}
