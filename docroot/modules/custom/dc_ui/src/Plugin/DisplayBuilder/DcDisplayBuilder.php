<?php

namespace Drupal\dc_ui\Plugin\DisplayBuilder;

use Drupal\panels\Plugin\DisplayBuilder\StandardDisplayBuilder;

/**
 * The standard display builder for viewing a PanelsDisplayVariant.
 *
 * @DisplayBuilder(
 *   id = "dc_display_builder",
 *   label = @Translation("DC display builder")
 * )
 */
class DcDisplayBuilder extends StandardDisplayBuilder {

  /**
   * {@inheritdoc}
   */
  protected function buildRegions(array $regions, array $contexts) {
    $build = parent::buildRegions($regions, $contexts);

    foreach ($build as $name => &$region) {
      // Remove '#prefix' with obsolete markup.
      if (isset($region['#prefix'])) {
        unset($region['#prefix']);
      }

      // Remove '#suffix' with obsolete markup.
      if (isset($region['#suffix'])) {
        unset($region['#suffix']);
      }
    }

    return $build;
  }

}
