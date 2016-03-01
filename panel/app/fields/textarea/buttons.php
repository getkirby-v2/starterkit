<?php

class Buttons {

  static public $setup = array();

  public $texarea = null;
  public $buttons = array();

  public function __construct($textarea, $buttons = array()) {

    $this->textarea = $textarea;

    if(!is_array($buttons)) {
      $this->buttons = array_keys(static::$setup);
    } else {
      $this->buttons = $buttons;
    }

  }

  public function __toString() {

    $html  = '<nav class="field-buttons">';
    $html .= '<ul class="nav nav-bar">';

    foreach(static::$setup as $key => $button) {

      if(!in_array($key, $this->buttons)) continue;

      if(!empty($button['action'])) {
        $action = $this->textarea->model()->url('field/' . $this->textarea->name() . '/textarea/' . $button['action']);
      } else {
        $action = null;
      }

      $icon  = '<i class="icon fa fa-' . $button['icon'] . '"></i>';
      $html .= '<li class="field-button-' . $key . '">';
      $html .= html::tag('button', $icon, array(
        'type'                 => 'button',
        'tabindex'             => '-1',
        'title'                => @$button['label'] . ' (' . @$button['shortcut'] . ')',
        'class'                => 'btn',
        'data-editor-shortcut' => @$button['shortcut'],
        'data-tpl'             => @$button['template'],
        'data-text'            => @$button['text'],
        'data-action'          => $action
      ));

      $html .= '</li>';

    }

    $html .= '</ul>';
    $html .= '</nav>';

    return $html;

  }

}

buttons::$setup = array(
  'bold' => array(
    'label'    => l::get('fields.textarea.buttons.bold.label'),
    'text'     => l::get('fields.textarea.buttons.bold.text'),
    'shortcut' => 'meta+b',
    'template' => '**{text}**',
    'icon'     => 'bold'
  ),
  'italic' => array(
    'label'    => l::get('fields.textarea.buttons.italic.label'),
    'text'     => l::get('fields.textarea.buttons.italic.text'),
    'shortcut' => 'meta+i',
    'template' => '*{text}*',
    'icon'     => 'italic'
  ),
  'link' => array(
    'label'    => l::get('fields.textarea.buttons.link.label'),
    'shortcut' => 'meta+shift+l',
    'action'   => 'link',
    'icon'     => 'chain'
  ),
  'email' => array(
    'label'    => l::get('fields.textarea.buttons.email.label'),
    'shortcut' => 'meta+shift+e',
    'action'   => 'email',
    'icon'     => 'envelope'
  ),
);