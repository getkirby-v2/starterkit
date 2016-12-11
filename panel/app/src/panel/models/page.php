<?php

namespace Kirby\Panel\Models;

use C;
use Dir;
use Exception;
use F;
use Obj;
use S;
use Str;
use V;

use Kirby\Panel\Event;
use Kirby\Panel\Snippet;
use Kirby\Panel\Topbar;
use Kirby\Panel\Structure;
use Kirby\Panel\Collections\Children;
use Kirby\Panel\Collections\Files;
use Kirby\Panel\Exceptions\PermissionsException;
use Kirby\Panel\Models\Page\AddButton;
use Kirby\Panel\Models\Page\Blueprint;
use Kirby\Panel\Models\Page\Menu;
use Kirby\Panel\Models\Page\UI;
use Kirby\Panel\Models\Page\Options;
use Kirby\Panel\Models\Page\Sorter;
use Kirby\Panel\Models\Page\Changes;
use Kirby\Panel\Models\Page\Editor;
use Kirby\Panel\Models\Page\Sidebar;
use Kirby\Panel\Models\Page\Uploader;
use Kirby\Panel\Models\User\History;

class Page extends \Page {

  public function __construct($parent, $dirname) {
    parent::__construct($parent, $dirname);
  }

  public function blueprint() {

    if(isset($this->cache['blueprint'])) return $this->cache['blueprint'];

    $blueprint = $this->intendedTemplate();

    if(!Blueprint::exists($blueprint)) {
      $blueprint = $this->template();
    }

    return $this->cache['blueprint'] = new Blueprint($blueprint);

  }

  public function createNum($to = null) {

    $parent = $this->parent();
    $params = $parent->blueprint()->pages()->num();

    switch($params->mode()) {
      case 'zero':
        return 0;
        break;
      case 'date':
        if($to = $this->date($params->format(), $params->field())) {
          return $to;
        } else {
          return date($params->format());
        }
        break;
      default:

        $visibleSiblings = $parent->children()->visible();

        if($to == 'last') {
          $to = $visibleSiblings->count() + 1;
        } else if($to == 'first') {
          $to = 1;
        } else if(is_null($to)) {
          $to = $this->num();
        }

        if(!v::num($to)) return false;

        if($to <= 0) return 1;

        if($this->isInvisible()) {
          $limit = $visibleSiblings->count() + 1;
        } else {
          $limit = $visibleSiblings->count();
        }

        if($limit < $to) {
          $to = $limit;
        }

        return intval($to);
        break;
    }    

  }

  public function uri($action = null) {
    if(empty($action)) {
      return parent::uri();
    } else {
      return 'pages/' . $this->id() .  '/' . $action;            
    }
  }

  public function url($action = null) {
    if(empty($action)) {
      return parent::url();
    } else if($action == 'preview') {

      $preview = $this->blueprint()->preview();

      if($preview && $this->options()->preview()) {

        switch($preview) {
          case 'parent':
            return $this->parent() ? $this->parent()->url() : $this->url();
            break;
          case 'first-child':
            return $this->children()->first() ? $this->children()->first()->url() : false;
            break;
          case 'last-child':
            return $this->children()->last()  ? $this->children()->last()->url() : false;
            break;
          default:
            return $this->url();
            break;
        }

      } else {
        return false;
      }

    } else if($this->site->multilang() and $lang = $this->site->language($action)) {
      return parent::url($lang->code());
    } else {
      return panel()->urls()->index() . '/' . $this->uri($action);            
    }
  }

  public function form($action, $callback) {    
    return panel()->form('pages/' . $action, $this, $callback);
  }

  public function structure() {
    return new Structure($this, 'page_' . $this->id() . '_' . $this->site->lang());
  }

  public function getFormData() {

    // get the latest content from the text file
    $data = $this->content()->toArray();

    // make sure the title is always there
    $data['title'] = $this->title();

    // add the changes to the content array
    $data = array_merge($data, $this->changes()->get());

    return $data;

  }

  public function getBlueprintFields() {
    return $this->blueprint()->fields($this);
  }

  public function getFormFields() {
    
    $fields = $this->getBlueprintFields()->toArray();

    // add the title as hidden field
    if(!isset($fields['title'])) {
      $fields['title'] = array(
        'type' => 'hidden',
        'name' => 'title'
      ); 
    } else {
      // make sure the title field always has the type title
      $fields['title']['type'] = 'title';
    }

    return $fields;

  }

  public function children() {
    return new Children($this);
  }

  public function files() {
    return new Files($this);
  }

  public function addButton() {
    try {
      return new AddButton($this);
    } catch(Exception $e) {
      return false;
    }
  }

