<?php

abstract class ModelObject extends DbObject {
  
  public function jsonEncode() {
    $tmp = array();
    foreach($this->getData() as $key => $value) {
      $tmp[$key] = (is_object($value) ? strval($value) : $value);
    }
    $tmp = $this->onJsonEncode($tmp);
    return json_encode($tmp);
  }
  
  protected function onJsonEncode($data) {
    return $data;
  }
  
  protected function getMandatoryErrors() {
    $mandatories = $this->getMandatories();
    $errors = array();
    $properties = $this->getProperties();
    foreach ($mandatories as $mandatory) {
      if (Property::isEmpty($properties[$mandatory], $this->$mandatory)) {
        $errors[$mandatory] = "Felt skal udfyldelse";
      }
    }
    return $errors;
  }

  protected function validateMandatories() {
    $errors = self::getMandatoryErrors();
    if (count($errors) > 0) {
      throw new ValidationException($errors);
    }
  }
 
  public function getMandatories() {}

  public function save() {
  	//DiContainer::instance()->sso->hasRole('write'); // This will throw an exception if not valid
		$this->validateMandatories();
    parent::save();
  }

  public function destroy() {
  	//DiContainer::instance()->sso->hasRole('delete'); // This will throw an exception if not valid
    parent::destroy();
  }

  public static function destroyBy(array $where) {
  	//DiContainer::instance()->sso->hasRole('delete'); // This will throw an exception if not valid
    parent::destroyBy($where);
  }
}
