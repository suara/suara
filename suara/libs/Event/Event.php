<?php
namespace Suara\Libs\Event;

/**
 * 事件基础模型
 */
class Event {
	/**
	 * Event name
	 */
	public $name;

	public $subject = null;
	/**
	 * event data
	 */
	public $data = null;

	public $result = null;

	protected $_stopped = false;

	public function __construct($name, $subject = null, $data = null) {
		$this->name = $name;
		$this->subject = $subject;
		$this->data = $data;
	}

	public function stopPropagation() {
		return $this->_stopped = true;
	}

	public function isStop() {
		return $thi->_stopped;
	}
}
?>
