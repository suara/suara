<?php
namespace Suara\Libs\Configure;
defined('IN_SUARA') or exit('Permission deiened');

/**
 * 读取配置文件接口，他适用于两种模式：
 */
interface IConfigureReader {
	/**
	 *	类似数组方式一样，根据给予的$key读取配置中的值。
	 *
	 *	@param string $key
	 *	@return 
	 */
	public function read($key);

	/**
	 * Dumps configure data into source
	 * @param string $key
	 * @param array $data
	 */
	public function dump($key, $data);
}

?>
