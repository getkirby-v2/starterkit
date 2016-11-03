<?php

class SiteModelTest extends PanelTestCase {

  protected function setUp() {

    parent::setUp();

    $this->user = $this->createAdmin();
    $this->user->login('test');

  }

  public function testBlueprint() {
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page\\Blueprint', $this->site->blueprint());
  }

  public function testUrl() {

    $this->assertEquals('/', $this->site->url());
    $this->assertEquals('/panel/options', $this->site->url('edit'));

  }

  public function testChanges() {
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page\\Changes', $this->site->changes());
  }

  public function testFiles() {
    $this->assertInstanceOf('Kirby\\Panel\\Collections\\Files', $this->site->files());    
  }

  public function testChildren() {
    $this->assertInstanceOf('Kirby\\Panel\\Collections\\Children', $this->site->children());    
  }

  public function testUpdate() {

    $this->site->update(array(
      'title' => 'Test Title'
    ));

    $this->assertEquals('Test Title', $this->site->title());

  }

  public function testSidebar() {
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page\\Sidebar', $this->site->sidebar());    
  }

  public function testUpload() {
    // not testable
  }

  public function testAddButton() {
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page\\AddButton', $this->site->addButton());    
  }

  public function testTopbar() {
    // TODO
  }

  public function testUsers() {
    $this->assertInstanceOf('Kirby\\Panel\\Collections\\Users', $this->site->users());    
  }

  public function testUser() {
    $this->assertInstanceOf('Kirby\\Panel\\Models\\User', $this->site->user());    
  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage The site cannot be deleted
   */
  public function testDelete() {
    $this->site->delete();
  }

  public function testMaxSubpages() {
    $this->assertTrue(is_int($this->site->maxSubpages()));    
  }

  public function testMaxFiles() {
    $this->assertTrue(is_int($this->site->maxFiles()));    
  }

  public function testCanHaveFiles() {
    $this->assertTrue(is_bool($this->site->canHaveFiles()));    
  }

  public function testCanShow() {
    $this->assertTrue(is_bool($this->site->canShowFiles()));    
  }

  public function testCanHaveMoreSubpages() {
    $this->assertTrue(is_bool($this->site->canHaveMoreSubpages()));    
  }

  public function testCanHaveMoreFiles() {
    $this->assertTrue(is_bool($this->site->canHaveMoreFiles()));    
  }

  public function testStructure() {
    $this->assertInstanceOf('Kirby\\Panel\\Structure', $this->site->structure('test'));    
  }

}