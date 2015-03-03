<?php

class AlterTask extends Ruckusing_Migration_Base {
	private $table = "task";
	
	public function up() {
		$this->add_column($this->table, 'remainder', 'decimal', array("scale" => 2, "precision" => 5));
		$this->execute("update task set remainder = 0 where taskstate_uid in (4,5)");
		$this->execute("update task set remainder = estimate where taskstate_uid = 1");
		$this->execute(
			"update task t ".
			"left join (select task_uid, sum((to_seconds(end)-to_seconds(start))/(60*60)) as used from work group by task_uid) w on t.uid = w.task_uid  ".
			"set t.remainder = t.estimate - ifnull(w.used,0) ".
			"where taskstate_uid in (2,3,4)"
		);
		$this->execute(
			"update task set remainder = 0 where taskstate_uid in (2,4) and remainder < 0"
		);
	}

	public function down() {
		$this->remove_column($this->table, 'remainder');
	}
}
