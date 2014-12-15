<?php
require_once 'test/settings.php';

class TaskStateHistoryTest extends BaseCrud {
	
	protected function tearDown() {
		Db::exec(DbObject::$db, "delete from taskstatehistory");
		Db::exec(DbObject::$db, "delete from task");
	}
  
	public function __construct() {
    parent::__construct("TaskStateHistory");
  }

  protected function createObject() {
    return Fixtures::getTaskStateHistory();
  }
  
  protected function updateObject($object) {
		$object->from_uid = TaskState::DONE;
  }
	
	public function testStateChange() {
		$task = Fixtures::getTask();
		$task->save();
		
		$history = TaskStateHistory::getBy(array('task_uid' => $task->uid));
		$this->assertEquals(1, count($history));

		$task->description = "sletmig";
		$task->save();
		$history = TaskStateHistory::getBy(array('task_uid' => $task->uid));
		$this->assertEquals(1, count($history));

		$task->taskstate_uid = TaskState::PLANNED;
		$task->save();
		$history = TaskStateHistory::getBy(array('task_uid' => $task->uid));
		$this->assertEquals(2, count($history));
	}
}