  public function menu($position = 'sidebar') {
    return new Menu($this, $position);
  }

  public function ui() {
    return new UI($this);
  }

  public function options() {
    return new Options($this);
  }

  public function filterInput($input) {
    return $input;
  }

  public function changes() {
    return new Changes($this);
  }

  public function maxSubpages() {
    $max = $this->blueprint()->pages()->max();
    // if max subpages is null, use the biggest 32bit integer
    // will never be reached anyway. Kirby is not made for that scale :)
    return is_null($max) ? 2147483647 : $max;
  }

  public function maxFiles() {
    $max = $this->blueprint()->files()->max();
    // see: maxSubpages
    return is_null($max) ? 2147483647 : $max;    
  }

  public function move($uid) {

    // check if the url option is available for this page
    if($this->options()->url() === false) {
      throw new PermissionsException();
    }

    // keep the old state of the page object
    $old     = clone $this;
    $site    = panel()->site();
    $changes = $this->changes()->get();
    $event   = $this->event('url:action', ['uid' => $uid]);

    // check for permissions
    $event->check();

    $this->changes()->discard();

    if($site->multilang() and $site->language()->code() != $site->defaultLanguage()->code()) {
      parent::update(array(
        'URL-Key' => $uid
      ));
    } else {
      parent::move($uid);
    }

    $this->changes()->update($changes);

    // remove all thumbs for the old id
    $old->removeThumbs();

    // hit the hook
    kirby()->trigger('panel.page.move', [$this, $old]);
  
  }

  public function _sort($to) {
    if(is_dir($this->root())) {
      return parent::sort($to);       
    } else {
      return false;
    }
  }

  public function event($type, $args = []) {
    
    if(in_array($type, ['upload', 'upload:ui', 'upload:action'])) {
      // rewrite the upload event
      $type = 'panel.file.' . $type;
    } else {
      $type = 'panel.page.' . $type;
    }

    return new Event($type, array_merge(['page' => $this], $args));

  }

  public function sort($to = null) {

    // keep the old state of the page object
    $old   = clone $this;
    $event = $this->event('visibility:action', [
      'visibility' => 'visible'
    ]);

    // check for permissions
    $event->check();

    // run the sorter
    $this->sorter()->to($to);    

    // run the hook if the number changed
    if($old->num() != $this->num()) {
      // hit the hook
      kirby()->trigger('panel.page.sort', [$this, $old]);
    }

    return $this->num();

  }

  public function sorter() {
    return new Sorter($this);
  }

  public function hide() {

    // check if sorting is available at all
    if($this->options()->visibility() === false) {
      throw new PermissionsException();
    }

    // keep the old state of the page object
    $old   = clone $this;
    $event = $this->event('visibility:action', [
      'visibility' => 'invisible'
    ]);

    // check for permissions
    $event->check();

    parent::hide();
    $this->sorter()->hide();

    kirby()->trigger('panel.page.hide', [$this, $old]);

  }

  public function toggle($position) {

    $mode     = $this->parent()->blueprint()->pages()->num()->mode();
    $position = intval($position);

    if($mode === 'default') {

      if($position > 0 || $this->isInvisible()) {
        $this->sort($position);                  
      } else {
        $this->hide();
      }

    } else {

      if(!$this->isVisible()) {
        $this->sort($position);
      } else {
        $this->hide();
      }

    }
  
  }

  public function hasNoTitleField() {
    $fields = $this->getFormFields();
    return empty($fields['title']);
  }

  public function sidebar() {
    return new Sidebar($this);    
  }

  public function addToHistory() {
    panel()->user()->history()->add($this);
  }

  public function updateNum() {

    // make sure that the sorting number is correct
    if($this->isVisible()) {      
      $this->sort($this->num());
    }

    return $this->num();

  }

  public function updateUid() {

    // auto-update the uid if the sorting mode is set to zero
    if($this->parent()->blueprint()->pages()->num()->mode() == 'zero') {
      $uid = str::slug($this->title());
      $this->move($uid);
    }
    return $this->uid();

  }

  public function update($data = array(), $lang = null) {

    // create the update event
    $event = $this->event('update:action', [
      'data' => $data
    ]);

    // check for update permissions
    $event->check();

    // keep the old state of the page object
    $old = clone $this;

    // flush all changes 
    $this->changes()->discard();
    
    parent::update($data, $lang);

    // update the number if the date field
    // changed for example
    $this->updateNum();

    kirby()->trigger($event, [$this, $old]);

    // add the page to the history
    $this->addToHistory();

  }

  public function upload() {
    new Uploader($this);        
  }

