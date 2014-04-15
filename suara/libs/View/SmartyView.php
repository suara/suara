<?php
namespace Suara\Libs\View;

use Suara\Libs\View\IViewRenderer as IViewRenderer;

class SmartyView implements IViewRenderer {
	public $viewVars = [];


	/**
	 * 模板默认配置属性
	 * @param $is_cached 是否开启缓存机制
	 * @param $cache_time 缓存时间
	 * @param $output 是输出到页面， 还是将html源码返回
	 * @param cache_id
	 * @param compile_id
	 */
	private $_options = array(
		'cached'  => true,
		'cachetime' => 600,
		'output'     => 'render', //render / source
	);

	private static $_renderer = null; //self

	public $view;

	public $layout = 'default';

	public $ext = '.html';

	public $layoutPath = null;

	public $layoutCachePath = null;

	public function __construct() {
		if ($this->view == null) {
			include __DIR__.DIRECTORY_SEPARATOR."Engine/Smarty/Smarty.php";
			$this->view = new \Smarty();
		}

		//define('ASSET_COMPILE_OUTPUT_DIR', CACHE_PATH.'asset_cache');
		//define('ASSET_COMPILE_URL_ROOT', '/caches/asset_cache');
		//define('SACY_WRITE_HEADERS', false);

		//$TEMPLATE_CACHE_PATH = CACHE_PATH . "cache_template" . DIRECTORY_SEPARATOR;
		//$TEMPLATE_PATH = S_PATH. "templates" . DIRECTORY_SEPARATOR;
		//$USER_TEMPLATE_PATH = SUARA_PATH."templates".DIRECTORY_SEPARATOR;

		//$module = str_replace("/", DIRECTORY_SEPARATOR, $module);
		//if (!empty($style) && preg_match('/[a-z0-9\-_]+/is', $style)) {
		//} elseif (empty($style) && s_core::load_config('system', "style")) {
		//	$style = s_core::load_config('system', 'style');
		//}else {
		//	$style = 'default';
		//}
	}

    //public function __construct($module, $template, $options) {
	//}

	public function getVars() {
		return array_keys($this->viewVars);
	}

	public function getVar($name) {
		//return $this->get($name);
	}

    /**
     * 注册函数到模板中
     * @param string type 类型 (function, block, compiler, modifier)
     *
     *  function  直接可以在模板中调用此函数 比如date_now  {date_now}  每个函数都会有两个固定的值
     *    $params, $smarty    $params 所有的参数都会在得到比如 {date_now format='Y-M-d'}，  $smarty 全局模板类。 
     *  block  块函数  注册成一个标签。用来引用内部大量的文字 {typelist} <a href='~~link~~'></a>  {/typelist}
     *     每个注册中的函数一共包含有5个参数  具体可以看plugins目录下的block.s_typelist.php
     *
     *  modifier 修饰器  调用方式{$var|ss}  其中ss就是调用的函数   这里的ss 是我们注册进去的stripslashes
     * @param string name 在smarty模板中的函数名
     * @param string function 回调函数 
     */
    //public static function registerTemplatePlugin($type, $name, $callback) {
    //    $key = $type.$name;

    //    if (isset(self::$registered_template_plugins[$key])) {
    //        throw "This $type $name has registered";
    //        return;
    //    }

    //    self::$registered_template_plugins[$key] = array(
    //        'type' => $type,
    //        'name' => $name,
    //        'callback' => $callback
    //    );
	//}

	/**
	 * 模板调用
	 * @param $module 这个模板所在的模块. 模板是按照模块目录分放的
	 * @param $template 需要调用的模板
	 *
	 */
	public function render($module, $template, $options = array()) { 
		$smarty->setCompileDir($this->layoutCachePath."compiles".DIRECTORY_SEPARATOR);
		$smarty->setConfigDir($this->layoutCachePath."configs".DIRECTORY_SEPARATOR);
		$smarty->setCacheDir($this->layoutCachePath."caches".DIRECTORY_SEPARATOR);

		//load plugin for smarty
		//$smarty->addPluginsDir(S_PATH."libs".DIRECTORY_SEPARATOR."plugins");
		//$smarty->debugging = true;

		////先去尝试读取用户定义的模板
		//$in_system = false;
		//$tplFile = $this->layoutPath.DIRECTORY_SEPARATOR.$this->layout.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.$this->ext;
		//if (file_exists($tplFile)) {
		//	$this->view->setTemplateDir($this->layoutPath.DIRECTORY_SEPARATOR.$this->layout);
		//}


		//if (file_exists($USER_TEMPLATE_PATH.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.".html")) {
		//	$smarty->setTemplateDir($USER_TEMPLATE_PATH.$style);
		//	register_template_data("style_dir", DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.$style);
		//} elseif (file_exists($TEMPLATE_PATH.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.".html")) {
		//	$smarty->setTemplateDir($TEMPLATE_PATH.$style);
		//	register_template_data("style_dir", DIRECTORY_SEPARATOR."suara".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.$style);
		//} else {
		//	//模块目录下的template
		//	$smarty->setTemplateDir(S_PATH.'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR);
		//	register_template_data("style_dir", DIRECTORY_SEPARATOR."suara".DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR);
		//	$in_system = true;
		//}

		//if ($in_system) {
		//	$path = $template.".html";
		//} else {
		//	$path = $module.DIRECTORY_SEPARATOR.$template.".html";
		//}
		if ( $this->view->templateExists($path) ) {
			if ($this->options['cached']) {
				$this->view->setCaching(\Smarty::CACHING_LIFETIME_CURRENT);
				$this->view->setCompileCheck(false);
				$this->view->cache_modified_check = true;
				$this->view->cache_lifetime = $this->options['cachetime'];
			} else {
				$this->view->setCaching(\Smarty::CACHING_OFF);
				$this->view->cache_lifetime = -1;
			}

			//向模板中注册全局变量
			foreach ($this->viewVars as $k => $v) {
				$smarty->assign($k, $v);
			}

		//	$functions = register_template_plugin(false, false, false, true);
		//	foreach ($functions as $k => $function) {
		//		$smarty->registerPlugin($function['type'], $function['name'], $function['callback']);
		//	}

			$cache_id = null;
			$compile_id = null;
			if (isset($this->options['cacheOptions'])) {
				$cache_options = $this->options['cacheOptions'];
				if (!empty($cache_options['cache_id'])) {
					$cache_id = $cache_options['cache_id'];
				}
				if (!empty($cache_options['compile_id'])) {
					$compile_id = $cache_options['compile_id'];
				}
			}

			if ($this->options['output'] == 'render') {
				return $smarty->display($path, $cache_id, $compile_id);
			} elseif ($this->options['output'] == 'source') {
				return $smarty->fetch($path, $cache_id, $compile_id);
			}
		}
	}
}

?>
