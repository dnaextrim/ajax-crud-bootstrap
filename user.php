<?php
namespace Model;

User::$db = $db;

class User extends \Model {
	protected static $table = 'user';
	public static $db = NULL;

	public function __construct($id=array()) {
		parent::$db = self::$db;
		parent::__construct($id);
	}
	
}