<?php

namespace DrupalCenter\Robo\Utility;

/**
 * A helper class for environments.
 */
class Environment {

  /**
   * Environment: local.
   */
  const LOCAL = 'local';

  /**
   * Environment: travis.
   */
  const TRAVIS = 'travis';

  /**
   * Environment: devdesktop
   */
  const DEVDESKTOP = 'devdesktop';


  /**
   * @var string Environment
   */
  private static $environment;

  /**
   * Sets the environment statically
   *
   * @param string $environment
   *  The environment
   */
  public static function set($environment) {
    self::$environment = $environment;
  }

  /**
   * Gets the statically saved environment
   *
   * @return string The environment
   */
  public static function get() {
    return self::$environment;
  }

  /**
   * Detect environment identifier from environment variable.
   *
   * @return string|null
   *   The environment identifier on success, otherwise NULL.
   */
  public static function detect() {
    $environment = getenv('AH_SITE_ENVIRONMENT');

    return $environment ?: NULL;
  }

  /**
   * Is Acquia environment?
   *
   * @param string $environment
   *   An environment string.
   *
   * @return bool
   *   Whether the environment is an Acquia server or not.
   */
  public static function isAcquia($environment) {
    return $environment && !in_array($environment, [
      static::LOCAL,
      static::TRAVIS,
      static::DEVDESKTOP
    ]);
  }

  /**
   * Is DevDesktop environment?
   *
   * @return bool
   *  Whether the environment is set to Acquia DevDesktop
   */
  public static function isDevdesktop() {
    return self::$environment === self::DEVDESKTOP;
  }

  /**
   * Is valid environment?
   *
   * @param string $environment
   *   An environment string.
   *
   * @return bool
   *   Whether the environment is valid or not.
   */
  public static function isValid($environment) {
    return $environment && ($environment === static::LOCAL || $environment == static::DEVDESKTOP || file_exists(PathResolver::siteDirectory() . '/settings.' . $environment . '.php'));
  }

  /**
   * Needs building?
   *
   * @param $environment
   *   An environment string.
   *
   * @return bool
   *   Whether the environment has to perform builds (e.g. run 'composer install').
   */
  public static function needsBuild($environment) {
    return $environment && static::isValid($environment) && !static::isAcquia($environment);
  }

}
