<?php

class ChangesTest extends PanelTestCase {

  protected function setUp() {

    parent::setUp();

    $this->user    = $this->createAdmin();
    $this->page    = $this->site->children()->create('test', 'test');
    $this->changes = $this->page->changes();

  }

  public function testConstruct() {
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page\\Changes', $this->changes);
    $this->assertEquals($this->changes->id(), sha1($this->page->id()));
    $this->assertEquals(array(), $this->changes->data());
  }

  public function testUpdate() {

    $updates = array(
      'a' => 'test-1',
      'b' => 'test-2'
    );

    $this->changes->update($updates);

    $this->assertEquals('test-1', $this->changes->get('a'));
    $this->assertEquals('test-2', $this->changes->get('b'));

    $this->assertEquals($updates, $this->changes->get());

  }

  public function testDiscard() {

    $updates = array(
      'a' => 'test-1',
      'b' => 'test-2'
    );

    $this->changes->update($updates);

    $this->assertEquals('test-1', $this->changes->get('a'));
    $this->assertEquals('test-2', $this->changes->get('b'));

    $this->changes->discard('a');

    $this->assertEquals(false, $this->changes->get('a'));
    $this->assertEquals('test-2', $this->changes->get('b'));

    $this->changes->discard();

    $this->assertEquals(false, $this->changes->get('a'));
    $this->assertEquals(false, $this->changes->get('b'));

  }

  public function testFlush() {

    $updates = array(
      'a' => 'test-1',
      'b' => 'test-2'
    );

    $this->changes->update($updates);

    $this->assertEquals('test-1', $this->changes->get('a'));
    $this->assertEquals('test-2', $this->changes->get('b'));

    $this->changes->flush();

    $this->assertEquals(array(), $this->changes->data());

  }

}