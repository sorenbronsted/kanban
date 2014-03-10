<?php

class UserProject extends ModelObject {
	private static $properties = array(
		'uid' => Property::INT,
		'project_uid' => Property::INT,
		'user_uid' => Property::INT,
	);

	private static $mandatories = array(
		'user_uid', 'project_uid'
	);

	public function getMandatories() {
		return self::$mandatories;
	}
	
	protected function getProperties() {
		return self::$properties;
	}
}

?>