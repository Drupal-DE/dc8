<?php

namespace DrupalCenter\Robo\Task\Site;

use Robo\Common\BuilderAwareTrait;
use Robo\Contract\BuilderAwareInterface;
use DrupalCenter\Robo\Task\DatabaseDump\Export;
use DrupalCenter\Robo\Task\DatabaseDump\Import;
use DrupalCenter\Robo\Task\Drush\CacheRebuild;
use DrupalCenter\Robo\Task\Drush\ConfigExport;
use DrupalCenter\Robo\Task\Drush\EnableExtension;
use DrupalCenter\Robo\Task\Drush\LocaleUpdate;
use DrupalCenter\Robo\Task\Drush\SiteInstall;
use DrupalCenter\Robo\Task\Drush\SqlDrop;
use DrupalCenter\Robo\Task\Drush\UserLogin;
use DrupalCenter\Robo\Utility\PathResolver;
use Robo\Collection\Collection;
use Robo\Task\BaseTask;

/**
 * Robo task base: Install site.
 */
class Install extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;

  /**
   * Environment.
   *
   * @var string
   */
  protected $environment;

  /**
   * Whether to skip localization update.
   *
   * @var bool
   */
  protected $no_locale_update;

  /**
   * Constructor.
   *
   * @param string $environment
   *   An environment string.
   * @param bool $no_locale_update
   *   Whether to skip localization update (defaults to FALSE).
   */
  public function __construct($environment, $no_locale_update = FALSE) {
    $this->environment = $environment;
    $this->no_locale_update = $no_locale_update;
  }

  /**
   * Return task collection for this task.
   *
   * @return \Robo\Collection\Collection
   *   The task collection.
   */
  public function collection() {
    $collection = $this->collectionBuilder();
    $dump = PathResolver::databaseDump();

    // No database dump file present -> perform initial installation, export
    // configuration and create database dump file.
    if (!file_exists($dump)) {
      $collection->addTaskList([
        // Install Drupal site.
        'Install.siteInstall' => new SiteInstall(),
      ]);

      // Set up file system.
      $collection->addTask($this->collectionBuilder()->taskSiteSetupFileSystem($this->environment));

      $collection->addTaskList([
        // Ensure 'config' and 'locale' module.
        'Install.enableExtensions' => $this->collectionBuilder()->taskDrushEnableExtension(['config', 'locale']),
        // Update translations.
        'Install.localeUpdate' => $this->collectionBuilder()->taskDrushLocaleUpdate(),
        // Rebuild caches.
        'Install.cacheRebuild' => $this->collectionBuilder()->taskDrushCacheRebuild(),
        // Export configuration.
        'Install.configExport' => $this->collectionBuilder()->taskDrushConfigExport(),
        // Export database dump file.
        'Install.databaseDumpExport' => $this->collectionBuilder()->taskDatabaseDumpExport($dump),
      ]);
    }

    // Database dump file already exists -> import it and update database with
    // latest exported configuration (if any).
    else {
      $collection->addTaskList([
        // Drop all tables.
        'Install.sqlDrop' => $this->collectionBuilder()->taskDrushSqlDrop(),
        // Import database dump.
        'Install.databaseDumpImport' => $this->collectionBuilder()->taskDatabaseDumpImport($dump)
      ]);

      // Perform site update tasks
      $collection->addTask($this->collectionBuilder()->taskSiteUpdate($this->environment));
    }

    return $collection->original();
  }

  /**
   * {@inheritdoc}
   */
  public function run() {
    return $this->collection()->run();
  }

}
