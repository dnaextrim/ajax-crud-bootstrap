<?php
/**
 * Driver SQLite
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.io/database
 * @license		MIT
 * @version 	1.0.3
 * @package		SQLite
 *
 * ID: Saya akan sangat berterimakasih jika anda memberikan donasi untuk ini, anda dapat memberikan donasi berapapun
 *     yang anda mau, saya juga akan mencantumkan nama anda sebagai donatur kedalam file khusus yang akan selalu 
 *     disertakan dalam source code ini, saya tidak akan mencantum jumlah donasi, saya hanya mencantumkan nama saja. Tolong
 *     cantumkan keterangan dengan isi "Donasi Ajax Crud Bootstrap".
 * EN: I would be very grateful if you make a donation to this, you can donate whatever you want, I will put your name 
 *     as a donor into a special file will always be included in the source code, I would not fasten the number of 
 *     donations, I just included name only. Please include a description with the contents of the "Donate Ajax Crud Bootstrap".
 * 
 * PayPal: dony_cavalera_md@yahoo.com
 * Rekening Mandiri: 113-000-6944-858, Atas Nama: Dony Wahyu Isprananda
 **/
class Kecik_SQLite {
	private $dbcon=NULL;

	private $_select = '';

	public function __construct() {

	}

	public function connect($dbname, $failover=FALSE) {
		$mode = '0666';
		$this->dbcon = @sqlite_open($dbname, $mode, $error_connect);

		if ($failover === FALSE) {
			if ( $error_connect ) {
			    header('X-Error-Message: Fail Connecting', true, 500);
			    die("Failed to connect to Sqlite: " . $error_connect);
			}
		}
		
		return $this->dbcon;
	}

	public function exec($sql) {
		if (strtolower( substr($sql, 0,6) ) == 'select') {
			$res = sqlite_query($this->dbcon, $sql, SQLITE_BOTH, $error_msg);
			if ($error_msg)
				echo 'Query Error: '.$error_msg;
		} else {
			$res = sqlite_exec($this->dbcon, $sql, $error_msg);
			if ($error_msg)
				echo 'Query Error: '.$error_msg;
		}

		return $res;
	}

	public function fetch($res) {
		$result = array();
		$rows = sqlite_fetch_all($res, SQLITE_ASSOC);

		foreach ($rows as $data) 
		    $result[] = (object) $data;

		return $result;
	}

	public function affected() {
        return NULL;
    }

	public function __destruct() {
		sqlite_close($this->dbcon);
	}

	public function insert($table, $data) {
		$table = (!empty($this->table))?$this->table:$table;
		$fields = $values = array();

		while (list($field, $value) = each($data)) {
			$fields[] = "`$field`";
			$values[] = "'".sqlite_escape_string($value)."'";
		}

		$fields = implode(',', $fields);
		$values = implode(',', $values);
		$query = "INSERT INTO `$table` ($fields) VALUES ($values)";

		return $this->exec($query);
	}

	public function update($table, $id, $data) {
		$fieldsValues = array();
		$and = array();

		while(list($pk, $value) = each($id)) {
			$value = sqlite_escape_string($value);
			if (preg_match('/<|>|!=/', $value))
				$and[] = "`$pk`$value";
			else
				$and[] = "`$pk`='$value'";
		}

		$where = '';
		if (count($id) > 0)
			$where = 'WHERE '.implode(' AND ', $and);

		while (list($field, $value) = each($data)) {
			$fieldsValues[] = "`$field`='".sqlite_escape_string($value)."'";
		}

		$fieldsValues = implode(',', $fieldsValues);
		$query = "UPDATE `$table` SET $fieldsValues $where";
		return $this->exec($query);
	}

	public function delete($table, $id) {
		$fieldsValues = array();
		$and = array();

		while(list($pk, $value) = each($id)) {
			$value = sqlite_escape_string($value);
			if (preg_match('/<|>|!=/', $value))
				$and[] = "`$pk`$value";
			else
				$and[] = "`$pk`='$value'";
		}

		$where = '';
		if (count($id) > 0)
			$where = 'WHERE '.implode(' AND ', $and);

		$query = "DELETE FROM `$table` $where";
		return $this->exec($query);
	}

	public function find($table, $condition='') {
		$ret = array();
		$query = "SELECT ";
		$query .= (empty($this->_select))?'* ':$this->_select;
		$query .="FROM $table ";
		if ($res = $this->exec($query))
			$ret = $this->fetch($res);
		else
			sqlite_last_error($this->dbcon);
		
		return $ret;
	}
}

return new Kecik_SQLite;