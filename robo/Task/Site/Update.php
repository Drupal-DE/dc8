<?php

namespace DrupalCenter\Robo\Task\Site;

use Robo\Common\BuilderAwareTrait;
use Robo\Common\IO;
use Robo\Contract\BuilderAwareInterface;
use Robo\Task\BaseTask;

/**
 * Robo task base: Update site.
 */
class Update extends BaseTask implements BuilderAwareInterface {

  use BuilderAwareTrait;
  use IO;

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

    // Set up filesystem.
    $collection->addTask($this->collectionBuilder()->taskSiteSetupFileSystem($this->environment));

    $collection->addTaskList([
      // Clear all caches.
      'Update.cacheRebuild' => $this->collectionBuilder()->taskDrushCacheRebuild(),
      // Update profile information.
      'Update.drushConfigUpdateProfileHack' => $this->collectionBuilder()->taskDrushConfigUpdateProfileHack(),
      // Import configuration.
      'Update.drushConfigImport' => $this->collectionBuilder()->taskDrushFoodConfigImport(),
      // Apply database updates.
      'Update.applyDatabaseUpdates' => $this->collectionBuilder()->taskDrushApplyDatabaseUpdates(),
      // Clear all caches (again).
      'Update.cacheRebuildAgain' => $this->collectionBuilder()->taskDrushCacheRebuild(),
      // Import configuration (again, to ensure no stale configuration updates).
      'Update.drushConfigImportAgain' => $this->collectionBuilder()->taskDrushFoodConfigImport(),
      // Apply entity schema updates.
      'Update.applyEntitySchemaUpdates' => $this->collectionBuilder()->taskDrushEntitySchemaUpdates(),
      // Clear all caches (again).
      'Update.cacheRebuildAnotherTime' => $this->collectionBuilder()->taskDrushCacheRebuild(),
    ]);

    // Skip localization update if no-locale command options is not set.
    if (empty($this->no_locale_update)) {
      $collection->addTaskList([
        // Update translations.
        'Install.localeUpdate' => $this->collectionBuilder()->taskDrushLocaleUpdate(),
      ]);
    }
    else {
      $this->say('Locale update skipped due to "--no-locale" being set.');
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
