<?php
/**
 * 输出控制
 *
 */
namespace Suara\Libs\Web;
use Suara\Libs\Core\Configure;

class Response {
	/**
	 * HTTP Status code definitions
	 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	 */
	protected $_statusCodes = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	];

	protected $_mimeTypes = [
		'html' => ['text/html', '*/*'],
		'json' => 'application/json',
		'xml' => ['application/xml', 'text/xml'],
		'rss' => 'application/rss+xml',
		'ai' => 'application/postscript',
		'bcpio' => 'application/x-bcpio',
		'bin' => 'application/octet-stream',
		'ccad' => 'application/clariscad',
		'cdf' => 'application/x-netcdf',
		'class' => 'application/octet-stream',
		'cpio' => 'application/x-cpio',
		'cpt' => 'application/mac-compactpro',
		'csh' => 'application/x-csh',
		'csv' => ['text/csv', 'application/vnd.ms-excel', 'text/plain'],
		'dcr' => 'application/x-director',
		'dir' => 'application/x-director',
		'dms' => 'application/octet-stream',
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'drw' => 'application/drafting',
		'dvi' => 'application/x-dvi',
		'dwg' => 'application/acad',
		'dxf' => 'application/dxf',
		'dxr' => 'application/x-director',
		'eot' => 'application/vnd.ms-fontobject',
		'eps' => 'application/postscript',
		'exe' => 'application/octet-stream',
		'ez' => 'application/andrew-inset',
		'flv' => 'video/x-flv',
		'gtar' => 'application/x-gtar',
		'gz' => 'application/x-gzip',
		'bz2' => 'application/x-bzip',
		'7z' => 'application/x-7z-compressed',
		'hdf' => 'application/x-hdf',
		'hqx' => 'application/mac-binhex40',
		'ico' => 'image/x-icon',
		'ips' => 'application/x-ipscript',
		'ipx' => 'application/x-ipix',
		'js' => 'application/javascript',
		'latex' => 'application/x-latex',
		'lha' => 'application/octet-stream',
		'lsp' => 'application/x-lisp',
		'lzh' => 'application/octet-stream',
		'man' => 'application/x-troff-man',
		'me' => 'application/x-troff-me',
		'mif' => 'application/vnd.mif',
		'ms' => 'application/x-troff-ms',
		'nc' => 'application/x-netcdf',
		'oda' => 'application/oda',
		'otf' => 'font/otf',
		'pdf' => 'application/pdf',
		'pgn' => 'application/x-chess-pgn',
		'pot' => 'application/vnd.ms-powerpoint',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		'ppz' => 'application/vnd.ms-powerpoint',
		'pre' => 'application/x-freelance',
		'prt' => 'application/pro_eng',
		'ps' => 'application/postscript',
		'roff' => 'application/x-troff',
		'scm' => 'application/x-lotusscreencam',
		'set' => 'application/set',
		'sh' => 'application/x-sh',
		'shar' => 'application/x-shar',
		'sit' => 'application/x-stuffit',
		'skd' => 'application/x-koan',
		'skm' => 'application/x-koan',
		'skp' => 'application/x-koan',
		'skt' => 'application/x-koan',
		'smi' => 'application/smil',
		'smil' => 'application/smil',
		'sol' => 'application/solids',
		'spl' => 'application/x-futuresplash',
		'src' => 'application/x-wais-source',
		'step' => 'application/STEP',
		'stl' => 'application/SLA',
		'stp' => 'application/STEP',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc' => 'application/x-sv4crc',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',
		'swf' => 'application/x-shockwave-flash',
		't' => 'application/x-troff',
		'tar' => 'application/x-tar',
		'tcl' => 'application/x-tcl',
		'tex' => 'application/x-tex',
		'texi' => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'tr' => 'application/x-troff',
		'tsp' => 'application/dsptype',
		'ttc' => 'font/ttf',
		'ttf' => 'font/ttf',
		'unv' => 'application/i-deas',
		'ustar' => 'application/x-ustar',
		'vcd' => 'application/x-cdlink',
		'vda' => 'application/vda',
		'xlc' => 'application/vnd.ms-excel',
		'xll' => 'application/vnd.ms-excel',
		'xlm' => 'application/vnd.ms-excel',
		'xls' => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'xlw' => 'application/vnd.ms-excel',
		'zip' => 'application/zip',
		'aif' => 'audio/x-aiff',
		'aifc' => 'audio/x-aiff',
		'aiff' => 'audio/x-aiff',
		'au' => 'audio/basic',
		'kar' => 'audio/midi',
		'mid' => 'audio/midi',
		'midi' => 'audio/midi',
		'mp2' => 'audio/mpeg',
		'mp3' => 'audio/mpeg',
		'mpga' => 'audio/mpeg',
		'ogg' => 'audio/ogg',
		'oga' => 'audio/ogg',
		'spx' => 'audio/ogg',
		'ra' => 'audio/x-realaudio',
		'ram' => 'audio/x-pn-realaudio',
		'rm' => 'audio/x-pn-realaudio',
		'rpm' => 'audio/x-pn-realaudio-plugin',
		'snd' => 'audio/basic',
		'tsi' => 'audio/TSP-audio',
		'wav' => 'audio/x-wav',
		'aac' => 'audio/aac',
		'asc' => 'text/plain',
		'c' => 'text/plain',
		'cc' => 'text/plain',
		'css' => 'text/css',
		'etx' => 'text/x-setext',
		'f' => 'text/plain',
		'f90' => 'text/plain',
		'h' => 'text/plain',
		'hh' => 'text/plain',
		'htm' => ['text/html', '*/*'],
		'ics' => 'text/calendar',
		'm' => 'text/plain',
		'rtf' => 'text/rtf',
		'rtx' => 'text/richtext',
		'sgm' => 'text/sgml',
		'sgml' => 'text/sgml',
		'tsv' => 'text/tab-separated-values',
		'tpl' => 'text/template',
		'txt' => 'text/plain',
		'text' => 'text/plain',
		'avi' => 'video/x-msvideo',
		'fli' => 'video/x-fli',
		'mov' => 'video/quicktime',
		'movie' => 'video/x-sgi-movie',
		'mpe' => 'video/mpeg',
		'mpeg' => 'video/mpeg',
		'mpg' => 'video/mpeg',
		'qt' => 'video/quicktime',
		'viv' => 'video/vnd.vivo',
		'vivo' => 'video/vnd.vivo',
		'ogv' => 'video/ogg',
		'webm' => 'video/webm',
		'mp4' => 'video/mp4',
		'm4v' => 'video/mp4',
		'f4v' => 'video/mp4',
		'f4p' => 'video/mp4',
		'm4a' => 'audio/mp4',
		'f4a' => 'audio/mp4',
		'f4b' => 'audio/mp4',
		'gif' => 'image/gif',
		'ief' => 'image/ief',
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpe' => 'image/jpeg',
		'pbm' => 'image/x-portable-bitmap',
		'pgm' => 'image/x-portable-graymap',
		'png' => 'image/png',
		'pnm' => 'image/x-portable-anymap',
		'ppm' => 'image/x-portable-pixmap',
		'ras' => 'image/cmu-raster',
		'rgb' => 'image/x-rgb',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'xbm' => 'image/x-xbitmap',
		'xpm' => 'image/x-xpixmap',
		'xwd' => 'image/x-xwindowdump',
		'ice' => 'x-conference/x-cooltalk',
		'iges' => 'model/iges',
		'igs' => 'model/iges',
		'mesh' => 'model/mesh',
		'msh' => 'model/mesh',
		'silo' => 'model/mesh',
		'vrml' => 'model/vrml',
		'wrl' => 'model/vrml',
		'mime' => 'www/mime',
		'pdb' => 'chemical/x-pdb',
		'xyz' => 'chemical/x-pdb',
		'javascript' => 'application/javascript',
		'form' => 'application/x-www-form-urlencoded',
		'file' => 'multipart/form-data',
		'xhtml' => ['application/xhtml+xml', 'application/xhtml', 'text/xhtml'],
		'xhtml-mobile' => 'application/vnd.wap.xhtml+xml',
		'atom' => 'application/atom+xml',
		'amf' => 'application/x-amf',
		'wap' => ['text/vnd.wap.wml', 'text/vnd.wap.wmlscript', 'image/vnd.wap.wbmp'],
		'wml' => 'text/vnd.wap.wml',
		'wmlscript' => 'text/vnd.wap.wmlscript',
		'wbmp' => 'image/vnd.wap.wbmp',
		'woff' => 'application/x-font-woff',
		'webp' => 'image/webp',
		'appcache' => 'text/cache-manifest',
		'manifest' => 'text/cache-manifest',
		'htc' => 'text/x-component',
		'rdf' => 'application/xml',
		'crx' => 'application/x-chrome-extension',
		'oex' => 'application/x-opera-extension',
		'xpi' => 'application/x-xpinstall',
		'safariextz' => 'application/octet-stream',
		'webapp' => 'application/x-web-app-manifest+json',
		'vcf' => 'text/x-vcard',
		'vtt' => 'text/vtt',
		'mkv' => 'video/x-matroska',
		'pkpass' => 'application/vnd.apple.pkpass'
	];

	protected $_protocol = 'HTTP/1.1';

	protected $_status = 200;

	protected $_contentType = 'text/html';

	protected $_headers = [];

	protected $_cookies = [];

	protected $_body = null;

	protected $_charset = 'UTF-8';
	
	public function __construct($options = []) {
		if (isset($options['body'])) {
			$this->body($options['body']);
		}

		if (isset($options['status'])) {
			$this->statusCode($options['status']);
		}

		if (isset($options['type'])) {
			//mime type
			$this->type($options['type']);
		}

		if (!isset($options['charset'])) {
			$options['charset'] = Configure::read('system', 'charset');
		}

		$this->charset($options['charset']);
	}

	public function send() {
		if (isset($this->_headers['Location']) && $this->_status == 200) {
			$this->statusCode(302);
		}

		$codeMessage = $this->_statusCodes[$this->_status];
		$this->_setCookie();
		//$this->_sendHeader("{$this->_protocol} {$this->_status} {$codeMessage}");
		//$this->_setContent();
		//$this->_setContentLength();
		//$this->_setContentType();

		//foreach ($this->_headers as $header => $values) {
		//	foreach ((array)$values as $value) {
		//		$this->_sendHeader($header, $value);
		//	}
		//}

		//file
		//$this->_sendContent($this->_body);
	}

	/**
	 * Set Response header
	 */
	public function header($header = null, $value = null) {
		if ($header == null) {
			return $this->_headers;
		}

		$headers = is_array($header) ? $header : [$header => $value];

		foreach ($headers as $header => $value) {
			if (is_numeric($header)) {
				list($header, $value) = array($value, null);
			}
			if ($value == null) {
				list($header, $value) = explode(":", $header, 2);
			}

			$this->_headers[$header] = is_array($value) ? array_map('trim', $value) : trim($value);
		}

		return $this->_headers;
	}

	public function body($content = null) {
		if ($content == null) {
			return $this->_body;
		}
		$this->_body = $content;
	}

	public function statusCode($code = null) {
		if ($code == null) {
			$code = $this->_status; //default 200
		}
		if (!isset($this->_statusCodes[$code])) {
			//throw
		}

		$this->status = $code;
	}

	/**
	 * mime type
	 */
	public function type($contentType = null) {
		if ($contentType == null) {
			return $this->_contentType;
		}

		if (is_array($contentType)) {

		}

		if (isset($this->_mimeTypes[$contentType])) {
			$contentType = $this->_mimeTypes[$contentType];
			$contentType = is_array($contentType) ? current($contentType) : $contentType;
		}

		if (strpos($contentType, '/') === false) {
			return false;
		}
		return $this->_contentType = $contentType;
	}

	public function charset() {

	}

	public function cookie() {

	}

	/**
	 * {{{
	 * Cache
	 */
	public function disableCache() {
		$this->header([
			'Expires' => 'Thu Jan 01 1970 00:00:00 GMT',
			'Last-Modified' => gmdate('D, d M Y h:i:s') . " GMT",
			'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'
		]);
	}

	public function cache($since, $time = "+1 day") {
		if (!is_int($time)) {
			$time = strtotime($time);
		}


		//$this->modified($since);
		$this->expires($time);

		$this->maxAge($time - time());
	}

	public function sharable($public = null, $time = null) {

	}

	public function sharedMaxAge() {

	}

	public function expires() {

	}

	public function modified() {

	}

	public function maxAge() {

	}

	public function mustRevalidate() {

	}

	protected function _setCacheControl() {

	}

	public function notModified() {

	}

	public function vary() {

	}

	public function etag() {

	}

	/**
	 * }}}
	 */


	private function _setCookie() {
		foreach ($this->_cookies as $name => $value) {
			setcookie($name, $value['value'], $value['expire'], $value['path'], $value['domain'], $value['secure'], $value['httpOnly']);
		}
	}

	private function _setContent() {
		if (in_array($this->status, array(304, 204))) {
			$this->body('');
		}
	}

	private function _setContentLength() {

	}

	private function _setContentType() {
		if (in_array($this->_status, array(304, 204))) {
			return;
		}
		$whitelist = array(
			'application/javascript', 'application/json', 'application/xml', 'application/rss+xml'
		);

		$charset = false;
		if ( $this->_charset && (strpos($this->_contentType, 'text/') === 0 || in_array($this->_contentType, $whitelist))) {
			$charset = true;
		}

		if ($charset) {
			$this->header('Content-Type', "{$this->_contentType}; charset={$this->_charset}");
		} else {
			$this->header('Content-Type', "{$this->_contentType}");
		}
	}

	private function _sendHeader($name, $value = null) {
		if (!headers_sent()) {
			if ($value == null) {
				header($name);
			} else {
				header("{$name}: {$value}");
			}
		}
	}

	private function _sendContent($content) {
		echo $content;
	}


	/**
	 * {{{
	 */
	public function compress() {
		if(function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
			ob_start('ob_gzhandler');
		} else {
			ob_start();
		}
	}

	public function outputCompressed() {

	}

	/**
	 * }}}
	 */
}
?>
