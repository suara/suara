<?php
/**
 *
 */
namespace Suara\Libs\Http;
use Suara;

/**
 * 网络请求数据全部从这边走
 */
class Request{
	public function __construct($url = null, $parseEnvironment = true) {
		echo Suara\env('REQUEST_URI');
	}

	/**
	 * 获得相对地址，这个地址在系统SITE_URL提供的可能有错误
	 * 
	 */
	private function _base() {
	}
}

?>
