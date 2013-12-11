<?php

//View:
//jsonView
//SmartyView
class View {
    static private $registered_template_data = array();
    static private $registered_template_plugins = array();

    public function __construct() {

    }

	/**
	 * 注册变量到模板中
	 * @param string $key 模板需要调用的key
	 * @param mixed $value 变量值
	 * @param boolean $update 是否需要更新此变量
	 * @param boolean $get_values 取出所有已经注册的变量
	 */
    public function registerTemplateData($key, $value) {
        self::$registered_template_data[$key] = $value;
    }

	
	//获取注册到模板中的变量
    public function getTemplateData($key) {
        return self::$registered_template_data[$key];
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
    public function registerTemplatePlugin($type, $name, $callback) {
        $key = $type.$name;

        if (isset(self::$registered_template_plugins[$key])) {
            throw "This $type $name has registered";
            return;
        }

        self::$registered_template_plugins[$key] = array(
            'type' => $type,
            'name' => $name,
            'callback' => $callback
        );
    }
	
	public function render($module, $template, $style = '', $is_cached = true, $cache_time = 600, $output = true, $cache_options=array()) {

	}
}

/**
 * 模板调用
 * @param $module 这个模板所在的模块. 模板是按照模块目录分放的
 * @param $template 需要调用的模板
 * @param $style 需要调用的样式
 * @param $is_cached 是否开启缓存机制
 * @param $cache_time 缓存时间
 * @param $output 是输出到页面， 还是将html源码返回
 * @param $cache_options 缓存数组 传入比如是 array('cache_id', 'compile_id')
 *
 */
//function template($module, $template, $style = '', $is_cached = true, $cache_time = 600, $output=true, $cache_options=array()) {
//    $TEMPLATE_CACHE_PATH = CACHE_PATH . "cache_template" . DIRECTORY_SEPARATOR;
//    $TEMPLATE_PATH = S_PATH. "templates" . DIRECTORY_SEPARATOR;
//    $USER_TEMPLATE_PATH = SUARA_PATH."templates".DIRECTORY_SEPARATOR;
//
//    $module = str_replace("/", DIRECTORY_SEPARATOR, $module);
//    if (!empty($style) && preg_match('/[a-z0-9\-_]+/is', $style)) {
//    } elseif (empty($style) && s_core::load_config('system', "style")) {
//	$style = s_core::load_config('system', 'style');
//    }else {
//	$style = 'default';
//    }
//
//    if (!$style) {
//	$style = 'default';
//    }
//    //Configure Smarty
//    s_core::load_sys_class("smarty", 'libs'.DIRECTORY_SEPARATOR."smarty", 0);
//    $smarty = new Smarty();
//    $smarty->setCompileDir($TEMPLATE_CACHE_PATH."compiles".DIRECTORY_SEPARATOR);
//    $smarty->setConfigDir($TEMPLATE_CACHE_PATH."configs".DIRECTORY_SEPARATOR);
//    $smarty->setCacheDir($TEMPLATE_CACHE_PATH."caches".DIRECTORY_SEPARATOR);
//
//    //load plugin for smarty
//    $smarty->addPluginsDir(S_PATH."libs".DIRECTORY_SEPARATOR."plugins");
//    //$smarty->debugging = true;
//
//    //先去尝试读取用户定义的模板
//    $in_system = false;
//    if (file_exists($USER_TEMPLATE_PATH.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.".html")) {
//	$smarty->setTemplateDir($USER_TEMPLATE_PATH.$style);
//	register_template_data("style_dir", DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.$style);
//    } elseif (file_exists($TEMPLATE_PATH.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.".html")) {
//	$smarty->setTemplateDir($TEMPLATE_PATH.$style);
//	register_template_data("style_dir", DIRECTORY_SEPARATOR."suara".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.$style);
//    } else {
//	//模块目录下的template
//	$smarty->setTemplateDir(S_PATH.'modules'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR);
//	register_template_data("style_dir", DIRECTORY_SEPARATOR."suara".DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR);
//	$in_system = true;
//    }
//
//    if ($in_system) {
//	$path = $template.".html";
//    } else {
//	$path = $module.DIRECTORY_SEPARATOR.$template.".html";
//    }
//    if ($smarty->templateExists($path)) {
//	if ($is_cached) {
//	    $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
//	    $smarty->setCompileCheck(false);
//	    $smarty->cache_modified_check = true;
//	    $smarty->cache_lifetime = $cache_time;
//	} else {
//	    $smarty->setCaching(Smarty::CACHING_OFF);
//	    $smarty->cache_lifetime = -1;
//	}
//
//	//向模板中注册全局变量
//	$values = register_template_data(false, false, false, true);
//	foreach ($values as $k => $v) {
//	    $smarty->assign($k, $v);
//	}
//
//	$functions = register_template_plugin(false, false, false, true);
//	foreach ($functions as $k => $function) {
//	    $smarty->registerPlugin($function['type'], $function['name'], $function['callback']);
//	}
//
//	$cache_id = null;
//	$compile_id = null;
//
//	if (is_array($cache_options)) {
//	    if (!empty($cache_options['cache_id'])) {
//		$cache_id = $cache_options['cache_id'];
//	    }
//
//	    if (!empty($cache_options['compile_id'])) {
//		$compile_id = $cache_options['compile_id'];
//	    }
//	}
//
//	if ($output) {
//	    return $smarty->display($path, $cache_id, $compile_id);
//	} else {
//	    return $smarty->fetch($path, $cache_id, $compile_id);
//	}
//    }
//}

?>
