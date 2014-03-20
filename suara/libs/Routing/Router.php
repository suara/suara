<?php
/**
 * Suara Bootstrap (http://suaraphp.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link          http://suaraphp.com
 * @package       Suara.Libs.Routing
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        wolftankk@gmail.com		  
 */
namespace Suara\Libs\Routing;

class Router {
	public static $initialized = false;

	public static function parse($url) {

	}


	protected static function loadRoutes() {
		//load config
		self::$initialized = true;
		include APP_CONFIG_PATH . 'routers.php';
	}
}
?>
