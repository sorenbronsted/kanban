<?php
require_once 'test/settings.php';

class WorkTest extends BaseCrud {
	private $task;
	
  public function __construct() {
    parent::__construct("Work");
  }

	protected function setUp() {
		$this->task = Fixtures::getTask();
		$this->task->save();
	}
	
	protected function tearDown() {
		Db::exec(DbObject::$db, "delete from taskstatehistory");
		Db::exec(DbObject::$db, "delete from task");
		Db::exec(DbObject::$db, "delete from work");
	}

  protected function createObject() {
    $work = Fixtures::getWork();
		$work->task_uid = $this->task->uid;
		return $work;
  }
  
  protected function updateObject($object) {
		$object->start = Date::parse('2014-01-01 10:37:19');
		$object->end = Date::parse('2014-01-10 10:37:19');;
		$object->user_uid = 2;
  }
}
