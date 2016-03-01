<?php

/**
 * Visitor
 *
 * Gives some handy information about the current visitor
 *
 * @package   Kirby Toolkit
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Visitor {

  // banned ips
  public static $banned = array();

  // cache for the detected language code
  protected static $acceptedLanguageCode = null;

  /**
   * Returns the ip address of the current visitor
   *
   * @return string
   */
  public static function ip() {
    return getenv('REMOTE_ADDR');
  }

  /**
   * Returns the user agent string of the current visitor
   *
   * @return string
   */
  public static function ua() {
    return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
  }

  /**
   * A more readable but longer alternative for ua()
   *
   * @return string
   */
  public static function userAgent() {
    return static::ua();
  }

  /**
   * Returns the user's accepted language
   *
   * @return string
   */
  public static function acceptedLanguage() {
    return isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
  }

  /**
   * Returns the user's accepted language code
   *
   * @return string
   */
  public static function acceptedLanguageCode() {
    if(!is_null(static::$acceptedLanguageCode)) return static::$acceptedLanguageCode;
    $detected = explode(',', static::acceptedLanguage());
    $detected = explode('-', $detected[0]);
    return static::$acceptedLanguageCode = strtolower($detected[0]);
  }

  /**
   * Returns the referrer if available
   *
   * @return string
   */
  public static function referrer() {
    return r::referer();
  }

  /**
   * Nobody can remember if it is written with on or two r
   *
   * @return string
   */
  public static function referer() {
    return r::referer();
  }

  /**
   * Checks if the ip of the current visitor is banned
   *
   * @return boolean
   */
  public static function banned() {
    return in_array(static::ip(), static::$banned);
  }

}