<?php

class Fixtures {
	public static function getProject() {
		$object = new Project();
		$object->name = 'test';
		$object->description = 'test description';
		$object->created = new Timestamp();
		return $object;
	}

	public static function getUser() {
		$object = new User();
		$object->name = 'test';
		$object->userid = 'test';
		$object->email = 'test@somewhere.net';
		$object->deleted = 0;
		return $object;
	}

	public static function getWork() {
		$object = new Work();
		$object->start = Date::parse('2014-01-12 15:24:10');
		$object->end = Date::parse('2014-01-12 16:24:10');
		$object->user_uid = 1;
		$object->task_uid = 1;
		return $object;
	}

	public static function getComment() {
		$object = new Comment();
		$object->text = 'test';
		$object->created = new Timestamp();
		$user = User::getCurrentUser();
		$object->user_uid = $user->uid;
		$object->task_uid = 1;
		return $object;
	}

	public static function getTask() {
		$object = new Task();
		$object->title = 'test';
		$object->description = 'test';
		$object->points = 7;
		$object->estimate = 0;
		$object->position = 1;
		$object->created = new Timestamp();
		$object->tasktype_uid = 1;
		$object->taskstate_uid = 1;
		$object->project_uid = 1;
		$object->owner_uid = 1;
		$object->requester_uid = 1;
		return $object;
	}}

?>