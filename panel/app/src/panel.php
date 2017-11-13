<?php

namespace Kirby;

use A;
use C;
use Collection;
use Detect;
use Dir;
use ErrorController;
use Exception;
use F;
use Header;
use Kirby;
use L;
use Obj;
use R;
use Response;
use Router;
use Server;
use S;
use Str;
use Toolkit;
use Tpl;
use Url;

use Kirby\Panel\Event;
use Kirby\Panel\ErrorHandling;
use Kirby\Panel\Installer;
use Kirby\Panel\Form;
use Kirby\Panel\Models\Site;
use Kirby\Panel\Translation;
use Kirby\Panel\Models\User\Blueprint as UserBlueprint;
use Kirby\Panel\Models\Page\Blueprint as PageBlueprint;

class Panel {

  static public $version = '2.5.7';

  // minimal requirements
  static public $requires = array(
    'php'     => '5.4.0',
    'toolkit' => '2.5.7',
    'kirby'   => '2.5.7'
  );

  static public $instance;

  public $kirby;
  public $site;
  public $path;
  public $roots;
  public $routes = array();
  public $router = null;
  public $route  = null;
  public $translation = null;
  public $translations = null;
  public $csrf = null;

  static public function instance() {
    return static::$instance;
  }

  static public function version() {
    return static::$version;
  }

  public function defaults() {

    return array(
      'panel.language'         => 'en',
      'panel.stylesheet'       => null,
      'panel.kirbytext'        => true,
      'panel.session.timeout'  => 1440,
      'panel.session.lifetime' => 0,
      'panel.info.license'     => true,
      'panel.info.versions'    => true,
      'panel.favicon'          => false,
      'panel.widgets'          => array(
        'pages'   => true,
        'site'    => true,
        'account' => true,
        'history' => true
      ),
    );

  }

  public function __construct($kirby, $root) {

    // check requirements
    $this->requirements();

    // store the instance as a singleton
    static::$instance = $this;

    // init the core
    $this->kirby = $kirby;

    // configure the site setup
    $this->site = $this->site();

    // store the roots and urls for the panel
    $this->roots = new \Kirby\Panel\Roots($this, $root);
    $this->urls  = new \Kirby\Panel\Urls($this, $root);

    // add the panel default options
    $this->kirby->options = array_merge($this->defaults(), $this->kirby->options);

    // setup the blueprints roots
    UserBlueprint::$root = $this->kirby->roots()->blueprints() . DS . 'users';
    PageBlueprint::$root = $this->kirby->roots()->blueprints();

    // setup the session
    $this->session();

    // load the current translation
    $this->translation()->load();

    // load all Kirby extensions (methods, tags, smartypants)
    $this->kirby->extensions();
    $this->kirby->plugins();

    // setup the multilang site stuff
    $this->multilang();

    // setup the form plugin
    form::$root = array(
      'default' => $this->roots->fields,
      'custom'  => $this->kirby->roots()->fields()
    );

    // force ssl if set in config
    if($this->kirby->option('ssl') and !r::secure()) {
      // rebuild the current url with https
      go(url::build(array('scheme' => 'https')));
    }

    // load all available routes
    $this->routes = array_merge($this->routes, require($this->roots->config . DS . 'routes.php'));

    // start the router
    $this->router = new Router($this->routes);

    // register router filters
    $this->router->filter('auth', function($route) use($kirby) {

      $panel = panel();

      try {
        $user = panel()->user();
      } catch(Exception $e) {
        panel()->redirect('login');
      }

      // check for area access
      if($area = $route->area()) {
        $panel->access($area)->check();
      }

    });

    // check for a completed installation
    $this->router->filter('isInstalled', function() use($kirby) {
      $installer = new Installer();
      if(!$installer->isCompleted()) {
        panel()->redirect('install');
      }
    });

    // check for valid csrf tokens. Can be used for get requests
    // since all post requests are blocked anyway
    $this->router->filter('csrf', function() {
      panel()->csrfCheck();
    });

    // csrf protection for every post request
    if(r::is('post')) {
      $this->csrfCheck();
    }

  }

  public function session() {

    // setup the session
    s::$timeout            = $this->kirby->option('panel.session.timeout', 120);
    s::$cookie['lifetime'] = $this->kirby->option('panel.session.lifetime', 0);

    // start the session
    s::start();

  }

