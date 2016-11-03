<?php

class PageModelTest extends PanelTestCase {

  protected function setUp() {

    parent::setUp();

    $this->user = $this->createAdmin();
    $this->user->login('test');

  }

  public function testCreate() {

    $page = $this->site->children()->create('home', 'home', array(
      'title' => 'Home'
    ));

    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page', $page);
    $this->assertTrue($page->isHomePage());
    $this->assertEquals('Home', $page->title());

  }

  public function testCreateChildren() {

    $page = $this->site->children()->create('test', 'test');

    $a = $page->children()->create('a', 'a');
    $b = $page->children()->create('b', 'b');

    $this->assertEquals(2, $page->children()->count());

  }

  public function testChanges() {
    $page = $this->site->children()->create('test', 'test');
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page\\Changes', $page->changes());
  }

  public function testUpdate() {

    $page = $this->site->children()->create('test', 'test');

    $page->update(array(
      'title' => 'Test Title'
    ));

    $this->assertEquals('Test Title', $page->title());

  }

  public function testUpdateHook() {

    $page      = $this->site->children()->create('test', 'test');
    $triggered = false;

    kirby()->hook('panel.page.update', function() use(&$triggered) {
      $triggered = true;
    });

    // check that the hook has not been triggered yet
    $this->assertFalse($triggered);

    // update the page content
    $page->update(array('title' => 'Test Title'));

    // check if the hook has been triggered
    $this->assertTrue($triggered);

  }

  public function testUpdateHookWithVisiblePage() {

    $page = $this->site->children()->create('test', 'test');

    // make the page visible
    $page->sort(1);

    $triggered = false;

    kirby()->hook('panel.page.update', function() use(&$triggered) {
      $triggered = true;
    });

    // check that the hook has not been triggered yet
    $this->assertFalse($triggered);

    // update the page content
    $page->update(array('title' => 'Test Title'));

    // check if the hook has been triggered
    $this->assertTrue($triggered);

  }

  public function testParent() {

    $test    = $this->site->children()->create('test', 'test');
    $subtest = $test->children()->create('test', 'test');

    $this->assertInstanceOf('Kirby\\Panel\\Models\\Site', $test->parent());
    $this->assertInstanceOf('Kirby\\Panel\\Models\\Page', $subtest->parent());

    $this->assertEquals($this->site, $test->parent());
    $this->assertEquals($test, $subtest->parent());

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage You cannot change the URL of this page
   */
  public function testChangeHomePageUrl() {

    $page = $this->site->children()->create('home', 'home');
    $page->move('blog');

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage You cannot change the URL of this page
   */
  public function testChangeErrorPageUrl() {

    $page = $this->site->children()->create('error', 'error');
    $page->move('blog');

  }

  public function testChangeUrl() {

    $page = $this->site->children()->create('notes', 'notes');
    $page->move('blog');

    $this->assertEquals('blog', $page->uid());

  }

  public function testHidePage() {

    $page = $this->site->children()->create('test', 'test');

    $page->sort(1);

    $this->assertEquals(1, $page->num());

    $page->hide();

    $this->assertEquals(null, $page->num());

  }

  public function testSortErrorPage() {

    $page = $this->site->children()->create('error', 'error');
    $page->sort(1);
    $this->assertEquals(null, $page->num());

  }

  public function testSortChildren() {

    $page = $this->site->children()->create('test', 'test');

    $a = $page->children()->create('a', 'a');
    $a->sort(1);

    $b = $page->children()->create('b', 'b');
    $b->sort(2);

    $c = $page->children()->create('c', 'c');
    $c->sort(3);

    $this->assertEquals(1, $a->num());
    $this->assertEquals(2, $b->num());
    $this->assertEquals(3, $c->num());

  }


  /**
   * @expectedException Exception
   * @expectedExceptionMessage The page cannot be deleted
   */
  public function testDeleteHomePage() {

    $page = $this->site->children()->create('home', 'home');
    $page->delete();

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage The page cannot be deleted
   */
  public function testDeleteErrorPage() {

    $page = $this->site->children()->create('error', 'error');
    $page->delete();

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage The page cannot be deleted
   */
  public function testDeletePageWithSubpages() {

    $page = $this->site->children()->create('test', 'test');

    $a = $page->children()->create('a', 'a');
    $b = $page->children()->create('b', 'b');

    $page->delete();

  }

  public function testDeletePage() {

    $page = $this->site->children()->create('test', 'test');
    $page->delete();

  }

}