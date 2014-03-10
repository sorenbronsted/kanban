<?php

class TestSso {
	public $authorized = true;
	
	public function hasRole($role) {
		if (!$this->authorized) {
			throw new NotAuthorizedException();
		}
	}
	
	public function getUserUid() {
		return "test";
	}
}

?>
