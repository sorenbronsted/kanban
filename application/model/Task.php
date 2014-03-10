<?php

class Task extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'title' => Property::STRING,
		'description' => Property::STRING,
		'points' => Property::INT,
		'estimate' => Property::INT,
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
		'title', 'position', 'tasktype_uid', 'taskstate_uid', 'project_uid'
	);

	public static function getByProjectUid($uid) {
		return self::getBy(array('project_uid' => $uid));
	}
	
	public static function getByState($uid) {
		$user = User::getCurrentUser();
		$sql = "select distinct t.*
						from task t
						join project p on p.uid = t.project_uid
						join userproject up on up.project_uid = p.uid
						join user u on u.uid = up.user_uid and u.userid = '$user->userid'
						where t.taskstate_uid = $uid
						order by p.name";
		return self::getObjects($sql);
	}

	public function save() {
		if ($this->uid == 0) {
			$this->created = new Timestamp();
		}
		parent::save();
	}
	
	public function destroy() {
		$works = Work::getByTaskUid($this->uid);
		if (count($works) > 0) {
			throw new ApplicationException("Opgaven kan ikke slettes da det indeholder timer");
		}
		parent::destroy();
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