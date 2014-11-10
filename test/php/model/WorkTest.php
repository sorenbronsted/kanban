<?php
require_once 'test/settings.php';

class WorkTest extends BaseCrud {

  public function __construct() {
    parent::__construct("Work");
  }

  protected function createObject() {
    return Fixtures::getWork();
  }
  
  protected function updateObject($object) {
		$object->start = Date::parse('2014-01-01 10:37:19');
		$object->end = Date::parse('2014-01-10 10:37:19');;
		$object->user_uid = 2;
		$object->task_uid = 2;
  }
}
