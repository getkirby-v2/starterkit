<?php

namespace Kirby\Panel;

use R;
use Response;
use Toolkit;
use Tpl;
use Visitor;
use ErrorController;

use Kirby;
use Kirby\Panel;

use Whoops\Run;
use Whoops\Handler\Handler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\CallbackHandler;

class ErrorHandling {

  public $kirby;
  public $panel;
  public $whoops;

  public function __construct(Kirby $kirby, Panel $panel) {

    $this->kirby  = $kirby;
    $this->panel  = $panel;
    $this->whoops = new Run;

    if(r::ajax() || visitor::acceptance('application/json') > visitor::acceptance('text/html')) {
      $this->json();
    } else {
      $this->html();
    }

    $this->whoops->register();

  }

  public function json() {

    $kirby   = $this->kirby;   
    $handler = new CallbackHandler(function($exception, $inspector, $run) use($kirby) {

      if($kirby->options['debug'] === true) {
        echo response::json([
          'status'  => 'error',
          'code'    => $exception->getCode(),
          'message' => $exception->getMessage() . ' in file: ' . $exception->getFile() . ' on line: ' . $exception->getLine(),
        ], 500);
      } else {
        echo response::json([
          'status'  => 'error',
          'code'    => $exception->getCode(),
          'message' => $exception->getMessage(),
        ], 500);        
      }

      return Handler::QUIT;

    });
  
    $this->whoops->pushHandler($handler);      

  }

  public function html() {

    if($this->kirby->options['whoops'] === true && $this->kirby->options['debug'] === true) {

      $handler = new PrettyPageHandler;
      $handler->setPageTitle('Kirby CMS Debugger');
      $handler->addDataTableCallback('Kirby', function() {
        return [
          'Kirby Toolkit' => 'v' . toolkit::$version,
          'Kirby CMS'     => 'v' . kirby::$version,
          'Kirby Panel'   => 'v' . panel::$version
        ];
      });

    } else {

      $panel   = $this->panel;
      $kirby   = $this->kirby;
      $handler = new CallbackHandler(function($exception, $inspector, $run) use($panel, $kirby) {

        // load the error controller
        require_once($panel->roots()->controllers() . DS . 'error.php');

        $error[] = $exception->getMessage();

        if($kirby->options['debug'] === true) {
          $message[] = 'File: ' . $exception->getFile();
          $message[] = 'Line: ' . $exception->getLine();          

          $error[] = '';
          $error[] = '<pre class="debugger"></code>' . implode(PHP_EOL, $message) . '</code></pre>';              
        }

        // start the error controller
        $controller = new ErrorController;
        $response   = $controller->index(implode('<br>', $error), $exception);

        echo $response;

        return Handler::QUIT;

      });

    }

    $this->whoops->pushHandler($handler);      

  }

}