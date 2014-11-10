<?php
require_once 'test/settings.php';

class TaskTest extends BaseCrud {

  public function __construct() {
    parent::__construct("Task");
  }

  protected function createObject() {
    return Fixtures::getTask();
  }
  
  protected function updateObject($object) {
		$object->title = 'sletmig';
		$object->description = 'sletmig';
		$object->points = 0;
		$object->estimate = 5;
		$object->order = 2;
		$object->tasktype_uid = 2;
		$object->taskstate_uid = 2;
		$object->project_uid = 3;
		$object->owner_uid = 4;
		$object->requester_uid = 5;
  }
}
