<?php

class Project extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'name' => Property::STRING,
		'description' => Property::STRING,
		'created' => Property::TIMESTAMP
	);
	
	private static $mandatories = array('name');
	
	public function save() {
		if ($this->uid == 0) {
			$this->created = new Timestamp();
		}
		parent::save();
	}

	public function destroy() {
		$users = UserProject::getBy(array('project_uid' => $this->uid));
		if (count($users) > 0) {
			throw new ApplicationException("Project kan ikke slettes, da det har tilknyttet brugere");
		}
		$tasks = Task::getByProjectUid($this->uid);
		if (count($tasks) > 0) {
			throw new ApplicationException("Projektet kan ikke slettes, da det indeholder opgaver");
		}
		parent::destroy();
	}
	
	public function getUsers() {
		$sql = "select up.uid, u.name from project p
					  join userproject up on up.project_uid = p.uid
						join user u on u.uid = up.user_uid
						where p.uid = ".$this->uid;
		return parent::getObjects($sql);
	}
	
	public function removeUser($uid) {
		UserProject::destroyBy(array('user_uid' => $uid, 'project_uid' => $this->uid));	
	}
	
	public function addUser($uid) {
		if ($this->uid <= 0) {
			throw new Exception("Project is not saved");
		}
		try {
			UserProject::getOneBy(array('user_uid' => $uid, 'project_uid' => $this->uid));
		}
		catch(NotFoundException $e) {
			$user = User::getByUid($uid);
			$up = new UserProject();
			$up->user_uid = $uid;
			$up->project_uid = $this->uid;
			$up->save();
		}
	}
	
	public function getMandatories() {
		return self::$mandatories;
	}
	
	protected function getProperties() {
		return self::$properties;
	}
}

?>