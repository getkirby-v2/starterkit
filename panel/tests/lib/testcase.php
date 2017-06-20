<?php 

class PanelTestCase extends PHPUnit_Framework_TestCase {

  public $roots;
  public $kirby;
  public $panel;
  public $site;

  public function __construct() {

    $roots = array(
      'tests' => dirname(__DIR__),
      'panel' => dirname(dirname(__DIR__)),
      'index' => dirname(dirname(dirname(__DIR__))),
      'kirby' => dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'kirby',
      'dummy' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'dummy'
    );

    // load kirby
    require_once($roots['kirby'] . DIRECTORY_SEPARATOR . 'bootstrap.php');

    // make sure to start a session
    s::start();

    // create the dummy content directory
    dir::make($roots['dummy'] . DS . 'content');

    // create the roots object
    $this->roots = new Obj($roots);

    // load the panel 
    require_once($roots['panel'] . DS . 'app' . DS . 'bootstrap.php');

    // initiate kirby
    $this->kirby = new Kirby();
    $this->kirby->roots->content = $this->roots->dummy . DS . 'content';
    $this->kirby->roots->site    = $this->roots->dummy . DS . 'site';

    // initiate the panel
    $this->panel = new Panel($this->kirby, $this->roots->panel);

    // load the default panel translation
    $this->panel->translation()->load();

    // store the site instance
    $this->site = $this->panel->site();

  }

  protected function setUp() {

    s::restart();

    // clean all triggers
    kirby::$triggered = array();
    kirby::$hooks     = array();

    $this->removeContent();
    $this->removeAccounts();
    
  }

  protected function tearDown() {
    
    s::restart();

    // clean all triggers
    kirby::$triggered = array();
    kirby::$hooks     = array();

    $this->removeContent();
    $this->removeAccounts();

  }

  protected function removeContent() {
    dir::clean($this->roots->dummy . DS . 'content');    
  }

  protected function removeAccounts() {
    dir::clean($this->roots->dummy . DS . 'site' . DS . 'accounts');    
  }

  protected function createAdmin($username = 'admin') {
    return $this->panel->users()->create(array(
      'username'             => $username,
      'email'                => $username . '@getkirby.com', 
      'password'             => 'test',
      'passwordconfirmation' => 'test',
      'role'                 => 'admin'
    ));
  }

  protected function createEditor($username = 'editor') {
    return $this->panel->users()->create(array(
      'username'             => $username,
      'email'                => $username . '@getkirby.com',
      'password'             => 'test',
      'passwordconfirmation' => 'test',
      'role'                 => 'editor'
    )); 
  }

}