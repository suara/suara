<?php
namespace Suara\Libs\Model;

class Model {
	//db config
//	protected $db_config = '';
//	//db instance
//	protected $db = '';
//	protected $db_setting = 'default';
//	//数据表名
//	protected $table_name = '';
//	//tab prefix
//	public $table_prefix = '';
//	//init
//	public function __construct() {
//		if (!isset($this->db_config[$this->db_setting])) {
//			$this->db_setting = 'default';
//		}
//		$this->table_name = $this->db_config[$this->db_setting]["tablepre"].$this->table_name;
//		$this->table_prefix = $this->db_config[$this->db_setting]["tablepre"];
//		$this->db = Crow::get_instance($this->db_config)->get_database($this->db_setting);
//	}
//
//	/**
//	 * sql query
//	 * @param $where   查询条件 `name` = '$name'
//	 * @param $data    需要查询的字段 `name`, `type`
//	 * @param $limit   返回结果范围
//	 * @param $order   排序方式
//	 * @param $group   分组方式
//	 * @param $key     返回数据按键名排序
//	 *
//	 *
//	 * @return array  返回查询结果集数组
//	 */
//	final public function select($where = '', $data = '*', $limit = '', $order = '', $group = '', $key = '', $safe=true) {
//		if (is_array($where)) {
//			$where = $this->sqls($where);
//		}
//
//		$data = $this->db->select($data, $this->table_name, $where, $limit, $order, $group, $key, $safe);
//		if(empty($data)) $data = array();
//		return $data;
//	}
//
//	/**
//	 * 查询多条数据并分页
//	 * @param $where
//	 * @param $order
//	 * @param $page
//	 * @param $limit
//	 * 
//	 * @return mixed
//	 */
//	final public function listinfo($where = '', $order = '', $page = 1, $limit = 20, $key = '', $setpages = 5, $urlrule = '', $array = array()) {
//		if (is_array($where)) {
//			$where = $this->sqls($where);
//		}
//		$this->number = $this->count($where);
//		$page = max(intval($page), 1);
//		$offset = $limit * ($page - 1);
//		$this->pages = pages($this->number, $page, $limit, $urlrule, $array, $setpages);
//		$array = array();
//		if ($this->number > 0) {
//			return $this->select($where, '*', "$offset, $limit", $order, '', $key);
//		}else{
//			return array();
//		}
//	}
//
//
//	/**
//	 * get one record
//	 * @param $where
//	 * @param $data
//	 * @param $order
//	 * @param $group
//	 *
//	 * @return mixed   array / null
//	 */
//	final public function get_one($where = '', $data = '*', $order = '', $group = '') {
//		if (is_array($where)) {
//			$where = $this->sqls($where);
//		}
//
//		return $this->db->get_one($data, $this->table_name, $where, $order, $group);
//	}
//
//	final public function query($sql) {
//		//$sql = str_replace("");
//		return $this->db->query($sql);
//	}
//
//	final public function insert($data, $return_insert_id = false, $replace = false) {
//		return $this->db->insert($data, $this->table_name, $return_insert_id, $replace);
//	}
//
//	final public function insert_id() {
//		return $this->db->insert_id();
//	}
//
//	/**
//	 * 执行更新记录操作
//	 * @param $data 		要更新的数据内容，参数可以为数组也可以为字符串，建议数组。
//	 * 						为数组时数组key为字段值，数组值为数据取值
//	 * 						为字符串时[例：`name`='phpcms',`hits`=`hits`+1]。
//	 *						为数组时[例: array('name'=>'phpcms','password'=>'123456')]
//	 *						数组的另一种使用array('name'=>'+=1', 'base'=>'-=1');程序会自动解析为`name` = `name` + 1, `base` = `base` - 1
//	 * @param $where 		更新数据时的条件,可为数组或字符串
//	 * @return boolean
//	 */
//	final public function update($data, $where = '') {
//		if (is_array($where)) {
//			$where = $this->sqls($where);
//		}
//
//		return $this->db->update($data, $this->table_name, $where);
//	}
//
//	final public function delete($where) {
//		if (is_array($where)){
//			$where = $this->sqls($where);
//		}
//
//		return $this->db->delete($this->table_name, $where);
//	}
//
//	final public function count($where='') {
//		$r = $this->get_one($where, "COUNT(*) AS num");
//		return $r["num"];
//	}
//
//	//将数组转成where语句
//	final public function sqls($where, $font = " AND ") {
//		if (is_array($where)) {
//			$sql = '';
//			foreach ($where as $key => $val) {
//				$sql .= $sql ? "$font `$key` = '$val' " : " `$key` = '$val'";
//			}
//
//			return $sql;
//		}else {
//			return $where;
//		}
//	}
//
//	final public function affected_rows() {
//		return $this->db->affected_rows();
//	}
//
//	final public function get_primary(){
//		return $this->db->get_primary($this->table_name);
//	}
//
//	final public function get_fields($table_name = '') {
//		if (empty($table_name)) {
//			$table_name = $this->table_name;
//		}else{
//			$table_name = $this->table_prefix.$table_name;
//		}
//
//		return $this->db->get_fields($table_name);
//	}
//
//	final public function table_exists($table) {
//		return $this->db->table_exists($this->table_prefix.$table);
//	}
//
//	final public function field_exists($field) {
//		$fields = $this->db->get_fields($this->table_name);
//		return array_key_exists($field, $fields);
//	}
//
//	final public function list_tables(){
//		return $this->db->list_tables();
//	}
//
//	final public function fetch_array(){
//		$data = array();
//		while ($r = $this->db->fetch_next()) {
//			$data[] = $r;
//		}
//		return $data;
//	}
//
//	final public function version(){
//		return $this->db->version();
//	}
}
?>
