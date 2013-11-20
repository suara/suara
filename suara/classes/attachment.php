<?php 
class attachment {
	var $contentid;
	var $module;
	var $catid;
	var $attachments;
	var $field;
	var $imageexts = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
	var $uploadedfiles = array();
	var $downloadedfiles = array();
	var $error;
	var $upload_root;
	var $siteid;
	var $site = array();


	function __construct($upload_dir = '') {
		s_core::load_sys_func('dir');		
		s_core::load_sys_class('image','','0');
		$this->upload_root = s_core::load_config('system','upload_path');
		$this->upload_func = 'copy';
		$this->upload_dir = $upload_dir;
	}

	/**
	 * 附件上传方法
	 * @param $field 上传字段
	 * @param $thumb_setting 缩略图设置
	 * @param $alowexts 允许上传类型
	 * @param $maxsize 最大上传大小
	 * @param $overwrite 是否覆盖原有文件
	 * @param $watermark_enable  是否添加水印
	 */
	function upload($field, $thumb_setting = array(), $alowexts = 'jpeg|jpg|gif|png', $maxsize = 0, $overwrite = 0, $watermark_enable = 0) {
		if(!isset($_FILES[$field])) {
			return false;
		}

		$this->field = $field;
		$this->savepath = $this->upload_root.$this->upload_dir.date('Y/md/');
		$this->alowexts = $alowexts;
		$this->maxsize = $maxsize;
		$this->overwrite = $overwrite;
		$uploadfiles = array();
		//$description = isset($GLOBALS[$field.'_description']) ? $GLOBALS[$field.'_description'] : array();

		if(is_array($_FILES[$field]['error'])) {
			$this->uploads = count($_FILES[$field]['error']);
			foreach($_FILES[$field]['error'] as $key => $error) {
				if($error === UPLOAD_ERR_NO_FILE) continue;
				if($error !== UPLOAD_ERR_OK) {
					return false;
				}

				$uploadfiles[$key] = array('tmp_name' => $_FILES[$field]['tmp_name'][$key], 'name' => $_FILES[$field]['name'][$key], 'type' => $_FILES[$field]['type'][$key], 'size' => $_FILES[$field]['size'][$key], 'error' => $_FILES[$field]['error'][$key]);
			}
		} else {
			$this->uploads = 1;
			if(!$description) $description = '';
			$uploadfiles[0] = array('tmp_name' => $_FILES[$field]['tmp_name'], 'name' => $_FILES[$field]['name'], 'type' => $_FILES[$field]['type'], 'size' => $_FILES[$field]['size'], 'error' => $_FILES[$field]['error']);
		}

		if(!dir_create($this->savepath)) {
			return false;
		}
		if(!is_dir($this->savepath)) {
			return false;
		}
		@chmod($this->savepath, 0777);

		if(!is_writeable($this->savepath)) {
			return false;
		}

		$aids = array();
		$ch = curl_init();

		foreach($uploadfiles as $k=>$file) {
			$fileext = fileext($file['name']);
			if($file['error'] != 0) {
				$this->error = $file['error'];
				return false;				
			}
			if(!preg_match("/^(".$this->alowexts.")$/", $fileext)) {
				$this->error = '10';
				return false;
			}
			if($this->maxsize && $file['size'] > $this->maxsize) {
				$this->error = '11';
				return false;
			}
			if(!$this->isuploadedfile($file['tmp_name'])) {
				$this->error = '12';
				return false;
			}
			$temp_filename = $this->getname($fileext);
			$savefile = $this->savepath.$temp_filename;

			$savefile = preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2", $savefile);
			$filepath = preg_replace(new_addslashes("|^".$this->upload_root."|"), "", $savefile);

			if(!$this->overwrite && file_exists($savefile)) continue;
			$upload_func = $this->upload_func;

			if(@$upload_func($file['tmp_name'], $savefile)) {
				$this->uploadeds++;
				@chmod($savefile, 0644);
				@unlink($file['tmp_name']);
				$file['name'] = iconv("utf-8",CHARSET,$file['name']);
				$uploadedfile = array('filename'=>$file['name'], 'filepath'=>$filepath, 'filesize'=>$file['size'], 'fileext'=>$fileext);
				$thumb_enable = is_array($thumb_setting) && ($thumb_setting[0] > 0 || $thumb_setting[1] > 0 ) ? 1 : 0;	

				$image = new image($thumb_enable);				
				if($thumb_enable) {
					$image->thumb($savefile,'',$thumb_setting[0],$thumb_setting[1]);
				}
				//if($watermark_enable) {
				//    $image->watermark($savefile, $savefile);
				//}

				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_VERBOSE, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, "http://upload.plu.cn/upload.php");
				curl_setopt($ch, CURLOPT_POST, true);

				$post = array(
					"_suara"=> "@".$savefile,
					"filename"=> "_suara",
					'format' => 'json'
				);

				//in php 5.5 decard @
				if (PHP_MAJOR_VERSION >= 5 && PHP_MINOR_VERSION >= 5) {
					$post['_suara'] = new CURLFile($savefile, $file['type'], $temp_filename);
				}
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
				$response = curl_exec($ch);
				$curl_errno = curl_errno($ch);
				$curl_error = curl_error($ch);

				$response = @json_decode($response);
				if (!empty($response) && $response->success) {
					$uploadedfile['filepath'] = $response->url;
					$aids[] = $uploadedfile;
				}
			}
		}
		curl_close($ch);
		return $aids;
	}

	/**
	 * 获取缩略图地址..
	 * @param $image 图片路径
	 */
	function get_thumb($image){
		return str_replace('.', '_thumb.', $image);
	}


	/**
	 * 获取附件名称
	 * @param $fileext 附件扩展名
	 */
	function getname($fileext){
		return date('Ymdhis').rand(100, 999).'.'.$fileext;
	}

	/**
	 * 返回附件大小
	 * @param $filesize 图片大小
	 */
	function size($filesize) {
		if($filesize >= 1073741824) {
			$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
		} elseif($filesize >= 1048576) {
			$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
		} elseif($filesize >= 1024) {
			$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
		} else {
			$filesize = $filesize . ' Bytes';
		}
		return $filesize;
	}

	/**
	 * 判断文件是否是通过 HTTP POST 上传的
	 *
	 * @param	string	$file	文件地址
	 * @return	bool	所给出的文件是通过 HTTP POST 上传的则返回 TRUE
	 */
	function isuploadedfile($file) {
		return is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file));
	}
}
?>
