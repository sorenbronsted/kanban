<?php

class AddUser1 extends Ruckusing_Migration_Base {
	private $table = 'user';
	
	public function up() {
		$this->execute("insert into user values(0,'ingen', 'Ingen', '', 0)");
	}

	public function down() {
		$this->execute("delete from user where uid = 0");
	}
}
