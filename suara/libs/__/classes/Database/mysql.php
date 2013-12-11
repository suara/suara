<?php
final class mysql {
	private $config = null;
	public $link = null;
	public $lastqueryid = null;

	public $querycount = 0;

	public function __construct(){

	}

	public function open($config){
		$this->config = $config;
		if ($config["autoconnect"] == 1) {
			$this->connect();
		}
	}

	//connect Mysql
	public function connect(){
		$func = $this->config["pconnect"] == 1 ? "mysql_pconnect" : "mysql_connect";
		if (!$this->link = @$func($this->config["hostname"], $this->config["username"], $this->config["password"], 1)) {
			$this->halt("Cant connect to MySQL server");
			return false;
		}

		if ($this->version() > "4.1") {
			$charset = isset($this->config["charset"]) ? $this->config["charset"] : "";
			$serverset = $charset ? 'character_set_connection='.$charset.', character_set_results='.$charset.', character_set_client=binary' : '';
			$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
			$serverset && mysql_query("SET $serverset", $this->link);
		}

		if ($this->config["database"] && !@mysql_select_db($this->config["database"], $this->link)) {
			$this->halt("Cant use database ". $this->config["database"]);
			return false;
		}

		$this->database = $this->config["database"];
		return $this->link;
	}

	//database query method
	//return mysql query handler
	public function execute($sql) {
		if (!is_resource($this->link)) {
			$this->connect();
		}
		$this->lastqueryid = mysql_query($sql, $this->link) or $this->halt(mysql_error(), $sql);
		$this->querycount++;
		return $this->lastqueryid;
	}

	public function select($data, $table, $where = '', $limit = '', $order = '', $group = '', $key = '', $safe=true) {
		$where = $where == '' ? '' : ' WHERE '.$where; 
		$order = $order == '' ? '' : ' ORDER BY '.$order;
		$group = $group == '' ? '' : ' GROUP BY '.$group;
		$limit = $limit == '' ? '' : ' LIMIT '.$limit;
		$field = explode(",", $data);
		if ($safe) {
			array_walk($field, array($this, 'add_special_char'));
		}
		$data = join(",", $field);

		$sql = "SELECT ".$data.' FROM `'.$this->config['database'].'`.`'.$table.'`'.$where.$group.$order.$limit;
		$this->execute($sql);
		if (!is_resource($this->lastqueryid)) {
			return $this->lastqueryid;
		}

		$datalist = array();
		while (($rs = $this->fetch_next()) != false) {
			if ($key) {
				$datalist[$rs[$key]] = $rs;
			}else{
				$datalist[] = $rs;
			}
		}
		$this->free_result();
		return $datalist;
	}

	public function get_one($data, $table, $where = '', $order = '', $group = '') {
		$where = $where == '' ? '' : ' WHERE '.$where; 
		$order = $order == '' ? '' : ' ORDER BY '.$order;
		$group = $group == '' ? '' : ' GROUP BY '.$group;
		$limit = ' LIMIT 1';
		$field = explode(",", $data);
		array_walk($field, array($this, 'add_special_char'));
		$data = join(",", $field);

		$sql = "SELECT ".$data.' FROM `'.$this->config['database'].'`.`'.$table.'`'.$where.$group.$order.$limit;
		$this->execute($sql);
		$res = $this->fetch_next();
		$this->free_result();
		return $res;
	}

	//$type 返回结果集类型
	//MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
	public function fetch_next($type = MYSQL_ASSOC){
		$rs = mysql_fetch_array($this->lastqueryid, $type);
		if (!$rs) {
			$this->free_result();
		}
		return $rs;
	}

	public function free_result(){
		if (is_resource($this->lastqueryid)) {
			mysql_free_result($this->lastqueryid);
			$this->lastqueryid = null;
		}
	}

	public function query($sql){
		return $this->execute($sql);
	}

	public function insert($data, $table, $return_insert_id = false, $replace = false){
		if (!is_array($data) || $table == '' || count($data) == 0) {
			return false;
		}

		$fielddata = array_keys($data);
		$valuedata = array_values($data);
		array_walk($fielddata, array($this, 'add_special_char'));
		array_walk($valuedata, array($this, 'escape_string'));

		$field = implode(",", $fielddata);
		$value = implode(",", $valuedata);

		$cmd = $replace ? "REPLACE INTO " : "INSERT INTO ";
		$sql = $cmd.' `'.$this->config["database"].'`.`'.$table.'`('.$field.') VALUES('.$value.')';
		$return = $this->execute($sql);
		return $return_insert_id ? $this->insert_id() : $return;
	}

