<?php

/**
 * This class provides basic CRUD (Create,Read,Update,Delete) testing
 * on a class extending ModelObject
 * and it test mandatory properties on it as well.
 * You must override updateObject where you modify the properties of an object.
 */
abstract class BaseCrud extends PHPUnit_Framework_TestCase {
  private $class = null;
  protected $backupGlobals = FALSE;
  
  public function __construct($class) {
    $this->class = $class;
  }
  
  public function testCrud() {
    $uid = $this->create();
    $object = $this->read($uid);
    $this->update($object);
    $this->delete($object->uid);
  }

  public function testMandatories() {
    $uid = $this->create();
    $object = $this->read($uid);
    $mandatories = $object->getMandatories();
    foreach($mandatories as $mandatory) {
      $oldValue = $object->$mandatory;
      $object->$mandatory = null;
      try {
        $object->save();
        $this->fail("Expected ValidationException");
      }
      catch (ValidationException $e) {
        $this->assertNotNull($e->$mandatory);
        $object->$mandatory = $oldValue;
        //Ignore
      }
    }
    $this->delete($object->uid);
  }

  abstract protected function updateObject($object);
  
  abstract protected function createObject();

  protected function create() {
    $object = $this->createObject();
    $object->save();
    $this->assertGreaterThan(0, $object->uid);
    return $object->uid;
  }
  
  protected function read($uid) {
    $class = $this->class;
    $origin = $this->createObject();
    $origin->uid = $uid;
    $object = $class::getByUid($uid);
    $this->compareObject($origin, $object);
    return $object;
  }
  
  protected function update($object) {
    $this->updateObject($object);
    $object->save();
    $class = $this->class;
    $updated = $class::getByUid($object->uid);
    $this->compareObject($object, $updated);
  }
  
  protected function delete($uid) {
    $class = $this->class;
    $object = $class::getByUid($uid);
    $object->destroy();
    try {
      $class::getByUid($uid);
      $this->fail("Expected NotFoundException");
    }
    catch (NotFoundException $e) {
      $this->assertTrue(true);
    }
  }
  
  protected function compareObject($origin, $object) {
    $this->assertNotNull($origin);
    $this->assertNotNull($object);
    foreach($origin->getData() as $name => $value) {
      $this->assertEquals($value, $object->$name, $name);
    }
  }
}

?>