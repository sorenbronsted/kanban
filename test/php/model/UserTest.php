<?php
require_once 'test/settings.php';

class UserTest extends BaseCrud {

  public function __construct() {
    parent::__construct("User");
  }

  protected function createObject() {
    return Fixtures::getUser();
  }

  protected function delete($uid) {
    $object = User::getByUid($uid);
    $object->destroy();
    $object = User::getByUid($uid);
		$this->assertEquals(true, $object->deleted);
		Db::exec('defaultDb', "delete from user where uid = $object->uid");
  }
	
  protected function updateObject($object) {
		$object->name = 'sletmig';
		$object->email = 'sletmig@somewhere.net';
		$object->userid = 'ss';
  }
}
