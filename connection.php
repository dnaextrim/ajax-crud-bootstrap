<?php
require_once("database_class/database.class.php");
require_once("model_class/model.php");

/**
 * Database
 **/
//** MYSQL -> mysqli

$config = array(
	'driver' 		=> 'mysqli',
	'hostname'		=> 'localhost',
	'username'		=> 'root',
	'password'		=> '',
	'dbname'		=> 'kecik'
);
//-- END

//** POSTGRESQL -> pgsql
//-- Without DSN
/*
$config = array(
	'driver'		=> 'pgsql',
	'hostname'		=> 'localhost',
	'username'		=> 'postgres',
	'password'		=> '1234567890'
);
*/
//-- Width DSN
/*
$config = array(
	'driver'	=> 'pgsql',
	'dsn'		=> "host=localhost port=5432 dbname=kecik user=postgres password=1234567890 options='--client_encoding=UTF8'"
);
*/
//-- END

//** ORACLE -> oci8
/*
$config = array(
	'driver'	=> 'oci8',
	'dsn'		=> 'localhost/xe',
	'username'	=> 'kecik',
	'password'	=> '1234567890'
);
*/
//-- END

//** MongoDB -> mongo
/*
$config = array(
	'driver'	=> 'mongo',
	'dsn'		=> 'mongodb://localhost',
	'dbname'	=> 'kecik'
);
*/
//-- END

//** PDO
//-- MySQL
/*
$config = array(
	'driver'	=> 'pdo',
	'dsn'		=> 'mysql:host=localhost;dbname=kecik;',
	'username'	=> 'root',
	'password'	=> ''
);
*/
//-- PostgreSQL
/*
$config = array(
	'driver'	=> 'pdo',
	'dsn'		=> 'pgsql:host=localhost;dbname=kecik;',
	'username'	=> 'postgres',
	'password'	=> '1234567890'
);
*/
//-- Oracle
/*
$config = array(
	'driver' 	=> 'pdo',
	'dsn'		=> 'oci:host=localhost;dbname=xe;',
	'username'	=> 'kecik',
	'password'	=> '1234567890'
);
*/
//-- END

$db = new Database($config);
$db->connect();