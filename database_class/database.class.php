<?php
/**
 * Database Class
 *
 * @author 		Dony Wahyu Isp
 * @copyright 	2015 Dony Wahyu Isp
 * @link 		http://github.io/dnaextrim/ajax-crud-bootstrap
 * @license		MIT
 * @version 	1.0.1
 * @package		Database Class
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
class Database {
	/**
	 * @var Kecik Class $app
	 **/
	private $app;

	/**
	 * @var string $dsn, $driver, $hostname, $username, $password, $dbname
	 **/
	private $dsn;
	private $driver;
	private $hostname;
	private $username;
	private $password;
	private $dbname;
	//-- End

	/**
	 * @var Driver Class $db
	 **/
	private $db = NULL;
	/**
	 * @var string $table
	 **/
	private $table = '';
	/**
	 * @var array $dsnuser ID: Driver yang menggunakan dsn | EN: Driver use dsn
	 **/
	private $dsnuse = array('pdo', 'oci8', 'pgsql', 'mongo');
	private $failOver = array();
	/**
 	 * __construct
 	 * @param Kecik $app
 	 **/
	public function __construct($config=array()) {
		$this->dsn = (isset($config['dsn']))?$config['dsn']:'';
		$this->driver = (isset($config['driver']))?strtolower($config['driver']):'';
		$this->hostname = (isset($config['hostname']))?$config['hostname']:'';
		$this->username = (isset($config['username']))?$config['username']:'';
		$this->password = (isset($config['password']))?$config['password']:'';
		$this->dbname = (isset($config['dbname']))?$config['dbname']:'';
	}

	/**
	 * connect
	 * @return res id
	 **/
	public function connect() {
		
		if (file_exists(dirname( __FILE__ )."/drivers/".$this->driver.".php")) {
			$this->db = include_once("drivers/".$this->driver.".php");
			$failover = (count($this->failOver) > 0)? TRUE:FALSE;

			$con = FALSE;
			if (in_array($this->driver, array('sqlite', 'sqlite3')))
				$con = @$this->db->connect($this->dbname, $failover);
			elseif (in_array($this->driver, $this->dsnuse))
				$con = @$this->db->connect($this->dsn, $this->dbname, $this->hostname, $this->username, $this->password, $failover);
			else
				$con = @$this->db->connect($this->dbname, $this->hostname, $this->username, $this->password, $failover);


			if (!$con) {
				header('X-Error-Message: Fail Connecting', true, 500);
				die('All Connecting Database Error');
				$this->db = NULL;
			}

			return $this->db;
			
		} else
			throw new \Exception('Database Library: Unknown Driver');
		
		
	}

	/**
	 * exec
	 * @param string $query
	 * @return res
	 **/
	public function exec($query) {
		$this->table = '';
		return $this->db->exec($query);
	}

	/**
	 * fetch
	 * @param res ID: dari exec() | EN: from exec()
	 * @return array object
	 **/
	public function fetch($res) {
		return $this->db->fetch($res);
	}

	/**
	 * affected
	 **/
	public function affected() {
		if (in_array($this->driver, array('sqlite', 'sqlite3','oci8')))
			echo 'Fungsi tidak support untuk driver '.$this->driver;
		
		return $this->db->affected();
	}

	/**
	 * __get
	 * @param string $table ID: nama table | EN: table name
	 **/
	public function __get($table) {
        $this->table = $table;
        return $this;
    }

    /**
     * insert
     * @param array $data
     * @param string $table
     * @return res
     **/
	public function insert($data, $table='') {
		$table = (!empty($this->table))?$this->table:$table;
		
		return $this->db->insert($table, $data);
	}

	/**
     * update
     * @param array $id primary key
     * @param array $data
     * @param string $table
     * @return res
     **/
	public function update($id, $data, $table='') {
		$table = (!empty($this->table))?$this->table:$table;
		
		return $this->db->update($table, $id, $data);
	}

	/**
     * insert
     * @param array $id primary key
     * @param string $table
     * @return res
     **/
	public function delete($id, $table='') {
		$table = (!empty($this->table))?$this->table:$table;

		return $this->db->delete($table, $id);
	}

	/**
     * insert
     * @param array $condition
     * @param string $table
     * @return res
     **/
	public function find($condition=[], $limit=[], $order_by=[],$table='') {
		$table = (!empty($this->table))?$this->table:$table;

		return $this->db->find($table, $condition, $limit, $order_by);
	}


	public function __destruct() {
		unset($this->db);
	}
}