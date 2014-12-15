<?php

class AlterProject1 extends Ruckusing_Migration_Base {
	private $table = "project";
	
	public function up() {
		$this->change_column($this->table, 'description', 'text');
	}

	public function down() {
		$this->change_column($this->table, 'description', 'string', array("limit" => 512, "null" => true));
	}
}
