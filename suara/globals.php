<?php
/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string){
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) {
	if(!is_array($string)) return htmlspecialchars($string);
	foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
	return $string;
}
/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	$string = str_replace('\\','',$string);
	return $string;
}



/**
 * 过滤ASCII码从0-28的控制字符
 * @return String
 */
function trim_unsafe_control_chars($str) {
	$rule = '/[' . chr ( 1 ) . '-' . chr ( 8 ) . chr ( 11 ) . '-' . chr ( 12 ) . chr ( 14 ) . '-' . chr ( 31 ) . ']*/';
	return str_replace ( chr ( 0 ), '', preg_replace ( $rule, '', $str ) );
}

/**
 * 格式化文本域内容
 *
 * @param $string 文本域内容
 * @return string
 */
function trim_textarea($string) {
	$string = nl2br ( str_replace ( ' ', '&nbsp;', $string ) );
	return $string;
}

/**
 * 取得文件扩展
 *
 * @param $filename 文件名
 * @return 扩展名
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}


/**
 * 字符截取 支持UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
function str_cut($string, $length, $dot = '...') {
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
	$strcut = '';
	if (defined('CHARSET')) {
		$charset = CHARSET;
	} else {
		$charset = s_core::load_config('system', 'charset');
	}
	if(strtolower($charset) == 'utf-8') {
		$length = intval($length-strlen($dot)-$length/3);
		$n = $tn = $noc = 0;
		while($n < strlen($string)) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
		$strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
	} else {
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		$current_str = '';
		$search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
		$replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
		$search_flip = array_flip($search_arr);
		for ($i = 0; $i < $maxi; $i++) {
			$current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
			if (in_array($current_str, $search_arr)) {
				$key = $search_flip[$current_str];
				$current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
			}
			$strcut .= $current_str;
		}
	}
	return $strcut.$dot;
}

/**
 * 获取请求ip
 *
 * @return ip地址
 */
function ip() {
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$ip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}

/**
 * 产生随机字符串
 *
 * @param    int        $length  输出长度
 * @param    string     $chars   可选的
 * @return   string     字符串
 */
function random($length, $chars = '0123456789') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

/**
 * 将字符串转换为数组
 *
 * @param	string	$data	字符串
 * @return	array	返回数组格式，如果，data为空，则返回空数组
 */
function string2array($data) {
	if($data == '') return array();
	@eval("\$array = $data;");
	return $array;
}
/**
 * 将数组转换为字符串
 *
 * @param	array	$data		数组
 * @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
 * @return	string	返回字符串，如果，data为空，则返回空
 */
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return addslashes(var_export($data, TRUE));
}

/**
 * 转换字节数为其他单位
 *
 *
 * @param	string	$filesize	字节大小
 * @return	string	返回大小
 */
function sizecount($filesize) {
	if ($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
	} elseif ($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 .' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize.' Bytes';
	}
	return $filesize;
}

/**
 * 字符串加密、解密函数
 *
 *
 * @param	string	$txt		字符串
 * @param	string	$operation	ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
 * @param	string	$key		密钥：数字、字母、下划线
 * @param	string	$expiry		过期时间
 * @return	string
 */
function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
	$key_length = 4;
	$key = md5($key != '' ? $key : s_core::load_config('system', 'auth_key'));
	$fixedkey = md5($key);
	$egiskeys = md5(substr($fixedkey, 16, 16));
	$runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
	$keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
	$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));

	$i = 0; $result = '';
	$string_length = strlen($string);
	for ($i = 0; $i < $string_length; $i++){
		$result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
	}
	if($operation == 'ENCODE') {
		return $runtokey . str_replace('=', '', base64_encode($result));
	} else {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	}
}

/**
 * 生成sql语句，如果传入$in_cloumn 生成格式为 IN('a', 'b', 'c')
 * @param $data 条件数组或者字符串
 * @param $front 连接符
 * @param $in_column 字段名称
 * @return string
 */
