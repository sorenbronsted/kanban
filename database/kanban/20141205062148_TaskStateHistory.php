<?php

class TaskStateHistory extends Ruckusing_Migration_Base {
	private $table = 'taskstatehistory';
	
	public function up() {
    $t = $this->create_table($this->table, array("id" => false, 'options' => 'Engine=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci'));
    $t->column("uid", "primary_key", array("primary_key" => true, "auto_increment" => true, "unsigned" => true, "null" => false));
    $t->column("task_uid", "integer", array('null' => false));
    $t->column("from_uid", "integer");
    $t->column("to_uid", "integer");
    $t->column("ts_changed", "datetime", array("null" => false));
    $t->finish();
		
		// Set all task to initial ready state
		$this->execute(
			"insert into taskstatehistory(task_uid, from_uid, to_uid, ts_changed) ".
			"select t.uid, 0, 1, created from task t"
		);
		
		// Add and calculate done state for task that is done
		$this->execute(
			"insert into taskstatehistory(task_uid, from_uid, to_uid, ts_changed) ".
			"select t.uid, 1, 5, date_add(created, interval ((used/(60*60)/8)*24*60*60) second) ".
			"from task t ".
			"join (select task_uid, sum(end-start) used from work group by task_uid) w on t.uid = w.task_uid ".
			"where t.taskstate_uid = 5"
		);
	}

	public function down() {
    $this->drop_table($this->table);
	}
}
