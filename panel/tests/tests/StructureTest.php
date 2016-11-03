<?php

class StructureTest extends PanelTestCase {

  public function setUp() {

    parent::setUp();

    $this->user      = $this->createAdmin();
    $this->page      = $this->site->children()->create('test', 'test');
    $this->structure = $this->page->structure()->forField('testfield');

  }

  public function testConstruct() {

    $this->assertInstanceOf('Kirby\\Panel\\Structure', $this->structure);
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page', $this->structure->model());
    $this->assertInstanceOf('Collection', $this->structure->data());
    $this->assertEquals($this->structure->model(), $this->page);

  }

  public function testAdd() {

    $id = $this->structure->add(array(
      'testKey' => 'testValue'
    ));

    $this->assertTrue(is_string($id) and strlen($id) == 32);

  }

  public function testFind() {

    $id = $this->structure->add(array(
      'testKey' => 'testValue'
    ));

    $element = $this->structure->find($id);

    $this->assertInstanceOf('Obj', $element);
    $this->assertEquals($id, $element->id());
    $this->assertEquals('testValue', $element->testKey());

  }

  public function testUpdate() {

    $id = $this->structure->add(array(
      'testKey' => 'testValue'
    ));

    $element = $this->structure->find($id);

    $this->assertEquals($id, $element->id());
    $this->assertEquals('testValue', $element->testKey());

    $this->structure->update($id, array(
      'testKey' => 'updatedValue'
    ));

    $element = $this->structure->find($id);

    $this->assertEquals($id, $element->id());
    $this->assertEquals('updatedValue', $element->testKey());

  }

  public function testSort() {

    $aId = $this->structure->add(array(
      'a' => 'a'
    ));

    $bId = $this->structure->add(array(
      'b' => 'b'
    ));

    $a = $this->structure->find($aId);
    $b = $this->structure->find($bId);

    $this->assertEquals($this->structure->data()->first(), $a);
    $this->assertEquals($this->structure->data()->last(), $b);

    $this->structure->sort(array($bId, $aId));

    $this->assertEquals($this->structure->data()->first(), $b);
    $this->assertEquals($this->structure->data()->last(), $a);

  }

  public function testDelete() {

    $id = $this->structure->add(array(
      'testKey' => 'testValue'
    ));

    $element = $this->structure->find($id);

    $this->assertInstanceOf('Obj', $element);

    $this->structure->delete($id);

    $element = $this->structure->find($id);
  
    $this->assertEquals(null, $element);    

  }

  public function testDeleteAll() {

    $aId = $this->structure->add(array(
      'a' => 'a'
    ));

    $bId = $this->structure->add(array(
      'b' => 'b'
    ));

    $a = $this->structure->find($aId);
    $b = $this->structure->find($bId);

    $this->assertInstanceOf('Obj', $a);
    $this->assertInstanceOf('Obj', $b);

    $this->structure->delete();

    $a = $this->structure->find($aId);
    $b = $this->structure->find($bId);

    $this->assertEquals(null, $a);
    $this->assertEquals(null, $b);

  }

}