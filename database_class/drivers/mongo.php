<?php
/**
* Driver MongoDB
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.io/dnaextrim/ajax-crud-bootstrap
 * @license		MIT
 * @version 	1.0.3
 * @package		MongoDB
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
class Kecik_Mongo {
	private $dbcon=NULL;

	private $db;
	private $_select = '';

	private $lastSQL = '';
	public function __construct() {

	}

	public function connect($dsn, $dbname, $hostname='mongodb://localhost:27017', $username='', $password='', $failover=FALSE) {
		$con_string='';
		if (empty($dsn)) {
			if (substr(strtolower($hostname), 0, 10) != 'mongodb://') $con_string= 'mongodb://'.$hostname;
			if (strpos($hostname, ':')<=0) $con_string.=':27017';

			if (!empty($username) || !empty($password))
				$con_string="mongodb://$username:$password@$hostname";
		} else
			$con_string = $dsn;
			
		$this->dbcon = new MongoClient($con_string);
		
		if ($failover === FALSE) {
			if ( !$this->dbcon ) {
			    header('X-Error-Message: Fail Connecting', true, 500);
			    die("Failed to connect to MongoDB: ");
			}
		}

		$this->db = $this->dbcon->selectDB($dbname);
		return $this->dbcon;
	}

	public function exec($sql) {
		$this->lastSQL = $sql;
		$res = $this->db->execute($sql);
		if (!$res) {
			echo "<strong>Query: ".$sql."</strong><br />";
			echo 'Query Error '.$this->db->lastError();
		}

		return $res;
	}

	public function fetch($res) {
		$res = $this->db->execute($this->lastSQL.'.toArray()');
		$this->lastSQL = '';
		$result = array();
		foreach ($res['retval'] as $data) {
			$result[] = (object) $data;
		}
		return $result;
	}

	public function affected() {
        return mysqli_affected_rows($this->dbcon);
    }

	public function __destruct() {
		$this->dbcon->close();
	}

	public function insert($table, $data) {
		return $this->db->$table->insert($data);
	}

	public function update($table, $id, $data) {
		return $this->db->$table->update($id, array('$set'=>$data));
	}

	public function delete($table, $id) {
		return $this->db->$table->remove($id);
	}

	public function find($table, $condition=array(), $limit=array(), $order_by=array()) {
		$query = QueryHelper::find($table, $condition, $limit, $order_by);
		$res = $this->exec($query);
		return $this->fetch($res);
	}
}

class QueryHelper {
	public static function select($list) {
		$select = [];

		if (is_array($list) && count($list) > 0) {
			while(list($idx, $selectlist) = each($list)) {
				while(list($id, $fields) = each($selectlist)) {
					
					if (is_int($id)) {
						if (!strpos($fields, ':') && count($selectlist)>0) {

							$slct = explode(',', $fields);
							if (count($slct) > 1) {
								while (list($id, $field) = each($slct)) {
									$slct[$id] .= ':1';
								}
								$select[] = implode(',', $slct);
							} else
								$select[] = $fields.':1';
						} else 
							$select[] = $fields;

					} elseif (is_string($fields)) {
						if (strtoupper($id) != 'AS')
							$select[] = strtoupper($id)."(`$fields`)";
						else
							$select[count($select)-1] .= " AS `$fields`";
					}
				}
			}
			
			$ret = implode(', ', $select);
		}

		return (!isset($ret))?'':'{'.$ret.'}';
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
					$where[$idx][] .= self::where($wherelist, $idx, $idx_where+1, $idx);
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
										$condfield = ''.$val.'';
										$id_step++;
										continue;
									} else {
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
										$condfield = $val.'';
										$id_step++;
										continue;
									} elseif($id_step == 1) {
										if (in_array($val, ['=','!=','<>','<','>','<=','>='])) {
											if ($val == '=') $val .= '=';
											$condopt = $val;
										} else {
											$condopt = ' '.strtoupper($val).' ';
										}
										$id_step++;
										continue;
									} else {
										if (is_array($val)) {
											if (substr(trim($condopt), -7) == 'BETWEEN')
												$val = "\$gte: $val[0], \$lte: $val[1]";
											else
												$val = '$in: ['.implode(', ', $val).']';
											$val = "{".$val."}";
										}

										
										if (substr(trim($condopt), -4) == 'LIKE') {
											if (substr($val, 0, 1) == '%')
												$val = '/'.substr($val,1).'$/i';
											elseif (substr($val, -1, 1) == '%')
												$val = '/^'.substr($val, 0, strlen($val)-1).'/i';
											else
												$val = '/'.substr($val, 1, strlen($val)-1).'/i';
										} 

										if (substr(trim($condopt), 0, 4) == 'NOT ')
											$val = '{ $not: '.$val.'}';
										

										$cond = $val;
									}
								}
								if (strtoupper($group) == 'OR') {
									$where[$group][] = '{'.$condfield.': '.$cond.'}';
								} else
									$where[$group][] = $condfield.': '.$cond;
								
								$condfield = '';
							}
						}

					}
				}

			}

			//if ($idx_where == 1 && (count($where['and'] > 0) || count($where['or'] > 0)) )
				//$ret = '$where: "';

			if (count($optrow) > 0) {
				
				while (list($id, $opt) = each($optrow)) {
					$wherestr .= implode(', ', $where[$opt]);
					if ($id == 0 && $idx_where == 2) $wherestr .= ' '.strtoupper($group_prev).' ';
				}

				$ret .= $wherestr;
			} else {
				$ret .= implode(', ', $where[$group]);
				if (strtoupper($group) == 'OR')
					$ret = '$or: ['.$ret.']';
			}
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

			$ret = ' '.implode(', ', $join);
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
		$from = "FROM `$table`";
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
				$limit = ".limit($lmt[0])";
			else
				$limit = ".skip($lmt[0]).limit($lmt[1])";
		}

		if (is_array($odr_by) && count($odr_by) > 0) {
			$ord = ['asc'=>[], 'desc'=>[]];
			while(list($sort, $fields) = each($odr_by)) {
				if (strtoupper($sort) == 'ASC') {
					while(list($id, $field) = each($fields))
						$ord['asc'][] = $field.': 1';
				} elseif (strtoupper($sort) == 'DESC') {
					while(list($id, $field) = each($fields))
						$ord['desc'][] = $field.': -1';
				}

			}

			if (count($ord['asc']) > 0 || count($ord['desc'])) {
				$order = [];
				if (count( $ord['asc'] ) > 0)
					$order[] .= implode(', ', $ord['asc']);
				if (count( $ord['desc'] ) > 0)
					$order[] .= implode(', ', $ord['desc']);
				$order = implode(', ', $order);
			} else
				$order = '';

			$order_by = ".sort({".$order."})";
		}


		$select = (empty($select))? '':', '.$select;
		//$sql = 'SELECT '.$select.$from.$where.$order_by.$limit.$union;
		$sql = "db.$table.find({ $where }$select)".$order_by.$limit;
		return $sql;
	}

}

return new Kecik_Mongo();