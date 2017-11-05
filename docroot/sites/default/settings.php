<?php

/**
 * @file
 * Drupal site-specific configuration file.
 */

/**
 * Include default configuration.
 */
require_once DRUPAL_ROOT . '/sites/default/default.settings.php';

/**
 * Custom hash salt for encryption.
 */
$settings['hash_salt'] = 'V5gQ-kgIx4K7KoSggL3fQBVHFf219yKrm7kBsFkdWMoLe5cuL9EnYddFLjzjwA1YRPJwfS2Fmw';

/**
 * Fast 404 configuration.
 */
$config['system.performance']['fast_404']['exclude_paths'] = '/\/(?:styles)|(?:system\/files)\//';
$config['system.performance']['fast_404']['paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$config['system.performance']['fast_404']['html'] = '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

/**
 * Translations directory.
 */
//$config['locale.settings']['translation.path'] = $settings['file_public_path'] . '/translations';

/**
 * Override default configuration path.
 */
$config_directories[CONFIG_SYNC_DIRECTORY] = '../config/sync';

/**
 * Load local development override configuration, if available.
 *
 * Use settings.local.php to override variables on secondary (staging,
 * development, etc) installations of this site. Typically used to disable
 * caching, JavaScript/CSS compression, re-routing of outgoing emails, and
 * other things that should not happen on development and testing sites.
 *
 * Keep this code block at the end of this file to take full effect.
 */
$site_environment = getenv('AH_SITE_ENVIRONMENT');
$site_environment = !empty($site_environment) ? $site_environment : 'local';

$env_settings_path = DRUPAL_ROOT . '/sites/default/settings.' . $site_environment . '.php';
if (file_exists($env_settings_path)) {
  include $env_settings_path;
}

/**
 * Force "minimal" as installation profile.
 */
$settings['install_profile'] = 'minimal';

// Make sure Drush keeps working.
// Modified from function drush_verify_cli()
$cli = (php_sapi_name() == 'cli');
