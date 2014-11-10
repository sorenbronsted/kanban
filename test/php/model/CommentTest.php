<?php
require_once 'test/settings.php';

class CommentTest extends BaseCrud {
	private $user;
	
	protected function setUp() {
		$this->user = Fixtures::getUser();
		$this->user->save();
	}
	
	protected function tearDown() {
		Db::exec(DbObject::$db, "delete from user where uid = ".$this->user->uid);
	}
  
	public function __construct() {
    parent::__construct("Comment");
  }

  protected function createObject() {
    return Fixtures::getComment();
  }
  
  protected function updateObject($object) {
		$object->text = 'sletmig';
  }
}
