<?php
/**
 * Driver PostgrSQL
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.io/database
 * @license		MIT
 * @version 	1.0.3
 * @package		PostgrSQL
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
class Kecik_PostgreSQL {
	private $dbcon=NULL;

	private $_select = '';

	public function __construct() {

	}

	public function connect($dsn, $dbname='', $hostname='', $username='root', $password='', $failover=FALSE) {
		$hostname = (empty($hostname))?'':"host=$hostname";
		$username = (empty($username))?'':"user=$username";
		$password = (empty($password))?'':"password=$password";
		$dbname   = (empty($dbname))?'':"dbname=$dbname";
		if (empty($dsn))
			$conn_string = "$hostname port=5432 $dbname $username $password options='--client_encoding=UTF8'";
		else
			$conn_string = $dsn;

		$this->dbcon = @pg_connect($conn_string);
		
		if ($failover === FALSE) {
			if ( !$this->dbcon ) {
			    header('X-Error-Message: Fail Connecting', true, 500);
			    die("Failed to connect to PostgreSQL");
			}
		}
		
		return $this->dbcon;
	}

	public function exec($sql) {
		$res = pg_query($this->dbcon, $sql);
		if (!$res){
			echo "<strong>Query: ".$sql."</strong><br />";
			echo 'Query Error: '.pg_last_error($this->dbcon);
		}

		return $res;
	}

	public function fetch($res) {
		$result = array();
		while( $data = pg_fetch_object($res) ) {
			$result[] = $data;
		}

		return $result;
	}

	public function affected() {
        return pg_affected_rows($this->dbcon);
    }

	public function __destruct() {
		if ($this->dbcon)
			pg_close($this->dbcon);
	}

	public function insert($table, $data) {
		$table = (!empty($this->table))?$this->table:$table;
		$fields = $values = array();

		while (list($field, $value) = each($data)) {
			$fields[] = $field;

			if (is_array($value) && $value[1] == FALSE)
				$values[] = $value[0];
			else {
				$value = addslashes($value);
				if (!is_numeric($value))
					$value = "'$value'";

				$values[] = $value;
			}
		}

		$fields = implode(',', $fields);
		$values = implode(',', $values);
		$query = "INSERT INTO $table ($fields) VALUES ($values)";

		return $this->exec($query);
	}

	public function update($table, $id, $data) {
		$fieldsValues = array();
		$and = array();

		while(list($pk, $value) = each($id)) {

			if (is_array($value) && $value[1] == FALSE)
				$value = $value[0];
			else {
				$value = addslashes($value);
				if (!is_numeric($value))
					$value = "'$value'";
			}

			if (preg_match('/<|>|!=/', $value))
				$and[] = "$pk$value";
			else
				$and[] = "$pk=$value";
		}

		$where = '';
		if (count($id) > 0)
			$where = 'WHERE '.implode(' AND ', $and);

		while (list($field, $value) = each($data)) {
			if (is_array($value) && $value[1] == FALSE)
				$fieldsValues[] = "#field=".$value[0];
			else {
				$value = addslashes($value);
				if (!is_numeric($value))
					$value = "'$value'";

				$fieldsValues[] = "$field=".$value;
			}
		}

		$fieldsValues = implode(',', $fieldsValues);
		$query = "UPDATE $table SET $fieldsValues $where";
		return $this->exec($query);
	}

	public function delete($table, $id) {
		$fieldsValues = array();
		$and = array();

		while(list($pk, $value) = each($id)) {
			if (is_array($value) && $value[1] == FALSE)
				$value = $value[0];
			else {
				$value = addslashes($value);
				if (!is_numeric($value))
					$value = "'$value'";
			}

			if (preg_match('/<|>|!=/', $value))
				$and[] = "$pk$value";
			else
				$and[] = "$pk='$value'";
		}

		$where = '';
		if (count($id) > 0)
			$where = 'WHERE '.implode(' AND ', $and);

		$query = "DELETE FROM $table $where";
		return $this->exec($query);
	}

	public function find($table, $condition=array(), $limit=array(), $order_by=array()) {
		$ret = array();
		$query = QueryHelper::find($table, $condition, $limit, $order_by);
		if ($res = $this->exec($query))
			$ret = $this->fetch($res);
		else 
			echo pg_last_error($this->dbcon);
		
		return $ret;
	}
}

class QueryHelper {
	public static function select($list) {
		$select = [];

		if (is_array($list) && count($list) > 0) {
			while(list($idx, $selectlist) = each($list)) {
				while(list($id, $fields) = each($selectlist)) {
					

					if (is_int($id)) {
						if (count($selectlist)>1)
							$select[] = $fields;
						else
							$select[] = $fields;

					} elseif (is_string($fields)) {
						if (strtoupper($id) != 'AS')
							$select[] = strtoupper($id)."($fields)";
						else
							$select[count($select)-1] .= " AS $fields";
					}
				}
			}
			
			$ret = implode(', ', $select).' ';
		}

		return (!isset($ret))?' * ':$ret;
	}

	public static function where($list, $group='and', $idx_where=1, $group_prev='') {
		$ret = '';
		$where = ['and'=>[], 'or'=>[]];
		$opt = ['and', 'or'];
		$optrow = [];
		$wherestr = '';

		if (is_array($list) && count($list) > 0) {
			while (list($idx, $wherelist) = each($list)) {
				
				//sub operator
				if ( is_string($idx) && in_array($idx, $opt)) {
					$where[$idx][] .= '('.self::where($wherelist, $idx, $idx_where+1, $idx).')';
					$optrow[] = $idx;
				//logical
				} elseif (is_array($wherelist)) {
					
					if (count($wherelist) == 1 && isset($wherelist[0])) {
						$where[$group][] = $wherelist[0];
					} else {
						$id_step = 0;
						while (list($cond, $val) = each($wherelist)) {
							// if Count item 1
							if (count($wherelist) == 1) {
								
								for( $i=0; $i <= substr_count($cond, '?'); $i++) {
									$pos = strpos($cond, '?');
									$tmp = substr($cond, 0, $pos);
									$tmp .= $val[$i];
									$cond = $tmp.substr($cond, $pos+1);
								}


								$where[$group][] = $cond;
							// if Count item 2
							} elseif (count($wherelist) == 2) {
								
								if (is_array($val)) {
									while(list($condkey, $condval) = each($val)) {
										for( $i=0; $i <= substr_count($cond, '?'); $i++) {
											$pos = strpos($cond, '?');
											$tmp = substr($cond, 0, $pos);
											$tmp .= $condval;
											$cond = $tmp.substr($cond, $pos+1);
										}
									}
								} else {
									if ($id_step == 0) {
										$condfield = $val;
										$id_step++;
										continue;
									} else {
										$val = addslashes($val);
										if (!is_numeric($val))
											$val = "'$val'";

										$cond = $val;
									}
								}

								$where[$group][] = $condfield.$cond;
								$condfield = '';
							// if Count item 3
							} elseif (count($wherelist) == 3) {
								if (is_array($val) && !is_int($cond)) {
									while(list($condkey, $condval) = each($val)) {
										for( $i=0; $i <= substr_count($cond, '?'); $i++) {
											$pos = strpos($cond, '?');
											$tmp = substr($cond, 0, $pos);
											$tmp .= $condval;
											$cond = $tmp.substr($cond, $pos+1);
										}
									}
								} else {
									if ($id_step == 0) {
										$condfield = $val;
										$id_step++;
										continue;
									} elseif($id_step == 1) {
										if (in_array($val, ['=','!=','<>','<','>','<=','>=']))
											$condopt = $val;
										else {
											$condopt = ' '.strtoupper($val).' ';
										}
										$id_step++;
										continue;
									} else {
										if (is_array($val)) {
											if (trim($condopt) == 'BETWEEN')
												$val = implode(' AND ', $val);
											else
												$val = '('.implode(', ', $val).')';
										}

										if (is_array($val) && $val[1] == FALSE)
											$val = $val[0];
										else {
											$val = addslashes($val);
											if (!is_numeric($val))
												$val = "'$val'";
										}

										$cond = $val;
									}
								}
								
								$where[$group][] = $condfield.$condopt.$cond;
								$condfield = '';
							}
						}

					}
				}
			}

			if ($idx_where == 1 && (count($where['and'] > 0) || count($where['or'] > 0)) )
				$ret = ' WHERE ';

			if (count($optrow) > 0) {
				
				while (list($id, $opt) = each($optrow)) {
					$wherestr .= implode(' '.strtoupper($opt).' ', $where[$opt]);
					if ($id == 0 && $idx_where == 2) $wherestr .= ' '.strtoupper($group_prev).' ';
				}

				$ret .= $wherestr;
			} else
				$ret .= implode(' '.strtoupper($group).' ', $where[$group]);//.$wherestr;
		}

		return $ret;
	}

	public static function join($table, $list) {
		$ret = '';
		$join = [];
		if (is_array($list) && count($list) > 0) {
			while (list($idx, $joinlist) = each($list)) {
				if (count($joinlist) == 2)
					$join[] = strtoupper($joinlist[0]).' JOIN '.$joinlist[1];
				elseif (count($joinlist) == 3) {
					if (is_array($joinlist[2]) && count($joinlist[2]) == 2) {
						$on1 = $joinlist[2][0];
						$on2 = $joinlist[2][1];
						$join[] = strtoupper($joinlist[0])." JOIN $joinlist[1] ON $joinlist[1].$on1 = $table.$on2";
					} else {
						$join[] = strtoupper($joinlist[0])." JOIN $joinlist[1] ON $joinlist[1].$joinlist[2] = $table.$joinlist[2]";
					}
				}
			}

			$ret = ' '.implode(' ', $join);
		}

		return $ret;
	}

	public static function union($list) {
		$ret = '';
		
		if (is_array($list) && count($list) > 0) {
			$ret = ' UNION '.implode(', ', $list);
		}

		return $ret;
	}

	public static function find($table, $filter=array(), $lmt=array(), $odr_by=array()) {
		$select = '';
		$from = "FROM $table";
		$where = '';
		$limit = '';
		$order_by = '';
		$union = '';

		if (is_array($filter) && count($filter) > 0) {
			
			while(list($syntax, $query) = each($filter)) {
				$syntax = strtoupper($syntax);

				switch ($syntax) {
					case 'SELECT':
						$select .= self::select($query);
					break;
					
					case 'WHERE':
						$where = self::where($query);
					break;

					case 'JOIN':
						$from .= self::join($table, $query);
					break;

					case 'UNION':
						$union = self::union($query);
					break;

					default:
					# code...
					break;
				}
			}
			
		}

		if (is_array($lmt) && count($lmt) > 0) {
			if (!isset($lmt[1]))
				$limit = " LIMIT $lmt[0]";
			else
				$limit = " LIMIT $lmt[1] OFFSET $lmt[0]";
		}

		if (is_array($odr_by) && count($odr_by) > 0) {
			$ord = ['asc'=>[], 'desc'=>[]];
			while(list($sort, $fields) = each($odr_by)) {
				if (strtoupper($sort) == 'ASC') {
					$ord['asc'][] = implode(', ', $fields).' ASC';
				} elseif (strtoupper($sort) == 'DESC') {
					$ord['desc'][] = implode(', ', $fields).' DESC';
				}

			}

			if (count($ord['asc']) > 0 || count($ord['desc'])) {
				$order = [];
				if (count( $ord['asc'] ) > 0)
					$order[] .= implode(', ', $ord['asc']).' ';
				if (count( $ord['desc'] ) > 0)
					$order[] .= implode(', ', $ord['desc']).' ';
				$order = implode(', ', $order);
			} else
				$order = '';

			$order_by = ' ORDER BY '.$order;
		}


		$select = (empty($select))? '* ':$select;
		$sql = 'SELECT '.$select.$from.$where.$order_by.$limit.$union;

		return $sql;
	}

}

return new Kecik_PostgreSQL;