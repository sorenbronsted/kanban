<?php
require_once 'test/settings.php';

class ProjectTest extends BaseCrud {

  public function __construct() {
    parent::__construct("Project");
  }

  protected function createObject() {
    return Fixtures::getProject();
  }
  
  protected function updateObject($object) {
		$object->name = 'sletmig';
		$object->description = 'sletmig description';
  }
}
