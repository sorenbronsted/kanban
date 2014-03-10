<?php

class AddTaskType extends Ruckusing_Migration_Base {
	private $table = 'tasktype';
	
	public function up() {
    $t = $this->create_table($this->table, array("id" => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
    $t->column("uid", "primary_key", array("primary_key" => true, "auto_increment" => true, "unsigned" => true, "null" => false));
    $t->column("name", "string", array("limit" => 32, "null" => false));
    $t->finish();
		
		$this->execute("insert into tasktype values(1, 'feature')");
		$this->execute("insert into tasktype values(2, 'bug')");
		$this->execute("insert into tasktype values(3, 'release')");
	}

	public function down() {
    $this->drop_table($this->table);
	}
}
