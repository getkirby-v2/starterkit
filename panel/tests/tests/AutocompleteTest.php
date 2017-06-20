<?php

class AutocompleteTest extends PanelTestCase {

  protected function setUp() {

    parent::setUp();

    $this->createAdmin('homer')->login('test');
    $this->createAdmin('marge');
    $this->createEditor('lisa');
    $this->createEditor('bart');

    $projects = $this->site->children()->create('projects', 'projects', array(
      'title' => 'Projects'
    ));

    $projects->children()->create('project-a', 'project', array(
      'title' => 'Project A',
      'tags'  => 'design, photography'
    ));

    $projects->children()->create('project-b', 'project', array(
      'title' => 'Project B',
      'tags'  => 'photography, architecture'
    ));

    $projects->children()->create('project-c', 'project', array(
      'title' => 'Project C',
      'tags'  => 'architecture, illustration'
    ));

  }

  public function testUsernames() {

    $autocomplete = new Kirby\Panel\Autocomplete($this->panel, 'usernames');
    $expected     = array('bart', 'homer', 'lisa', 'marge');

    $this->assertEquals($autocomplete->result(), $expected);

  }

  public function testEmails() {

    $autocomplete = new Kirby\Panel\Autocomplete($this->panel, 'emails');
    $expected     = array('bart@getkirby.com', 'homer@getkirby.com', 'lisa@getkirby.com', 'marge@getkirby.com');

    $this->assertEquals($autocomplete->result(), $expected);

  }

  public function testUris() {

    $autocomplete = new Kirby\Panel\Autocomplete($this->panel, 'uris');
    $expected     = array('projects', 'projects/project-a', 'projects/project-b', 'projects/project-c');

    $this->assertEquals($autocomplete->result(), $expected);

  }

  public function testFieldWithDefaults() {

    $autocomplete = new Kirby\Panel\Autocomplete($this->panel, 'field', array(
      'uri'       => 'projects/project-a',
      'separator' => ','
      // default: 'field' => 'tags',
      // default: 'index' => 'siblings'
    ));

    $expected = array('architecture', 'design', 'illustration', 'photography');

    $this->assertEquals($autocomplete->result(), $expected);

  }

  public function testFieldWithSiblingsIndex() {

    $autocomplete = new Kirby\Panel\Autocomplete($this->panel, 'field', array(
      'uri'       => 'projects/project-a',
      'separator' => ',',
      'index'     => 'siblings'
      // default: 'field' => 'tags',
    ));

    $expected = array('architecture', 'design', 'illustration', 'photography');

    $this->assertEquals($autocomplete->result(), $expected);

  }

  public function testFieldWithChildrenIndex() {

    $autocomplete = new Kirby\Panel\Autocomplete($this->panel, 'field', array(
      'uri'       => 'projects',
      'separator' => ',',
      'index'     => 'children',
      'field'     => 'tags'
    ));

    $expected = array('architecture', 'design', 'illustration', 'photography');

    $this->assertEquals($autocomplete->result(), $expected);

  }

  public function testFieldWithTemplateIndex() {

    $autocomplete = new Kirby\Panel\Autocomplete($this->panel, 'field', array(
      'uri'       => 'projects',
      'separator' => ',',
      'index'     => 'template',
      'field'     => 'tags'
    ));

    $expected = array('architecture', 'design', 'illustration', 'photography');

    $this->assertEquals($autocomplete->result(), $expected);

  }

  public function testFieldWithAllIndex() {

    $autocomplete = new Kirby\Panel\Autocomplete($this->panel, 'field', array(
      'uri'       => 'projects',
      'index'     => 'all',
      'field'     => 'title'
    ));

    $expected = array('Project A', 'Project B', 'Project C', 'Projects');

    $this->assertEquals($autocomplete->result(), $expected);

  }

}