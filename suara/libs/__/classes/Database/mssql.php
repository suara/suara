<?php
final class mssql {
	private $config = null;
	public $link = null;

	public $lastqueryid = null;

	public $querycount = 0;

	public function __construct() {

	}

	public function open($config){
		$this->config = $config;
		if ($this->config['autoconnect'] == 1) {
			$this->connect();
		}
	}

	public function connect() {
		$func = $this->config["pconnect"] == 1 ? "mssql_pconnect" : "mssql_connect";
		if (!$this->link = @$func($this->config["hostname"], $this->config["username"], $this->config["password"], 1)) {
			$this->halt("Cant connect to MSSQL server");
			return false;
		}

		//TODO: set mssql charset

		if ($this->config["database"] && !@mssql_select_db($this->config["database"], $this->link)) {
			$this->halt("Cant use database ". $this->config["database"]);
			return false;
		}

		$this->database = $this->config["database"];
		return $this->link;
	}

	public function execute($sql) {
		if (!is_resource($this->link)) {
			$this->connect();
		}

		if ($sql != '') {
			//$this->lastqueryid = mssql_query($sql, $this->link) or $this->halt(mssql_e(), $sql);
			//return $this->lastqueryid;
		}
	}

	public function select($data, $table, $where = '', $limit='', $order= '', $group = '', $key = '', $safe = true) {

	}

	public function get_one($data, $table, $where = '', $order = '', $group = '') {

	}

	public function fetch_next() {

	}

	public function free_result() {
		if (is_resource($this->lastqueryid)) {
			mssql_free_result($this->lastqueryid);
			$this->lastqueryid = null;
		}
	}

	public function query($sql) {
		return $this->execute($sql);
	}

	public function insert($data, $table, $return_insert_id = false, $replace = false) {

	}

	public function insert_id() {
	}

	public function update($data, $table, $where = "") {

	}

	public function delete($table, $where) {

	}

	public function affected_rows() {

	}

	public function get_primary($table) {

	}

	public function get_fields($table) {

	}

	public function check_fields($table, $array) {

	}

	public function table_exists($table) {

	}

	public function list_tables() {

	}

	public function fields_exists($table, $field) {

	}

	public function num_rows($sql) {

	}

	public function num_fields($sql) {

	}

	public function result($sql, $row) {

	}

	public function error() {

	}

	public function errno() {

	}

	public function version() {

	}

	public function close() {
		if (is_resource($this->link)) {
			mssql_close($this->link);
		}
	}

	public function halt($message="", $sql="") {

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