  public function requirements() {

    if(!version_compare(PHP_VERSION, static::$requires['php'], '>=')) {
      throw new Exception('Your PHP version is too old. Please upgrade to ' . static::$requires['php'] . ' or newer.');
    }

    if(!detect::mbstring()) {
      throw new Exception('The mbstring extension must be installed');
    }

    if(!version_compare(toolkit::version(), static::$requires['toolkit'], '>=')) {
      throw new Exception('Your Toolkit version is too old. Please upgrade to ' . static::$requires['toolkit'] . ' or newer.');
    }

    if(!version_compare(kirby::version(), static::$requires['kirby'], '>=')) {
      throw new Exception('Your Kirby version is too old. Please upgrade to ' . static::$requires['kirby'] . ' or newer.');
    }

  }

  public function csrf() {

    if(!is_null($this->csrf)) return $this->csrf;

    // see if there's a token in the session
    $token = s::get('kirby_panel_csrf');

    // create a new csrf token if not available yet
    if(str::length($token) !== 32) {
      $token = str::random(32);
    }

    // store the new token in the session
    s::set('kirby_panel_csrf', $token);

    // create a new csrf token
    return $this->csrf = $token;

  }

  public function csrfCheck() {

    $csrf = get('csrf');

    if(empty($csrf) or $csrf !== s::get('kirby_panel_csrf')) {

      try {
        $this->user()->logout();
      } catch(Exception $e) {}

      $this->redirect('login');

    }

  }

  public function kirby() {
    return $this->kirby;
  }

  public function site() {

    // return the site object if it has already been stored
    if(!is_null($this->site)) return $this->site;

    // load the original site first to load all branch files
    $this->kirby->site();

    // create a new panel site object
    return $this->site = new Site($this->kirby);

  }

  public function multilang() {

    if(!$this->site->multilang()) {
      $language = null;
    } else if($language = get('language') or $language = s::get('kirby_panel_lang')) {
      // $language is already set
    } else {
      $language = null;
    }

    // set the path and lang for the original site object
    $this->kirby->site()->visit('/', $language);

    // set the path and lang for the panel site object
    $this->site->visit('/', $language);

    // store the language code
    if($this->site->multilang()) {
      s::set('kirby_panel_lang', $this->site->language()->code());
    }

  }

  public function page($id) {
    if($page = (empty($id) or $id == '/') ? $this->site() : $this->site()->find($id)) {
      return $page;
    } else {
      throw new Exception(l('pages.error.missing'));
    }
  }

  public function roots() {
    return $this->roots;
  }

  public function routes($routes = null) {
    if(is_null($routes)) return $this->routes;
    return $this->routes = array_merge($this->routes, (array)$routes);
  }

  public function urls() {
    return $this->urls;
  }

  public function form($id, $data = array(), $submit = null) {

    if(file_exists($id)) {
      $file = $id;
    } else {
      $file = $this->roots->forms . DS . $id . '.php';
    }

    if(!file_exists($file)) {
      throw new Exception(l('form.error.missing'));
    }

    $callback = require($file);

    if(!is_callable($callback)) {
      throw new Exception(l('form.construct.error.invalid'));
    }

    $form = call($callback, $data);

    if(is_callable($submit)) {
      $form->on('submit', $submit);
    }

    return $form;

  }

  public function translations() {

    if(!is_null($this->translations)) return $this->translations;

    $this->translations = new Collection;

    foreach(dir::read($this->roots()->translations()) as $dir) {
      // filter out everything but directories
      if(!is_dir($this->roots()->translations() . DS . $dir)) continue;

      // create the translation object
      $translation = new Translation($this, $dir);
      $this->translations->append($translation->code(), $translation);
    }

    return $this->translations;

  }

  public function translation() {

    if(!is_null($this->translation)) return $this->translation;

    // get the default language code from the options
    $lang = $this->kirby()->option('panel.language', 'en');
    $user = $this->site()->user();

    if($user && $user->language()) {
      $lang = $user->language();
    }

    return $this->translation = new Translation($this, $lang);

  }

  public function language() {
    return $this->translation();
  }

  public function direction() {
    return $this->translation()->direction();
  }

  public function launch($path = null) {

    // set the timezone for all date functions
    date_default_timezone_set($this->kirby->options['timezone']);

    $this->path  = $this->kirby->path();
    $this->route = $this->router->run($this->path);

    // set the current url
    $this->urls->current = rtrim($this->urls->index() . '/' . $this->path, '/');

    // start the error handling
    new ErrorHandling($this->kirby, $this);

    ob_start();

    // react on invalid routes
    if(!$this->route) {
      throw new Exception(l('routes.error.invalid'));
    }

    if(is_callable($this->route->action())) {
      $response = call($this->route->action(), $this->route->arguments());
    } else {
      $response = $this->response();
    }

    // check for a valid response object
    if(is_a($response, 'Response')) {
      echo $response;
    } else {
      echo new Response($response);
    }

    ob_end_flush();

  }

