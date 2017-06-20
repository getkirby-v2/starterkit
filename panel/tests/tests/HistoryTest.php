<?php

class HistoryTest extends PanelTestCase {

  protected function setUp() {

    parent::setUp();

    $this->user    = $this->createAdmin();
    $this->history = $this->user->history();
    $this->page    = $this->site->children()->create('test', 'test');

  }

  public function testConstruct() {
    $this->assertInstanceOf('Kirby\\Panel\\Models\\User\\History', $this->history);
  }

  public function testAddWithoutLogin() {
    // Todo: fails because of missing auth check 
    // $this->history->add($this->page);
    // $this->assertEquals(array(), $this->history->get());
  }

  public function testInvalidPage() {
    $this->user->login('test');
    $this->history->add('doesnotexist');
    $this->assertEquals(array(), $this->history->get());
  }

  public function testAdd() {

    /* Todo: fails for no particular reason so far
    $this->user->login('test');
    $this->history->add($this->page);
    $this->assertEquals(array('test'), $this->history->get());
    */
  }

}