  public function delete($force = false) {

    // check if the delete option is available
    if($this->options()->delete() === false) {
      throw new PermissionsException();
    }

    // create the delete event
    $event = $this->event('delete:action');

    // check for permissions
    $event->check();

    // delete the page
    parent::delete();

    // resort the siblings
    $this->sorter()->delete();

    // remove unsaved changes
    $this->changes()->discard();

    // delete all associated thumbs
    $this->removeThumbs();

    // hit the hook
    kirby()->trigger($event, $this);

  }

  public function icon($position = 'left') {
    return icon($this->blueprint()->icon(), $position);
  }

  public function dragText() {
    if(c::get('panel.kirbytext') === false) {
      return '[' . $this->title() . '](' . $this->url() . ')';
    } else {
      return '(link: ' . $this->uri() . ' text: ' . $this->title() . ')';
    }
  }

  public function displayNum() {

    if($this->isInvisible()) {
      return '—';
    } else {

      $numberSettings = $this->parent()->blueprint()->pages()->num();

      switch($numberSettings->mode()) {
        case 'zero':
          if($numberSettings->display()) {
            // customer number display
            return $this->{$numberSettings->display()}();
          } else {
            // alphabetic display numbers
            return str::substr($this->title(), 0, 1);            
          }
          break;
        case 'date':
          return $this->date($numberSettings->display(), $numberSettings->field());
          break;
        default:
          if($numberSettings->display()) {
            // customer number display
            return $this->{$numberSettings->display()}();
          } else {
            // regular number display
            return intval($this->num());              
          }
          break;
      }

    }

  }

  public function topbar(Topbar $topbar) {

    foreach($this->parents()->flip() as $item) {
      $topbar->append($item->url('edit'), $item->title());
    }

    $topbar->append($this->url('edit'), $this->title());

    if($topbar->view == 'subpages/index') {
      $topbar->append($this->url('subpages'), l('subpages'));    
    }
   
    $topbar->html .= new Snippet('languages', array(
      'languages' => $this->site()->languages(),
      'language'  => $this->site()->language(),
    ));

  }

  public function changeTemplate($newTemplate) {

    // check if the template can be switched
    if($this->options()->template() === false) {
      throw new PermissionsException();
    }

    $oldTemplate = $this->intendedTemplate();

    if($newTemplate == $oldTemplate) return true;

    if($this->site()->multilang()) {
      
      foreach($this->site()->languages() as $lang) {
        $old = $this->textfile(null, $lang->code());
        $new = $this->textfile($newTemplate, $lang->code());
        f::move($old, $new);
        $this->reset();
        $this->updateForNewTemplate($oldTemplate, $newTemplate, $lang->code());
      }

    } else {
      $old = $this->textfile();      
      $new = $this->textfile($newTemplate);
      f::move($old, $new);
      $this->reset();
      $this->updateForNewTemplate($oldTemplate, $newTemplate);
    }

    return true;

  }

  public function prepareForNewTemplate($oldTemplate, $newTemplate, $language = null) {

    $data         = array();
    $incompatible = array();
    $content      = $this->content($language);
    $oldBlueprint = new Blueprint($oldTemplate);
    $newBlueprint = new Blueprint($newTemplate);
    $oldFields    = $oldBlueprint->fields($this);
    $newFields    = $newBlueprint->fields($this);

    // log
    $removed  = [];
    $replaced = [];
    $added    = [];

    // first overwrite everything
    foreach($oldFields as $oldField) {
      $data[$oldField->name()] = null;    
    }

    // now go through all new fileds and compare them to the old field types
    foreach($newFields as $newField) {

      $oldField = $oldFields->{$newField->name()};

      // only take data from fields with matching names and types
      if($oldField and $oldField->type() == $newField->type()) {
        $data[$newField->name()] = $content->get($newField->name())->value();
      } else {
        $data[$newField->name()] = $newField->default();

        if($oldField) {
          $replaced[$newField->name()] = i18n($newField->label());          
        } else {
          $added[$newField->name()] = i18n($newField->label());          
        }

      }

    }

    foreach($data as $name => $content) {
      if(is_null($content)) $removed[$name] = i18n($oldFields->{$name}->label());
    }

    return array(
      'data'     => $data,
      'removed'  => $removed,
      'replaced' => $replaced,
      'added'    => $added
    );

  }

  public function updateForNewTemplate($oldTemplate, $newTemplate, $language = null) {
    $prep = $this->prepareForNewTemplate($oldTemplate, $newTemplate, $language);
    $this->update($prep['data'], $language);
  }

  /**
   * Clean the thumbs folder for the page
   * 
   */
  public function removeThumbs() {
    return dir::remove($this->kirby()->roots()->thumbs() . DS . $this->id());
  }

}