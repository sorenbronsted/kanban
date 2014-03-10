<?php

class User extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'userid' => Property::STRING,
		'name' => Property::STRING,
		'email' => Property::STRING,
		'deleted' => Property::BOOLEAN,
	);
	private static $mandatories = array('userid', 'name');

	public static function sync() {
		Db::exec("update user set deleted = 1");
		$users = DiContainer::instance()->sso->getUsersByRole('read');
		foreach($users as $user) {
			$u = null;
			try {
				$u = User::getOneBy(array("userid" => $user->userid));
			}
			catch(NotFoundException $e) {
				$u = new User();	
			}
			$u->deleted = false;
			$u->userid = $user->userid;
			$u->name = $user->name;
			$u->email = $user->email;
			$u->save();
		}
	}
	
	public static function getCurrentUser() {
		$userid = DiContainer::instance()->sso->getUserUid();
		return self::getOneBy(array('userid' => $userid));
	}
	
	public function getProjects() {
		$sql = "select up.uid, p.name from project p
					  join userproject up on up.project_uid = p.uid
						where up.user_uid = '".$this->uid."'";
		return parent::getObjects($sql);
	}
	
	public function destroy() {
		$this->deleted = 1;
		$this->save();
	}
	
	public function removeProject($uid) {
		UserProject::destroyBy(array('user_uid' => $this->uid, 'project_uid' => $uid));	
	}
	
	public function addProject($uid) {
		if ($this->uid == 0) {
			throw new ApplicationException("You need to save the user object first");
		}
		try {
			UserProject::getOneBy(array('user_uid' => $this->uid, 'project_uid' => $uid));
		}
		catch(NotFoundException $e) {
			$project = Project::getByUid($uid);
			$up = new UserProject();
			$up->user_uid = $this->uid;
			$up->project_uid = $uid;
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