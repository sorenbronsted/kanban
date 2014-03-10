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
		$sql = "select * from work where user_uid = $userUid and end is null";
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
		$cursor = Db::query($sql);
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

  protected function onJsonEncode($data) {
		$task = Task::getByUid($this->task_uid);
		$user = User::getByUid($this->user_uid);
		$project = Project::getByUid($task->project_uid);
		$data['task'] = $task->title;
		$data['user'] = $user->name;
		$data['project'] = $project->name;
		$data['hours'] = 0;
		if ($this->end != null) {
			$data['hours'] = round($this->end->diff($this->start) / (60 * 60), 2);
		}
		return $data;
	}
	
	protected function getProperties() {
		return self::$properties;
	}
}

?>