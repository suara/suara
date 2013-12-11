<?php
/**
 * Suara Paginate
 *
 */
class Paginate {
	/**
	 * config:
	 *  item_count  总数量
	 *  page  	当前页
	 *  items_per_page 每页显示item数量
	 *  first_page 定义第一页
	 *  last_page 定义最后一页
	 *  previous_page 上一页
	 *  next_page 下一页
	 *  page_count 总页数
	 *
	 *  wrapper_class page的class 可以不用
	 *  selected_class 已选中的class 可以不用
	 *
	 *  items 当前items列表 存储链接地址
	 *  first_item 
	 *  last_item
	 *  template 分页模板
	 *  
	 *  url 当前链接
	 *  urlmaker URL规则
	 *  $extra_args 额外参数
	 */
	private $url;
	private $maker;
	private $config;
	private $extra_args = array();

	public function __construct(array $config = array(), $url = '', $urlmaker = '', array $extra_args = array()) {
		$default = array(
			'page' => 1,
			'items_per_page' => 10,
			'template' => 'pages/default'
		);
		$this->config = $config + $default;

		if (empty($url)) {
			$this->url = get_url();
		}
		if (empty($urlmaker)) {
			$this->maker = $this->urlmaker('page={$page}');
		}

		if (!empty($extra_args)) {
			$this->extra_args = $extra_args;
		}

		//初始状态
		if ($this->config['item_count'] > 0) {
			$this->config['first_page'] = 1;
			$this->config['page_count'] = (int) (($this->config['item_count'] - 1) / $this->config['items_per_page']) + 1;
			$this->config['last_page'] = (int) $this->config['first_page'] + $this->config['page_count'] - 1;

			//确认当前页数是在有效范围内
			if ($this->config['page'] > $this->config['last_page']) {
				$this->config['page'] = $this->config['last_page'];
			} elseif ($this->config['page'] < $this->config['first_page']) {
				$this->config['page'] = $this->config['first_page'];
			}

			//当在最后一页时，页面上的item数量少于items_per_page
			$this->config['first_item'] = ($this->config['page'] - 1) * $this->config['items_per_page'] + 1;
			$this->config['last_item'] = min($this->config['first_item'] + $this->config['items_per_page'] - 1, $this->config['item_count']);
			//生成items
			$this->setItems();
		} else {
			$this->config['first_page'] = null;
			$this->config['page_count'] = 0;
			$this->config['last_page'] = null;
			$this->config['first_item'] = null;
			$this->config['last_item'] = null;
			$this->config['previous_page'] = null;
			$this->config['next_page'] = null;
		}
	}

	private function setItems() {
		extract($this->config);
		$pages = array();
		for ($i = $first_page; $i <= $last_page; $i++) {
			$pages[] = $this->getPageUrl($i);
		}

		$items = array(
			'first_page' => $this->getPageUrl($first_page),
			'last_page'  => $this->getPageUrl($last_page),
			'pages' => $pages
		);

		if (($page - 1) >= $first_page) {
			$items['previous_page'] = $this->getPageUrl($page - 1);
			$this->config['previous_page'] = $page - 1;
		}
		if (($page + 1) <= $last_page) {
			$items['next_page'] = $this->getPageUrl($page + 1);
			$this->config['next_page'] = $page + 1;
		}

		$this->config['links'] = $items;
	}

	/**
	 * radius
	 * 设定围绕在当前页的数字范围
	 */
	public function getPages($radius = 2) {
		if ($this->config['page_count'] == 0 || $this->config['page_count'] == 1) {
			return '';
		}
		extract($this->config);
		$leftmost_page = max($first_page, ($page - $radius));
		$rightmost_page = min($last_page, ($page + $radius));
		$this->config['leftmost_page'] = $leftmost_page;
		$this->config['rightmost_page'] = $rightmost_page;

		list($module, $template) = explode("/", $this->config['template'], 2);
		register_template_data('page_data', $this->config);
		return template($module, $template, '', 0, 0, false);
	}

	public function __get($key) {
		if (array_key_exists($key, $this->config)) {
			return $this->config[$key];
		} else {
			return false;
		}
	}

	/**
	 * 返回页面路径
	 * @param $urlrule 分页规则
	 * @param $currentPage 当前页
	 * @param $array 需要传递的数据
	 *
	 * @return 完成的url
	 */
	private function getPageUrl($page) {
		$findme = array('{$page}');
		$replaceme = array($page);

		if (!empty($this->extra_args)) {
			foreach ($extra_args as $k => $v) {
				$findme[] = $k;
				$replaceme[] = $v;
			}
		}
		$url = str_replace($findme, $replaceme, $this->maker);
		return $url;
	}

	/**
	 * 重新生成页面链接
	 */
	private function urlmaker($maker) {
		$url = $this->url;
		$pos = strpos($this->url, '?');

		if ($pos === false) {
			$url .= '?'.$maker;
		} else {
			$querystring = substr(strstr($url, '?'), 1);
			parse_str($querystring, $params);
			$query_array = array();

			//移除page的参数
			foreach ($params as $k => $v) {
				if ($k != 'page') {
					$query_array[$k] = $v;
				}
			}
			$querystring = http_build_query($query_array);
			if (!empty($querystring)) {
				$querystring .= '&' . $maker;
			} else {
				$querystring .= $maker;
			}
			$url = substr($url, 0, $pos) . '?' . $querystring;
		}
		return $url;
	}
}

/**
 * 为了兼容老版本，目前保留
 */
function paginate($options, $urlrule='', $array=array(), $setpages= 5) {
	$currentPage = $options['page'];
	$totalCount = $options['totalCount'];
	$pagesize = $options['limit'];
	$style = (array)$options['style'];
	
	$config = array(
		'page' => $options['page'],
		'item_count' => $options['totalCount'],
		'items_per_page' => $options['limit'],
		'template' => 'tga/pages'
	);
	if (!empty($style) && !empty($style['pageclass'])) {
		$config['wrapper_class'] = $style['pageclass'];
	}
	if (!empty($style) && !empty($style['selected'])) {
		$config['selected_class'] = $style['selected'];
	}

	$p = new Paginate($config, '', $urlrule, $array);
	$radius = floor($setpages / 2);
	return $p->getPages($radius);
}
?>

