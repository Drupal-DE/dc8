<?php

namespace DrupalCenter\Robo\Task\DatabaseDump;

use Robo\Robo;
use DrupalCenter\Robo\Utility\Drush;

/**
 * Robo task: Export database dump.
 */
class Export extends Dump {

  /**
   * {@inheritdoc}
   */
  public function run() {
    /** @var Drush $drush */
    $drush = Robo::getContainer()->get('drush');
    return $drush->exec()
      ->arg('sql-dump')
      ->rawArg('>')
      ->arg($this->filepath)
      ->option('ordered-dump')
      ->option('extra=--skip-comments')
      ->option('structure-tables-list=' . implode(',', $this->getStructureOnlyTableList()))
      ->run();
  }

  /**
   * Return structure-only database table names.
   *
   * This is used to make the exported file as small as possible. All returned
   * database table names indicate to only export their structure but not data
   * rows.
   *
   * @return array
   *   An array of database table names.
   */
  protected function getStructureOnlyTableList() {
    $tables = [
      'cache_*',
      'cachetags',
      'watchdog',
      'sessions',
    ];

    return $tables;
  }

}