	public function insert_id() {
		return mysql_insert_id($this->link);
	}

	//update
	public function update($data, $table, $where = ""){
		if ($table == '' || $where == ''){
			return false;
		}

		$where = ' WHERE '.$where;

		$field = '';
		if (is_string($data) && $data != '' ){
			$field = $data;
		}elseif (is_array($data) && count($data) > 0){
			$fields = array();
			foreach ($data as $k => $v) {
				switch (substr($v, 0, 2)) {
				case '+=':
					$v = substr($v, 2);
					if (is_numeric($v)) {
						$fields[] = $this->add_special_char($k).'='.$k.'+'.$this->escape_string($v, '', false);
					}else{
						continue;
					}
					break;
				case '-=':
					$v = substr($v, 2);
					if (is_numeric($v)) {
						$fields[] = $this->add_special_char($k).'='.$k.'-'.$this->escape_string($v, '', false);
					}else{
						continue;
					}
					break;
				default:
					$fields[] = $this->add_special_char($k).'='.$this->escape_string($v);
				}
			}
			$field = join(",", $fields);
		}else{
			return false;
		}

		$sql = "UPDATE `".$this->config["database"].'`.`'.$table.'` SET'.$field.$where;

		return $this->execute($sql);
	}


	//delete
	public function delete($table, $where) {
		if ($table == '' || $where == ''){
			return false;
		}

		$where = ' WHERE '.$where;
		$sql = 'DELETE FROM `'.$this->config["database"].'`.`'.$table.'`'.$where;

		return $this->execute($sql);
	}

	public function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	//get primary key
	public function get_primary($table) {
		$this->execute("SHOW COLUMNS FROM $table");
		while ($r = $this->fetch_next()) {
			if ($r['key'] == "PRI") break;
		}
		return $r['Field'];
	}

	public function get_fields($table) {
		$fields = array();
		$this->execute("SHOW COLUMNS FROM $table");

		while ($r = $this->fetch_next()){
			$fields[$r['Field']] = $r['Type'];
		}

		return $fields;
	}

	public function check_fields($table, $array) {
		$fields = $this->get_fields($table);
		$nofields = array();
		foreach ($array as $v) {
			if (!array_key_exists($v, $fields)) {
				$nofields[] = $v;
			}
		}

		return $nofields;
	}

	public function table_exists($table){
		$tables = $this->list_tables();
		return in_array($table, $tables) ? 1 : 0;
	}

	public function list_tables() {
		$tables = array();
		$this->execute("SHOW TABLES");
		while ($r = $this->fetch_next()) {
			$tables[] = $r["Tables_in_".$this->config["database"]];
		}
		return $tables;
	}

	public function fields_exists($table, $field) {
		$fields = $this->get_fields($table);
		return array_key_exists($field, $fields);
	}

	public function num_rows($sql) {
		$this->lastqueryid = $this->execute($sql);

		return mysql_num_rows($this->lastqueryid);
	}

	public function num_fields($sql) {
		$this->lastqueryid = $this->execute($sql);

		return mysql_num_fields($this->lastqueryid);
	}

	public function result($sql, $row){
		$this->lastqueryid = $this->execute($sql);

		return @mysql_result($this->lastqueryid, $row);
	}

	public function error(){
		return @mysql_error($this->link);
	}

	public function errno() {
		return intval(@mysql_errno($this->link));
	}

	public function version(){
		if (!is_resource($this->link)){
			$this->connect();
		}

		return mysql_get_server_info($this->link);
	}

	public function close(){
		if (is_resource($this->link)) {
			@mysql_close($this->link);
		}
	}

	public function halt($message = "", $sql = ""){
		if ($this->config["debug"]) {
			$this->errormsg = "";
			$msg = $this->errormsg;
			echo $message;
			echo "\n\n";
			echo $sql;
			exit;
		}else{
			return false;
		}
	}

	public function add_special_char(&$value) {
		if ('*' == $value || false != strpos($value, '(') || false != strpos($value, ".") || false != strpos($value, "`")) {

		}else {
			$value = "`".trim($value)."`";
		}
		return $value;
	}

	public function escape_string(&$value, $key = '', $quotation = 1) {
		if ($quotation) {
			$q = '\'';
		}else{
			$q = '';
		}
		$value = $q.mysql_escape_string($value).$q;
		return $value;
	}
}
?>
