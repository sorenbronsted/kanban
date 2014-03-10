<?php

class Comment extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'text' => Property::STRING,
		'created' => Property::TIMESTAMP,
		'user_uid' => Property::INT,
		'task_uid' => Property::INT,
	);

	private static $mandatories = array('text', 'created', 'user_uid', 'task_uid');
	
	public static function getByTask($uid) {
		$sql = "select c.* from comment c ".
					 "join task t on t.uid = c.task_uid ".
					 "where t.uid = $uid order by c.created asc";
		return parent::getObjects($sql);
	}
	
	public function save() {
		if ($this->uid == 0) {
			$this->created = new Timestamp();
			$user = User::getCurrentUser();
			$this->user_uid = $user->uid;
		}
		parent::save();
	}

  protected function onJsonEncode($data) {
		$data['user'] = '';
		if  ($this->user_uid > 0) {
			try {
				$user = User::getByUid($this->user_uid);
				$data['user'] = $user->name;
			}
			catch(NotFoundException $e) {}
		}
		return $data;
	}

	public function getMandatories() {
		return self::$mandatories;
	}
	
	protected function getProperties() {
		return self::$properties;
	}
}

?>