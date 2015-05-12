<?php
/*///////////////////////////////////////////////////////////////
 /** ID: | /-- ID: Indonesia
 /** EN: | /-- EN: English
 ///////////////////////////////////////////////////////////////*/

/**
 * Model
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.com/dnaextrim/ajax-crud-bootstrap
 * @license		MIT
 * @version 	1.0.2
 * @package		Model
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
class Model {
	protected static $db = NULL;
	protected static $table = '';
	protected static $_id;
	protected static $_data = array();
	protected $add = TRUE;
	protected $is_instance = FALSE;

	/**
	 * save
	 * Fungsi untuk menambah atau mengupdate record (Insert/Update)
	 * @return string SQL Query
	 **/
	public function save() {

		if (static::$table != '') {
			$table = static::$table;
			// Untuk menambah record
			if ($this->add == TRUE)
				$ret = self::$db->$table->insert(self::$_data);
			// Untuk mengupdate record
			else
				$ret = self::$db->$table->update(self::$_id, self::$_data);
			
			self::$_data = array();
		}

		
		return $ret;
	}

	/**
	 * delete
	 * Fungsi untuk menghapus record
	 * @return string SQL Query
	 **/
	public function delete() {
		$table = static::$table;

		if ($table != '') {

			if (self::$_id != '' || (is_array(self::$_id) && count(self::$_id) > 0) ) {
				//echo $table;
				$ret = self::$db->$table->delete(self::$_id);
				self::$_data = array();
			} else
				$ret = FALSE;
		}

		return $ret;
	}

	/**
	 * find
	 * function for select query
	 * @param Condition ['select', 'where', 'join']
	 * @param Limit [limit] or [offset, limit]
	 * @param Order By ['asc'=>['field1', 'field2'], 'desc'=>['field3']]
	 * @return array rows
	 **/
	public static function find($condition=array(), $limit=array(), $order_by=array()) {
		self::$db = static::$db;

		$table = static::$table;
		if (is_array(static::relational()) && count(static::relational()) > 0) {
			$relational = static::relational();
			if (!is_array($relational[0])) {
				$model = '\Model\\'.$relational[0];
				if (count($relational) == 1)
					$condition['join'][] = ['natural', $model::$table];
				elseif (count($relational) == 2)
					$condition['join'][] = ['left', $model::$table, $relational[1]];
				elseif (count($relational) == 3)
					$condition['join'][] = ['left', $model::$table, [$relational[1], $relational[2]]];
			} else {
				while(list($id, $relation) = each($relational) ) {
					$model = '\Model\\'.$relation[0];
					if (count($relation) == 1)
						$condition['join'][] = ['natural', $model::$table];
					elseif (count($relation) == 2)
						$condition['join'][] = ['left', $model::$table, $relation[1]];
					elseif (count($relation) == 3)
						$condition['join'][] = ['left', $model::$table, [$relation[1], $relation[2]]];
				}
			}
		}
		
		$rows = self::$db->$table->find($condition, $limit, $order_by);
		return $rows;
	}

	/**
	 * relational()
	 * Overide for relational
	 * @return array []
	 **/
	public static function relational() {
		return array();
	}

	/**
	 * call static findFieldOperator
	 * findName("'name'") or findNameNot("'name'") or findNameLike("'%name%'") or findNameNotLike("'%name%'") or
	 * findProgressBetween([80, 100]) or findProgressNotBetween([80, 100])
	 * @return FALSE or array rows
	 **/
	public static function __callStatic($name, $args) {
		if (substr($name, 0, strlen('find')) == 'find') {
			$name  = substr($name, strlen('find'));
			if ($name[0] == strtoupper($name[0])) {
				$optname = '';
				if (substr($name, -strlen('like')) == 'Like') {
					$optname = 'Like';
					$name = substr($name, 0, -strlen('like'));
				} elseif (substr($name, -\strlen('between')) == 'Between') {
					$optname = 'Between';
					$name = substr($name, 0, -strlen('between'));
				} elseif (substr($name, -\strlen('in')) == 'In') {
					$optname = 'in';
					$name = substr($name, 0, -strlen('in'));
				} else {
					$optname = '=';
					$field = $name;
				}

				if (substr($name, -strlen('not')) == 'Not') {
					$optname = ($optname == '=')? '<>':'Not '.$optname;
					$name = substr($name, 0, -strlen('not'));
				} 

				$name = ($name != strtoupper($name))? strtolower($name):$name;

				$condition = [
					'where' => [
						[$name, $optname, $args[0]]
					]
				];

				if (!isset($args[1])) $args[1] = array();
				if (!isset($args[2])) $args[2] = array();
				return self::find($condition, $args[1], $args[2]);
			}

			return FALSE;
		}
	}

	/**
	 * Model Constructor
	 * @param mixed $id
	 **/
	public function __construct($id='') {
		self::$db = static::$db;

		if ($id != '') {
			if (is_array($id))
				self::$_id = $id;
			else 
				self::$_id['id'] = $id;

			$this->add = FALSE;

		}

		$this->is_instance = TRUE;
	}

	public function __set($field, $value) {
		self::$_data[$field] = $value;
	}

	public function __get($field) {
		return stripslashes(self::$_data[$field]);
	}
} 