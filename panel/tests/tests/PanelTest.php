<?php

class PanelTest extends PanelTestCase {

  public function testRoots() {
    $this->assertInstanceOf('Kirby\\Panel\\Roots', $this->panel->roots());
  }

  public function testUrls() {
    $this->assertInstanceOf('Kirby\\Panel\\Urls', $this->panel->urls());
  }

  public function testKirby() {
    $this->assertInstanceOf('Kirby', $this->panel->kirby());
    $this->assertEquals($this->kirby, $this->panel->kirby());
  }

  public function testSite() {
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Site', $this->panel->site());
  }

  public function testPage() {

  }

  public function testRoutes() {

  }

  public function testForm() {

  }

  public function testLanguages() {
    
  }

  public function testLanguage() {

  }

  public function testMultilang() {

  }

  public function testDirection() {

  }

  public function testLicense() {
    $this->assertInstanceOf('Obj', $this->panel->license());    
  }

  public function testUsers() {
    $this->assertInstanceOf('Kirby\\Panel\\Collections\\Users', $this->panel->users());    
  }

  public function testUser() {

  }

}