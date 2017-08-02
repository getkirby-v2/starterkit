<?php

class FileModelTest extends PanelTestCase {

  protected function setUp() {

    parent::setUp();

    $this->user = $this->createAdmin();
    $this->user->login('test');

    $this->page = $this->site->children()->create('test', 'test');

    f::copy($this->roots->dummy . DS . 'images' . DS . 'forrest.jpg', $this->page->root() . DS . '1.jpg');
    f::copy($this->roots->dummy . DS . 'images' . DS . 'forrest.jpg', $this->page->root() . DS . '2.jpg');

  }

  public function tearDown() {

    $this->user->logout();    

    $this->removeContent();
    $this->removeAccounts();

  }

  public function testFind() {

    $a = $this->page->file('1.jpg');
    $b = $this->page->file('2.jpg');

    $this->assertInstanceOf('Kirby\\Panel\\Models\\File', $a);
    $this->assertInstanceOf('Kirby\\Panel\\Models\\File', $b);

  }

  public function testRename() {

    $a = $this->page->file('1.jpg');
    $a->rename('3');

    $a = $this->page->file('3.jpg');

    $this->assertInstanceOf('Kirby\\Panel\\Models\\File', $a);
    $this->assertEquals('3', $a->name());
    $this->assertEquals('3.jpg', $a->filename());

  }

  public function testUpdate() {

    $a = $this->page->file('1.jpg');
    $a->update(array(
      'caption' => 'test'
    ));

    $this->assertEquals('test', (string)$a->caption());

  }

  public function testSort() {

    $a = $this->page->file('1.jpg');
    $a->update('sort', 2);

    $b = $this->page->file('2.jpg');
    $b->update('sort', 1);

    $this->assertEquals(2, $a->sort()->int());
    $this->assertEquals(1, $b->sort()->int());

  }

  public function testDelete() {

    $a = $this->page->file('1.jpg');
    $a->delete();

  }

}