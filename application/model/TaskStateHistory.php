<?php

class TaskStateHistory extends ModelObject {
	
	private static $properties = array(
		'uid' => Property::INT,
		'task_uid' => Property::INT,
		'from_uid' => Property::INT,
		'to_uid' => Property::INT,
		'ts_changed' => Property::TIMESTAMP,
	);

	private static $mandatories = array(
		'task_uid', 'to_uid'
	);

	public static function add($taskUid, $fromUid, $toUid) {
		if ($taskUid == 0) {
			throw new IllegalArgumentException("Task.uid", __FILE__, __LINE__);
		}
		
		if ($fromUid == $toUid) {
			return;
		}
		
		$o = new TaskStateHistory();
		$o->task_uid = $taskUid;
		$o->from_uid = $fromUid;
		$o->to_uid = $toUid;
		$o->save();
	}
	
	public static function getDateByState(Task $task) {
		$result = null;
		try {
			$sql = "select * from taskstatehistory where task_uid = ".$task->uid.
						 " and to_uid = ".$task->taskstate_uid." order by ts_changed desc limit 1";
			$objects = self::getObjects($sql);
			self::verifyOne($objects);
			$result = $objects[0]->ts_changed;
		}
		catch (NotFoundException $e) {
			//
		}
		return $result;
	}
	
	public function getMandatories() {
		return self::$mandatories;
	}
	
	public function save() {
		$this->ts_changed = new Timestamp();
		parent::save();
	}
	
	protected function getProperties() {
		return self::$properties;
	}
}
