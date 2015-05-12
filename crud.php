<?php
require("connection.php");

function insert() {
	global $db;

	if (count($_POST) > 0) {
		unset($_POST['username_old']);
		$data = array(
			'username' => $_POST['username'],
			'password' => md5($_POST['password']),
			'fullname' => $_POST['fullname'],
			'email' => $_POST['email'],
			'level' => $_POST['level']
		);

		$db->user->insert($_POST);

		//** Untuk Cara yang lebih mudah
		//$db->user->insert($_POST);
	}
}

function update() {
	global $db;

	if (count($_POST) > 0) {
		$pk = array('username' => $_POST['username_old']);
		unset($_POST['username_old']);

		$data = array(
			'username' => $_POST['username'],
			'password' => md5($_POST['password']),
			'fullname' => $_POST['fullname'],
			'email' => $_POST['email'],
			'level' => $_POST['level']
		);

		$db->user->update($pk, $_POST);

		/* 
		//** Untuk Cara yang lebih mudah
		$pk = array('username' => $_POST['username_old']);
		unset($_POST['username_old']);
		$db->user->update($pk, $_POST);
		*/
	}
}

function delete($pk) {
	global $db;

	if (!empty($pk)) {
		$db->user->delete($pk);
	}
}

function get() {
	global $db;

	$rows = $db->user->find(array(
		'where'=> array(
			array('username', '=', $_POST['username'])
		),
	));

	if (count($rows)>0)
		$rows[0]->password = '****';
	return $rows;
}

function select() {
	global $db;

	$rows = $db->user->find();
	foreach ($rows as $row)
		$row->actions = "{'username': '$row->username'}";

	return $rows;
}