<?php

class TaskState extends ModelObject {
	const READY = 1;
	const PLANNED = 2;
	const IMPLEMENTATION = 3;
	const TEST = 4;
	const DONE = 5;
	
	private static $properties = array(
		'uid' => Property::INT,
		'name' => Property::STRING,
		'constraint' => Property::INT,
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