function to_sqls($data, $front = ' AND ', $in_column = false) {
	if($in_column && is_array($data)) {
		$ids = '\''.implode('\',\'', $data).'\'';
		$sql = "$in_column IN ($ids)";
		return $sql;
	} else {
		if ($front == '') {
			$front = ' AND ';
		}
		if(is_array($data) && count($data) > 0) {
			$sql = '';
			foreach ($data as $key => $val) {
				$sql .= $sql ? " $front `$key` = '$val' " : " `$key` = '$val' ";
			}
			return $sql;
		} else {
			return $data;
		}
	}
}

/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function create_randomstr($lenth = 4) {
	return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
 * Function dataformat
 * 时间转换
 * @param $n INT时间
 */
function dataformat($n) {
	$hours = floor($n/3600);
	$minite	= floor($n%3600/60);
	$secend = floor($n%3600%60);
	$minite = $minite < 10 ? "0".$minite : $minite;
	$secend = $secend < 10 ? "0".$secend : $secend;
	if($n >= 3600){
		return $hours.":".$minite.":".$secend;
	}else{
		return $minite.":".$secend;
	}

}

/**
 * iconv 编辑转换
 */
if (!function_exists('iconv')) {
	function iconv($in_charset, $out_charset, $str) {
		$in_charset = strtoupper($in_charset);
		$out_charset = strtoupper($out_charset);
		if (function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($str, $out_charset, $in_charset);
		} else {
			s_core::load_sys_func('iconv');
			$in_charset = strtoupper($in_charset);
			$out_charset = strtoupper($out_charset);
			if ($in_charset == 'UTF-8' && ($out_charset == 'GBK' || $out_charset == 'GB2312')) {
				return utf8_to_gbk($str);
			}
			if (($in_charset == 'GBK' || $in_charset == 'GB2312') && $out_charset == 'UTF-8') {
				return gbk_to_utf8($str);
			}
			return $str;
		}
	}
}

/**
 * Cache
 */
function setcache($name, $data, $filepath = '', $type = 'file', $config='', $timeout=30) {
	s_core::load_sys_class('cache_factory', '', 0);
	if ($config) {
		$cacheconfig = s_core::load_config('cache');
		$cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
	} else {
		$cache = cache_factory::get_instance()->get_cache($type);
	}

	//clear 
	return $cache->set($name, $data, $timeout, '', $filepath);
}

function getcache($name, $filepath='', $type='file', $config='') {
	s_core::load_sys_class('cache_factory', '', 0);
	if ($config) {
		$cacheconfig = s_core::load_config('cache');
		$cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
	} else {
		$cache = cache_factory::get_instance()->get_cache($type);
	}

	return $cache->get($name, '', $filepath);
}

function delcache($name, $filepath='', $type='file', $config='') {
	s_core::load_sys_class('cache_factory', '', 0);
	if ($config) {
		$cacheconfig = s_core::load_config('cache');
		$cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
	} else {
		$cache = cache_factory::get_instance()->get_cache($type);
	}
	return $cache->delete($name, '', '', $filepath);
}

function getcacheinfo($name, $filepath ='', $type='file', $config='') {
	s_core::load_sys_class('cache_factory', '', 0);
	if ($config) {
		$cacheconfig = s_core::load_config('cache');
		$cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
	} else {
		$cache = cache_factory::get_instance()->get_cache($config);
	}
	return $cache->cacheinfo($name, '', '', $filepath);
}

/**
 * 生成缩略图
 * @param $imgurl 图片路径
 * @param $width 缩略图的宽度
 * @param $height 缩略图的高度
 * @param $autocut 是否自动裁剪
 * @param $smallpic 无图片时, 返回一张默认图片
 */
function thumb($imgurl, $width=100, $height=100, $autocut=1, $smallpic='nopic.gif') {
	global $image;

	if (!is_object($image)) {
		s_core::load_sys_class("image", "", false);
		$image = new image(true);
	}
}

/**
 * 获取服务器参数
 */
function env($key) {
	if ($key === 'HTTPS') {
		if (isset($_SERVER['HTTPS'])) {
			return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
		}

		return (strpos(env('SCRIPT_URI'), 'https://') === 0);
	}

	if ($key === 'SCRIPT_NAME') {
		if (env('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
			$key = 'SCRIPT_URL';
		}
	}

	$val = null;
	if (isset($_SERVER[$key])) {
		$val = $_SERVER[$key];
	} elseif (isset($_ENV[$key])){
		$val = $_ENV[$key];
	} elseif (getenv($key) !== false) {
		$val = getenv($key);
	}

	if ($key === 'REMOTE_ADDR' && $val === env('SERVER_ADDR')) {
		$addr = env('HTTP_PC_REMOTE_ADDR');
		if ($addr != null) {
			$val = $addr;
		}
	}

	if ($val != null) {
		return $val;
	}

	switch ($key) {
	case 'DOCUMENT_ROOT':
		$name = env('SCRIPT_NAME');
		$filename = env("SCRIPT_FILENAME");
		$offset = 0;
		if (!strpos($name, '.php')) {
			$offset = 4;
		}
		return substr($filename, 0, -(strlen($name) + $offset));
	case 'PHP_SELF':
		return str_replace(env('DOCUMENT_ROOT'), '', env('SCRIPT_FILENAME'));
	case 'CGI_MODE':
		return (PHP_SAPI === 'cgi');
	case 'HTTP_BASE':
		$host = env('HTTP_HOST');
		$parts = explode('.', $host);
		$count = count($parts);
		if ($count == 1) {
			return '.'.$host;
		} elseif ($count == 2) {
			return '.' . $host;
		} elseif ($count == 3) {
			$ds = array('com', 'cn', 'org', 'gov');
			if (in_array($parts[1], $ds)) {
				return '.'.$host;
			}
		}
		array_shift($parts);
		return '.'. implode('.', $parts);
	}

	return null;
}

function host() {
	return $_SERVER['HTTP_HOST'];
}

/**
 * 获得当前域
 */
function domain($tl_length = 1) {
	$host = host();
	$domain = array_slice(explode(".", $host), -1 * (1 + $tl_length));
	$domain = join(".", $domain);
	return $domain;
}

/**
 * 分页函数
 *
 * @param $totalCount 数据总数
 * @param $currentPage 当前页数
 * @param $pagesize 每页显示数量
 * @param $urlrule URL规则
 * @param $array 需要传递的数据 
 *
 * @return 
 */
function pages($totalCount, $currentPage, $pagesize=20, $urlrule='', $array=array(), $setpages=5) {
	if (!class_exists('Paginate')) {
		s_core::load_sys_class('paginator', '', 0);
	}
	$p = new Paginate(array(
		'item_count' => $totalCount,
		'page' => $currentPage,
		'items_per_page' => $pagesize,
		'template' => 'pages/bs2'
	), '', $urlrule, $array);

	return $p->getPages(floor($setpages / 2));
}

/**
 * 生成上传附件验证
 * @param $args   参数
 * @param $operation   操作类型(加密解密)
 */
function upload_key($args) {
	$pc_auth_key = md5(s_core::load_config('system','auth_key').$_SERVER['HTTP_USER_AGENT']);
	$authkey = md5($args.$pc_auth_key);
	return $authkey;
}

/**
 *  短消息函数,可以在某个动作处理后友好的提示信息
 *
 * @param     string  $msg      消息提示信息
 * @param     string  $gourl    跳转地址
 * @param     int     $onlymsg  仅显示信息
 * @param     int     $limittime  限制时间
 * @return    void
 */
function ShowMsg($msg, $gourl, $onlymsg=0, $limittime=0) {
	$htmlhead  = "<html>\r\n<head>\r\n<title>提示信息</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n";
	$htmlhead .= "<base target='_self'/>\r\n<style>div{line-height:160%;}</style></head>\r\n<body leftmargin='0' topmargin='0' bgcolor='#efefef'>\r\n<center>\r\n<script>\r\n";
	$htmlfoot  = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";
	$litime = ($limittime==0 ? 1000 : $limittime);
	$func = '';

	if ($gourl=='-1') {
		if ($limittime==0) $litime = 5000;
		$gourl = "javascript:history.go(-1);";
	}

	if ($gourl=='' || $onlymsg==1) {
		$msg = "<script>alert(\"".str_replace("\"","“",$msg)."\");</script>";
	} else {
		//当网址为:close::objname 时, 关闭父框架的id=objname元素
		if (preg_match('/close::/',$gourl)) {
			$tgobj = trim(preg_replace('/close::/', '', $gourl));
			$gourl = 'javascript:;';
			$func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';\r\n";
		}
		$func .= "      var pgo=0;
		function JumpUrl(){
			if(pgo==0){ location='$gourl'; pgo=1; }
	}\r\n";
	$rmsg = $func;
	$rmsg .= "document.write(\"<br /><div style='width:450px;padding:0px;border-radius: 4px;font-size:16px;background:#ffffff;height:130px;'><br />\");\r\n";
	$rmsg .= "document.write(\"".str_replace("\"","“",$msg)."\");\r\n";
	$rmsg .= "document.write(\"";

	if ($onlymsg==0) {
		if ( $gourl != 'javascript:;' && $gourl != '') {
			$rmsg .= "<br /><a href='{$gourl}'>如果你的浏览器没反应，请点击这里...</a>";
			$rmsg .= "<br/></div>\");\r\n";
			$rmsg .= "setTimeout('JumpUrl()',$litime);";
		} else {
			$rmsg .= "<br/></div>\");\r\n";
		}
	} else {
		$rmsg .= "<br/><br/></div>\");\r\n";
	}
	$msg  = $htmlhead.$rmsg.$htmlfoot;
	}
	echo $msg;
}

/**
 * random uuid
 * @see http://www.ietf.org/rfc/rfc4122.txt
 */
function uuid() {
	$node = env('SERVER_ADDR');
	if (strpos($node, ':') !== false) {
		if (substr_count($node, '::')) {
			$node = str_replace(
				'::', str_repeat(':0000', 8 - substr_count($node, ':')) . ':', $node
			);
		}
		$node = explode(':', $node);
		$ipSix = '';

		foreach ($node as $id) {
			/**
			 * STR_PAD_LEFT
			 * @see http://www.php.net/manual/en/function.str-pad.php
			 */
			$ipSix .= str_pad(base_convert($id, 16, 2), 16, 0, STR_PAD_LEFT);
		}
		$node = base_convert($ipSix, 2, 10);

		if (strlen($node) < 38) {
			$node = null;
		} else {
			$node = crc32($node);
		}
	} elseif (empty($node)) {
		$host = env('HOSTNAME');

		if (empty($host)) {
			$host = env('HOST');
		}

		if (!empty($host)) {
			$ip = gethostbyname($host);

			if ($ip === $host) {
				$node = crc32($host);
			} else {
				$node = ip2long($ip);
			}
		}
	} elseif ($node !== '127.0.0.1') {
		$node = ip2long($node);
	} else {
		$node = null;
	}

	if (empty($node)) {
		$node = hash('crc32', s_core::load_config('system', 'auth_key'));
	}

	if (function_exists('hphp_get_thread_id')) {
		$pid = hphp_get_thread_id();
	} elseif (function_exists('zend_thread_id')) {
		$pid = zend_thread_id();
	} else {
		$pid = getmypid();
	}

	if (!$pid || $pid > 65535) {
		$pid = mt_rand(0, 0xfff) | 0x4000;
	}

	list($timeMid, $timeLow) = explode(' ', microtime());
	return sprintf(
		"%08x-%04x-%04x-%02x%02x-%04x%08x", (int)$timeLow, (int)substr($timeMid, 2) & 0xffff,
		mt_rand(0, 0xfff) | 0x4000, mt_rand(0, 0x3f) | 0x80, mt_rand(0, 0xff), $pid, $node
	);
}

?>
