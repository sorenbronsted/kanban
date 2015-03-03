<?php

class Task extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'title' => Property::STRING,
		'description' => Property::STRING,
		'points' => Property::INT,
		'estimate' => Property::INT,
		'remainder' => Property::DECIMAL,
		'position' => Property::INT,
		'created' => Property::TIMESTAMP,
		'tags' => Property::STRING,
		'tasktype_uid' => Property::INT,
		'taskstate_uid' => Property::INT,
		'project_uid' => Property::INT,
		'owner_uid' => Property::INT,
		'requester_uid' => Property::INT,
	);

	private static $mandatories = array(
		'title', 'position', 'tasktype_uid', 'project_uid'
	);

	public static function getByProjectUid($uid) {
		return self::getBy(array('project_uid' => $uid), array('taskstate_uid'));
	}
	
	public static function getByState($uid) {
		$user = User::getCurrentUser();
		$sql = "select distinct t.* ".
					 "from task t ".
					 "join project p on p.uid = t.project_uid ".
					 "join userproject up on up.project_uid = p.uid ".
					 "join user u on u.uid = up.user_uid and u.userid = '$user->userid' ".
					 "where t.taskstate_uid = $uid ".
					 "order by t.position, p.name";
		return self::getObjects($sql);
	}

	public static function getStatByState($taskStateUid) {
		$user = User::getCurrentUser();
		$sql = "select sum(t.estimate) as estimate, round(sum(t.remainder),2) as remainder, round(sum(w.used),2) as used ".
					 "from task t ".
					 "left join (select task_uid, sum((to_seconds(end)-to_seconds(start))/(60*60)) as used from work group by task_uid) w on t.uid = w.task_uid ".
					 "join project p on p.uid = t.project_uid ".
					 "join userproject up on up.project_uid = p.uid ".
					 "join user u on u.uid = up.user_uid and u.userid = '$user->userid' ".
					 "where t.taskstate_uid = $taskStateUid ";
		$cursor = Db::query(DbObject::$db, $sql);
		return new DataWrapper((object)$cursor->next());
	}
	
	public static function getStatByProject($projectUid) {
		$user = User::getCurrentUser();
		$sql = "select sum(t.estimate) as estimate, round(sum(t.remainder),2) as remainder, round(sum(w.used),2) as used ".
					 "from task t ".
					 "join project p on p.uid = t.project_uid ".
					 "left join (select task_uid, sum((to_seconds(end)-to_seconds(start))/(60*60)) as used from work group by task_uid) w on t.uid = w.task_uid ".
					 "where t.project_uid = $projectUid ";
		$cursor = Db::query(DbObject::$db, $sql);
		return new DataWrapper((object)$cursor->next());
	}
	
	public function save() {
		$oldStateUid = 0;
		if ($this->uid == 0) {
			$this->created = new Timestamp();
			$this->taskstate_uid = TaskState::READY;
			$this->remainder = $this->estimate;
		}
		else {
			$old = self::getByUid($this->uid);
			$oldStateUid = $old->taskstate_uid;
			if ($this->remainder < 0) {
				$this->remainder = 0;
			}
		}
		parent::save();
		TaskStateHistory::add($this->uid, $oldStateUid, $this->taskstate_uid);
	}
	
	public function destroy() {
		$works = Work::getByTaskUid($this->uid);
		if (count($works) > 0) {
			throw new ApplicationException("Opgaven kan ikke slettes da det indeholder timer");
		}
		parent::destroy();
	}
	
	public function setWorkedHours($hours) {
		$this->remainder -= $hours;
		$this->save();
	}
	
	public function setState($stateUid) {
		// same state => do nothing
		if ($this->taskstate_uid == $stateUid) {
			return;
		}
		
		$user = User::getCurrentUser();
		// if changing FROM implementation => stop the current work
		if ($this->taskstate_uid == TaskState::IMPLEMENTATION) {
			$work = Work::getCurrentWork($user->uid);
			$work->endWork();
		}
		
		// if changing TO implementation => stop the current work and start new work
		if ($stateUid == TaskState::IMPLEMENTATION) {
			try {
				$work = Work::getCurrentWork($user->uid);
				$work->endWork();
				$otherTask = self::getByUid($work->task_uid);
				$otherTask->taskstate_uid = TaskState::PLANNED;
				$otherTask->save();
			}
			catch(NotFoundException $e) {
				// do nothing
			}
			$this->owner_uid = $user->uid;
			Work::startWork($user->uid, $this->uid);
		}

		// set new state and save
		$this->taskstate_uid = $stateUid;
		$this->save();
	}
	
	public function getMandatories() {
		return self::$mandatories;
	}
	
  protected function onJsonEncode($data) {
		$type = TaskType::getByUid($this->tasktype_uid);
		$data['type'] = $type->name;
		$state = TaskState::getByUid($this->taskstate_uid);
		$data['state'] = $state->name;
		$dateState = TaskStateHistory::getDateByState($this);
		$data['date_state'] = $dateState != null ? $dateState->toString() : '';
		$project = Project::getByUid($this->project_uid);
		$data['project'] = $project->name;
		$data['used'] = Work::getUsedByTaskUid($this->uid);
		$data['owner'] = '';
		if  ($this->owner_uid > 0) {
			try {
				$owner = User::getByUid($this->owner_uid);
				$data['owner'] = $owner->name;
			}
			catch(NotFoundException $e) {}
		}
		$data['requester'] = '';
		if ($this->requester_uid > 0) {
			try {
				$requester = User::getByUid($this->requester_uid);
				$data['requester'] = $requester->name;
			}
			catch(NotFoundException $e) {}
		}
		return $data;
	}
	
	protected function getProperties() {
		return self::$properties;
	}
}

?>