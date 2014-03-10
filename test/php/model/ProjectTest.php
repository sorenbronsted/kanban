<?php
require_once 'PHPUnit/Autoload.php';
require_once 'test/settings.php';

class ProjectTest extends BaseCrud {
  protected $backupGlobals = FALSE;

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
