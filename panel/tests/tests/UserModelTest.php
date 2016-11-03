<?php

class UserModelTest extends PanelTestCase {

  protected function setUp() {
    parent::setUp();
    $this->createAdmin();
  }

  public function testUrl() {

  }

  public function testForm() {

  }

  public function testAvatar() {

    $user = $this->panel->user('admin');

    $this->assertInstanceOf('Kirby\\Panel\\Models\\User\\Avatar', $user->avatar());
    $this->assertFalse($user->avatar()->exists());

  }

  public function testCreate() {

    // the test user should be available because of the setUp method
    $user = $this->panel->user('admin');

    $user->assertInstanceOf('UserModel', $user);

    $user->assertEquals('admin', $user->username());
    $user->assertEquals('admin@getkirby.com', $user->email());

    // the first user should be an admin
    $user->assertEquals('admin', $user->role());

    // the password confirmation should not be stored
    $user->assertFalse('passwordconfirmation', $user->passwordconfirmation());

  }

  public function testLoginWithInvalidPassword() {

    $user = $this->panel->user('admin');  
    $this->assertFalse($user->login('invalidpassword'));

    $this->setExpectedException('Exception', 'The user could not be found');
    $this->assertTrue($this->panel->user()->is($user));
    $this->assertTrue($user->isCurrent());

  }

  public function testLogin() {

    $user = $this->panel->user('admin');  
    $this->assertTrue($user->login('test'));
    $this->assertTrue($this->panel->user()->is($user));
    $this->assertTrue($user->isCurrent());

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage The user could not be found
   */
  public function testUpdateWithoutLogin() {

    $user = $this->panel->user('admin');
    $user->update(array(
      'firstName' => 'test',
      'lastName'  => 'user'      
    ));

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage You are not allowed to update this user
   */
  public function testUpdateWithDifferentAccount() {

    // create a new editor account
    $editor = $this->createEditor();

    // login as editor
    $editor->login('test');

    // try to update the admin account
    $user = $this->panel->user('admin');
    $user->update(array(
      'firstName' => 'test',
      'lastName'  => 'user'      
    ));

  }

  public function testUpdateWithAdmin() {

    $admin = $this->panel->user('admin');
    $admin->login('test');

    // create a new editor account
    $editor = $this->createEditor();
    $editor->update(array(
      'firstName' => 'test',
      'lastName'  => 'user'
    ));

    $this->assertEquals('test', $editor->firstName());
    $this->assertEquals('user', $editor->lastName());

  }

  public function testUpdateEditorWithSelf() {

    // create a new editor account
    $editor = $this->createEditor();

    // login as editor
    $editor->login('test');

    // update self
    $editor->update(array(
      'firstName' => 'test',
      'lastName'  => 'user'      
    ));

    $this->assertEquals('test', $editor->firstName());
    $this->assertEquals('user', $editor->lastName());

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage The user could not be found
   */
  public function testDeleteLastAdmin() {

    $user = $this->panel->user('admin');
    $user->delete();

  }

  public function testDeleteWithoutLogin() {

    // create another admin in order to be able to delete the first one
    $this->createAdmin('admin2');

    // get the first admin
    $user = $this->panel->user('admin');

    $this->setExpectedException('Exception', 'The user could not be found');
    $user->delete();

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage You are not allowed to delete users
   */
  public function testDeleteAdminWithEditor() {

    // create an editor and login with it
    $editor = $this->createEditor();
    $editor->login('test');

    // create another admin to make sure at least one admin can be deleted
    $admin = $this->createAdmin('admin2');
    $admin->delete();

  }

  /**
   * @expectedException Exception
   * @expectedExceptionMessage You are not allowed to delete users
   */
  public function testDeleteEditorWithEditor() {

    // create an editor and login with it
    $editor = $this->createEditor();
    $editor->login('test');

    // create another editor and try to delete it
    $editor2 = $this->createEditor('editor2');
    $editor2->delete();

  }

  public function testDeleteAdminWithAdmin() {

    // create and login a second admin
    $admin2 = $this->createAdmin('admin2');
    $admin2->login('test');

    // get the first admin and delete it
    $user = $this->panel->user('admin');
    $user->delete();

  }

  public function testDeleteEditorWithAdmin() {

    // login as admin
    $admin = $this->panel->user('admin');
    $admin->login('test');

    // create another editor
    $editor = $this->createEditor();
    $editor->delete();

  }

}