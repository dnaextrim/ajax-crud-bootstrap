<?php
require("connection.php");
require("user.php");

function insert() {
	if (count($_POST) > 0) {
		$user = new Model\User();
			$user->username = $_POST['username'];
			$user->password = md5($_POST['password']);
			$user->fullname = $_POST['fullname'];
			$user->email = $_POST['email'];
			$user->level = $_POST['level'];
		$user->save();
	}
}

function update() {
	if (count($_POST) > 0) {
		$user = new Model\User(array('username' => $_POST['username_old']));
			$user->username = $_POST['username'];
			$user->password = md5($_POST['password']);
			$user->fullname = $_POST['fullname'];
			$user->email = $_POST['email'];
			$user->level = $_POST['level'];
		$user->save();
	}
}

function delete($pk) {
	if (!empty($pk)) {
		$user = new Model\User($pk);
		$user->delete();
	}
}

function get() {
	$rows = Model\User::findUsername($_POST['username']);
	if (count($rows) > 0)
		$rows[0]->password = '****';

	return $rows;
}

function select() {
	$rows = Model\User::find();
	foreach ($rows as $row)
		$row->actions = "{'username': '$row->username'}";

	return $rows;
}