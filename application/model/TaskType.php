<?php

class TaskType extends ModelObject {
	
	private static $properties = array(
		'uid' => Property::INT,
		'name' => Property::STRING,
	);

	private static $mandatories = array(
		'name', 'constraint'
	);

	public function getMandatories() {
		return self::$mandatories;
	}
	
	protected function getProperties() {
		return self::$properties;
	}
}

?>