  public function response() {

    // let's find the controller and controller action
    $controllerParts  = str::split($this->route->action(), '::');
    $controllerUri    = $controllerParts[0];
    $controllerAction = $controllerParts[1];
    $controllerFile   = $this->roots->controllers . DS . strtolower(str_replace('Controller', '', $controllerUri)) . '.php';
    $controllerName   = basename($controllerUri);

    // react on missing controllers
    if(!file_exists($controllerFile)) {
      throw new Exception(l('controller.error.invalid'));
    }

    // load the controller
    require_once($controllerFile);

    // check for the called action
    if(!method_exists($controllerName, $controllerAction)) {
      throw new Exception(l('controller.error.action'));
    }

    // run the controller
    $controller = new $controllerName;

    // call the action and pass all arguments from the router
    return call(array($controller, $controllerAction), $this->route->arguments());

  }

  public function license() {

    $key  = c::get('license');
    $type = 'trial';

    /**
     * Hey stranger,
     *
     * So this is the mysterious place where the panel checks for
     * valid licenses. As you can see, this is not reporting
     * back to any server and the license keys are rather simple to
     * hack. If you really feel like removing the warning in the panel
     * or tricking Kirby into believing you bought a valid license even
     * if you didn't, go for it! But remember that literally thousands of
     * hours of work have gone into Kirby in order to make your
     * life as a developer, designer, publisher, etc. easier. If this
     * doesn't mean anything to you, you are probably a lost case anyway.
     *
     * Have a great day!
     *
     * Bastian
     */
    if(str::startsWith($key, 'K2-PRO') and str::length($key) == 39) {
      $type = 'Kirby 2 Professional';
    } else if(str::startsWith($key, 'K2-PERSONAL') and str::length($key) == 44) {
      $type = 'Kirby 2 Personal';
    } else if(str::startsWith($key, 'MD-') and str::length($key) == 35) {
      $type = 'Kirby 1';
    } else if(str::startsWith($key, 'BETA') and str::length($key) == 9) {
      $type = 'Kirby 1';
    } else if(str::length($key) == 32) {
      $type = 'Kirby 1';
    } else {
      $key = null;
    }

    return new Obj(array(
      'key'   => $key,
      'local' => $this->isLocal(),
      'type'  => $type,
    ));

  }

  public function isLocal() {
    $localhosts = array('::1', '127.0.0.1', '0.0.0.0');
    return (
      in_array(server::get('SERVER_ADDR'), $localhosts) ||
      server::get('SERVER_NAME') == 'localhost' ||
      str::endsWith(server::get('SERVER_NAME'), '.localhost') ||
      str::endsWith(server::get('SERVER_NAME'), '.test')
    );
  }

  public function notify($text) {
    s::set('kirby_panel_message', array(
      'type' => 'notification',
      'text' => $text,
    ));
  }

  public function alert($text) {
    s::set('kirby_panel_message', array(
      'type' => 'error',
      'text' => $text,
    ));
  }

  public function redirect($obj = '/', $action = false, $force = false) {

    if($force === false and $redirect = get('_redirect')) {
      $url = purl($redirect);
    } else {
      $url = purl($obj, $action);
    }

    if(r::ajax()) {

      $user = $this->site()->user();

      die(response::json(array(
        'direction' => $this->direction(),
        'user'      => $user ? $user->username() : false,
        'url'       => $url
      )));

    } else {
      go($url);
    }

  }

  public function users() {
    return $this->site()->users();
  }

  public function user($username = null) {
    if($user = $this->site()->user($username)) {
      return $user;
    } else {
      throw new Exception(l('users.error.missing'));
    }
  }

  public static function fatal($e, $root) {

    $message = $e->getMessage() ? $e->getMessage() : 'Error without a useful message :(';
    $where   = implode('<br>', [
      '',
      '',
      '<b>It happened here:</b>',
      'File: <i>' . str_replace($root, '/panel', $e->getFile()) . '</i>',
      'Line: <i>' . $e->getLine() . '</i>'
    ]);

    // load the fatal screen
    return tpl::load($root . DS . 'app' . DS . 'layouts' . DS . 'fatal.php', [
      'css'     => url::index() . '/assets/css/panel.css',
      'content' => $message . $where
    ]);

  }

  public function access($area) {
    return new Event('panel.access.' . $area);
  }

  public function __debuginfo() {
    return [
      'version'      => $this->version(),
      'license'      => $this->license(),
      'roots'        => $this->roots(),
      'urls'         => $this->urls(),
      'csrf'         => $this->csrf(),
      'translations' => $this->translations()->keys(),
      'translation'  => $this->translation(),
      'routes'       => $this->routes(),
      'kirby'        => $this->kirby(),
      'site'         => $this->site(),
    ];
  }

}
