<?php

/**
 * Redirect
 * 
 * Helps redirecting to various places in your app
 * Combined with custom handlers of URL::to, this can be really smart and handy
 * 
 * @package   Kirby Toolkit 
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Redirect {

  /**
   * Redirects the user to a new URL
   *
   * @param   string    $url The URL to redirect to
   * @param   boolean   $code The HTTP status code, which should be sent (301, 302 or 303)
   * @param   boolean   $send If true, headers will be sent and redirection will take effect
   */
  public static function send($url = false, $code = false, $send = true) {
    return header::redirect($url, $code, $send);
  }

  /**
   * Redirects to a specific URL. You can pass either a normal URI
   * a controller path or simply nothing (which redirects home)
   */
  public static function to() {
    static::send(call_user_func_array(array('url', 'to'), func_get_args()));
  }

  /**
   * Redirects to the home page of the app
   */
  public static function home() {
    static::send(url::home());
  }

  /**
   * Redirects to the last location of the user
   * 
   * @param string $fallback
   */
  public static function back($fallback = null) {
    // get the last url
    $last = url::last();
    // make sure there's a proper fallback
    if(empty($last)) $last = $fallback ? $fallback : url::home();
    static::send($last);
  }

}