<?php

class AddTaskState extends Ruckusing_Migration_Base {
	private $table = 'taskstate';
	
	public function up() {
    $t = $this->create_table($this->table, array("id" => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
    $t->column("uid", "primary_key", array("primary_key" => true, "auto_increment" => true, "unsigned" => true, "null" => false));
    $t->column("name", "string", array("limit" => 32, "null" => false));
    $t->column("constraint", "integer", array("default" => 0, "null" => false));
    $t->finish();
		
		$this->execute("insert into taskstate values(1, 'ready', 0)");
		$this->execute("insert into taskstate values(2, 'planned', 0)");
		$this->execute("insert into taskstate values(3, 'implementation', 1)");
		$this->execute("insert into taskstate values(4, 'test', 0)");
		$this->execute("insert into taskstate values(5, 'done', 0)");
	}

	public function down() {
    $this->drop_table($this->table);
	}
}
