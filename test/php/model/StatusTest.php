<?php
require_once 'PHPUnit/Autoload.php';
require_once 'test/settings.php';

class StatusTest extends PHPUnit_Framework_TestCase {
  protected $backupGlobals = FALSE;
	private $user;
	private $task;
	
	protected function setUp() {
		$this->user = Fixtures::getUser();
		$this->user->save();
		$this->task = Fixtures::getTask();
		$this->task->save();
	}
	
	protected function tearDown() {
		Db::exec(DbObject::$db, "delete from work where user_uid = ".$this->user->uid." and task_uid = ".$this->task->uid);
		$this->task->destroy();
		$this->user->destroy();
	}
	
  public function testGetReport() {
		for($i = 0; $i < 4; $i++) {
			$w = new Work();
			$w->user_uid = $this->user->uid;
			$w->task_uid = $this->task->uid;
			$w->start = new Timestamp();
			$ts = new Timestamp();
			$ts->hour += 1;
			$w->end = $ts;
			$w->save();
		}
		$start = new Date();
		$start->day = 1;
		$start->month = 1;
		$end = new Date();
		$end->day = 31;
		$end->month = 12;
		$result = Status::getReport($start->toString(), $end->toString(), 'userWork', 'week');
		$result = $result->getData();

		$current = new Date();
		$week = $current->format('Y').'-'.intval($current->format('W'));
		$fixture = array();
		$fixture[] = array('week', 'test', 'total');
		$fixture[] = array($week, 4, 4);
		$fixture[] = array('total', 4, 4);
		$this->assertEquals(count($fixture), count($result));
		foreach($fixture as $y => $values) {
			foreach($values as $x => $value) {
				$this->assertEquals($value, $result[$y][$x], "$y.$x");
			}
		}
  }
}
