<?php

class AlterTask1 extends Ruckusing_Migration_Base {
	private $table = "task";

	public function up() {
		$this->change_column($this->table, 'description', 'text');
	}

	public function down() {
		$this->change_column($this->table, 'description', 'string', array("limit" => 1024, "null" => true));
	}
}
