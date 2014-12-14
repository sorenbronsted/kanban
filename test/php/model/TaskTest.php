<?php
require_once 'test/settings.php';

class TaskTest extends BaseCrud {

  public function __construct() {
    parent::__construct("Task");
  }

	protected function tearDown() {
		Db::exec(DbObject::$db, 'delete from project');	
		Db::exec(DbObject::$db, 'delete from user');	
		Db::exec(DbObject::$db, 'delete from task');	
		Db::exec(DbObject::$db, 'delete from taskstatehistory');	
		Db::exec(DbObject::$db, 'delete from work');
	}
	
  protected function createObject() {
    return Fixtures::getTask();
  }
  
  protected function updateObject($object) {
		$object->title = 'sletmig';
		$object->description = 'sletmig';
		$object->points = 0;
		$object->estimate = 5;
		$object->order = 2;
		$object->tasktype_uid = 2;
		$object->taskstate_uid = TaskState::READY;
		$object->project_uid = 3;
		$object->owner_uid = 4;
		$object->requester_uid = 5;
  }
	
	public function testStateHistory() {
		$task = Fixtures::getTask();
		$task->save();
	}
	
	public function testJson() {
		$project = Fixtures::getProject();
		$project->save();
		$task = $this->createObject();
		$task->project_uid = $project->uid;
		$task->save();
		$str = $task->jsonEncode();
		$this->assertNotEmpty($str);
		$this->assertContains('date_state', $str);
		$task->destroy();
		$project->destroy();
	}
	
	public function testGetStatByState() {
		$user = Fixtures::getUser();
		$user->save();
		$project = Fixtures::getProject();
		$project->save();
		$project->addUser($user->uid);
		
		$task1 = Fixtures::getTask();
		$task1->project_uid = $project->uid;
		$task1->estimate = 5;
		$task1->save();

		$work = new Work();
		$work->start = Timestamp::parse('2014-12-08 08:00:00');
		$work->end = Timestamp::parse('2014-12-08 08:47:00');
		$work->user_uid = $user->uid;
		$work->task_uid = $task1->uid;
		$work->save();
		
		$work = new Work();
		$work->start = Timestamp::parse('2014-12-08 08:00:00');
		$work->end = Timestamp::parse('2014-12-08 08:47:00');
		$work->user_uid = $user->uid;
		$work->task_uid = $task1->uid;
		$work->save();
		
		$task2 = Fixtures::getTask();
		$task2->project_uid = $project->uid;
		$task2->estimate = 3;
		$task2->save();
		
		$stat = Task::getStatByState(TaskState::READY)->getData();
		$this->assertEquals(8, $stat->estimate);
		$this->assertEquals(6.44, $stat->remainder);
		$this->assertEquals(1.57, round($stat->used,2));
		
		$stat = Task::getStatByProject($project->uid)->getData();
		$this->assertEquals(8, $stat->estimate);
		$this->assertEquals(6.44, $stat->remainder);
		$this->assertEquals(1.57, round($stat->used,2));
	}
}
