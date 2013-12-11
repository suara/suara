<?php
final class Crow {
	//current instance
	private static $db_factory;

	//db config
	protected $db_config = array();

	protected $db_list = array();

	private $db_path = "";

	public function __construct() {
		$this->db_path = "libs".DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."Database";
	}

	static public function get_instance($db_config) {
		if ($db_config == '') {
			global $_CONFIG;
			$db_config = $_CONFIG["dbs"]["default"];
		}

		if (Crow::$db_factory == '') {
			Crow::$db_factory = new Crow();
		}

		if ($db_config != "" && $db_config != Crow::$db_factory->db_config) {
			Crow::$db_factory->db_config = array_merge($db_config, Crow::$db_factory->db_config);
		}

		return Crow::$db_factory;
	}

	public function get_database($db_name) {
		if (!isset($this->db_list[$db_name]) || !is_object($this->db_list[$db_name])) {
			$this->db_list[$db_name] = $this->connect($db_name);
		}

		return $this->db_list[$db_name];
	}

	public function connect($db_name) {
		$object = null;
		if ($this->db_config[$db_name]["type"] == "mysql") {
			s_core::load_sys_class('mysql', $this->db_path, 0);
			$object = new mysql();
		}

		$object->open($this->db_config[$db_name]);
		return $object;
	}

	protected function close(){
		foreach ($this->db_list as $db) {
			$db->close();
		}
	}

	public function __destruct(){
		$this->close();
	}
}
?>
