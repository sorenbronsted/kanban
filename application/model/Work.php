<?php

class Work extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'start' => Property::TIMESTAMP,
		'end' => Property::TIMESTAMP,
		'user_uid' => Property::INT,
		'task_uid' => Property::INT,
	);
	private static $mandatories = array('start', 'user_uid', 'task_uid');
	
	public static function getCurrentWork($userUid) {
		$sql = "select * from work where user_uid = $userUid and end = '0000-00-00 00:00:00' or end is null";
		$objects = self::getObjects($sql);
		self::verifyOne($objects);
		return $objects[0];
	}

	public static function getByTaskUid($uid) {
		return self::getWhere("task_uid = $uid order by start desc");
	}
	
	public static function getByUserUid($uid) {
		return self::getWhere("user_uid = $uid order by start desc");
	}
	
	public static function startWork($userUid, $taskUid) {
		$work = new Work();
		$work->start = new Timestamp();
		$work->user_uid = $userUid;
		$work->task_uid = $taskUid;
		$work->save(); 
	}
	
	public static function getUsedByTaskUid($taskUid) {
		$sql = "select round(sum(to_seconds(end)-to_seconds(start))/3600,2) as used ".
					 "from work where task_uid = $taskUid ".
					 "and start is not null and end is not null";
		$cursor = Db::query(static::$db, $sql);
		if ($cursor->hasNext()) {
			$v = $cursor->next();
			return $v['used'];
		}
		return 0;
	}

	public function endWork() {
		$this->end = new Timestamp();
		$this->save();
	}

	public function getMandatories() {
		return self::$mandatories;
	}

	public function save() {
		if (self::hasFieldChanged('end')) {
			$task = Task::getByUid($this->task_uid);
			$task->setWorkedHours($this->getHours());
		}
		parent::save();
	}
	
	public function getHours() {
		if ($this->end != null) {
			return round($this->end->diff($this->start) / (60 * 60), 2);
		}
		return 0;
	}
	
	public function destroy() {
		if ($this->end == null) {
			throw new ApplicationException("Kan ikke slettes da arbejdet er i gang. Stop opgaven først");
		}
		parent::destroy();
	}
	
  protected function onJsonEncode($data) {
		$task = Task::getByUid($this->task_uid);
		$user = User::getByUid($this->user_uid);
		$project = Project::getByUid($task->project_uid);
		$data['task'] = $task->title;
		$data['user'] = $user->name;
		$data['project'] = $project->name;
		$data['hours'] = 0;
		if ($this->end != null) {
			$data['hours'] = $this->getHours();
		}
		return $data;
	}
	
	protected function getProperties() {
		return self::$properties;
	}
}

?>