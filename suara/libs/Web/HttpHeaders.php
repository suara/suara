<?php
/**
 * Suara Bootstrap (http://suaraphp.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link          http://suaraphp.com
 * @package       Suara.Http
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        wolftankk@gmail.com		  
 * @Description   HTTP Headers Collection.
 */
namespace Suara\Libs\Web;

use ArrayIterator;

class HttpHeaders implements \IteratorAggregate, \ArrayAccess, \Countable {
	private $_headers = [];

	public function getIterator() {
		return new ArrayIterator($this->_headers);
	}

	public function count() {
		return count($this->_headers);
	}

	public function toArray() {
		return $this->_headers;
	}

	/**
	 * set
	 */
	public function offsetSet($name, $value = '') {
		$name = strtolower($name);
		$this->_headers[$name] = $value;

		return $this;
	}

	public function offsetGet($name) {
		$name = strtolower($name);
		if (isset($this->_headers[$name])) {
			return $this->_headers[$name];
		}

		return false;
	}

	public function offsetExists($name) {
		$name = strtolower($name);

		return isset($this->_headers[$name]);
	}

	public function offsetUnset($name) {
		$name = strtolower($name);

		if ($this->offsetExists($name)) {
			$value = $this->offsetGet($name);
			unset($this->_headers[$name]);
			return $value;
		} else {
			return null;
		}
	}

	public function has($name) {
		return $this->offsetExists($name);
	}

	public function get($name) {
		if ($this->has($name)) {
			return $this->offsetGet($name);
		}
		return null;
	}

	public function set($name, $value) {
		return $this->offsetSet($name, $value);
	}

	public function remove($name) {
		return $this->offsetUnset($name);
	}
}

?>
