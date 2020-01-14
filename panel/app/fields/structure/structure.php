<?php

class StructureField extends BaseField {

  static public $assets = array(
    'js' => array(
      'structure.js'
    ),
    'css' => array(
      'structure.css'
    )
  );

  public $default   = array();
  public $fields    = array();
  public $entry     = null;
  public $structure = null;
  public $style     = 'items';
  public $modalsize = 'medium';
  public $limit     = null;
  public $sort      = null;
  public $flip      = false;

  public function routes() {

    return array(
      array(
        'pattern' => 'add',
        'method'  => 'get|post',
        'action'  => 'add'
      ),
      array(
        'pattern' => 'sort',
        'method'  => 'post',
        'action'  => 'sort',
      ),
      array(
        'pattern' => '(:any)/update',
        'method'  => 'get|post',
        'action'  => 'update'
      ),
      array(
        'pattern' => '(:any)/delete',
        'method'  => 'get|post',
        'action'  => 'delete',
      )
    );
  }

  public function modalsize() {
    $sizes = array('small', 'medium', 'large');
    return in_array($this->modalsize, $sizes) ? $this->modalsize : 'medium';
  }

  public function style() {
    $styles = array('table', 'items');
    return in_array($this->style, $styles) ? $this->style : 'items';
  }

  public function sort() {
    return $this->sort ? str::split($this->sort) : false;
  }

  public function flip() {
    return $this->flip === true ? true : false;
  }

  public function sortable() {
    return !$this->readonly() && !$this->sort() && !$this->flip();
  }

  public function structure() {
    if(!is_null($this->structure)) {
      return $this->structure;
    } else {
      return $this->structure = $this->model->structure()->forField($this->name, $this->value());
    }
  }

  public function fields() {

    $output = array();

    // use the configured fields if available
    $fieldData = $this->structure->fields();
    $fields = $this->entry;
    if(!is_array($fields)) {
      // fall back to all existing fields
      $fields = array_keys($fieldData);
    }

    foreach($fields as $f) {
      if(!isset($fieldData[$f])) continue;
      $v = $fieldData[$f];

      $v['name']  = $f;
      $v['value'] = '{{' . $f . '}}';

      $output[] = $v;
    }

    return $output;

  }

  public function entries() {
    $entries = $this->structure()->data();

    if($sort = $this->sort()) {
      $entries = call([$entries, 'sortBy'], $sort);
    }
    if($this->flip()) {
      $entries = $entries->flip();
    }

    return $entries;
  }

  public function result() {  
    /**
     * Users store their data as plain yaml. 
     * So we need this hacky solution to give data 
     * as an array to the form serializer in case 
     * of users, in order to not mess up their data
     */
    if(is_a($this->model, 'Kirby\\Panel\\Models\\User')) {
      return $this->structure()->toArray();      
    } else {
      return $this->structure()->toYaml();            
    }
  }

  public function entry($data) {

    if(is_null($this->entry) or !is_string($this->entry)) {
      $html = array();
      foreach($this->fields as $name => $field) {
        if(isset($data->$name)) {
          $html[] = $data->$name;          
        }
      }
      return implode('<br>', $html);
    } else {
    
      $text = $this->entry;

      foreach((array)$data as $key => $value) {
        if(is_array($value)) {
          $value = implode(', ', array_values($value));
        }
        $text = str_replace('{{' . $key . '}}', $value, $text);
      }

      return $text;
    
    }

  }

  public function label() {
    return null;
  }

  public function headline() {

    // get entries
    $entries = $this->entries();

    // check if limit is either null or the number of entries less than limit 
    if(!$this->readonly && (is_null($this->limit) || (is_int($this->limit) && $entries->count() < $this->limit))) {

      $add = new Brick('a');
      $add->html('<i class="icon icon-left fa fa-plus-circle"></i>' . l('fields.structure.add'));
      $add->addClass('structure-add-button label-option');
      $add->data('modal', true);
      $add->attr('href', purl($this->model, 'field/' . $this->name . '/structure/add'));

    } else {
      $add = null;
    }

    // make sure there's at least an empty label
    if(!$this->label) {
      $this->label = '&nbsp;';
    }
 
    $label = parent::label();
    $label->addClass('structure-label');
    $label->append($add);

    return $label;

  }

  public function content() {
    return tpl::load(__DIR__ . DS . 'template.php', array('field' => $this));
  }

  public function url($action) {
    return purl($this->model(), 'field/' . $this->name() . '/structure/' . $action);
  }  

}