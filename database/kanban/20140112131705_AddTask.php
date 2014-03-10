<?php

class AddTask extends Ruckusing_Migration_Base {
	private $table = 'task';
	
	public function up() {
    $t = $this->create_table($this->table, array("id" => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
    $t->column("uid", "primary_key", array("primary_key" => true, "auto_increment" => true, "unsigned" => true, "null" => false));
    $t->column("title", "string", array("limit" => 128, "null" => false));
    $t->column("description", "string", array("limit" => 1024, "null" => true));
    $t->column("points", "integer", array("null" => true));
    $t->column("estimate", "integer", array("null" => true));
    $t->column("position", "integer", array("null" => false));
    $t->column("created", "datetime", array("null" => false));
    $t->column("tags", "string", array("limit" => 256, "null" => true));
    $t->column("tasktype_uid", "integer", array("null" => false));
    $t->column("taskstate_uid", "integer", array("null" => false));
    $t->column("project_uid", "integer", array("null" => false));
    $t->column("owner_uid", "integer", array("null" => true));
    $t->column("requester_uid", "integer", array("null" => true));
    $t->finish();
	}

	public function down() {
    $this->drop_table($this->table);
	}
}
