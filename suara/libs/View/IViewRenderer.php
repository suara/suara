<?php
namespace Suara\Libs\View;
/**
 * View Renderer Interface
 * Every view must include *render* method
 */

interface IViewRenderer {
	/**
	 * 模板调用
	 * @param string $module 这个模板所在的模块. 模板是按照模块目录分放的
	 * @param string $template 需要调用的模板
	 * @param string $options 参数配置
	 */
	public function render($module, $template, $options = array());
}
?>
