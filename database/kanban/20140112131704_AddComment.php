<?php

class AddComment extends Ruckusing_Migration_Base {
	private $table = 'comment';
	
	public function up() {
    $t = $this->create_table($this->table, array("id" => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
    $t->column("uid", "primary_key", array("primary_key" => true, "auto_increment" => true, "unsigned" => true, "null" => false));
    $t->column("text", "string", array("limit" => 1024, "null" => false));
    $t->column("created", "datetime", array("null" => false));
    $t->column("user_uid", "integer", array("null" => false));
    $t->column("task_uid", "integer", array("null" => false));
    $t->finish();
	}

	public function down() {
    $this->drop_table($this->table);
	}
}
