<?php

namespace Application\File;

class FileUploader {
	private $allowedExtensions;
	private $sizeLimit;
	private $file;
	private $uploadName;
	
	//(自己添加)
	private $uploadFileName;
	private $uploadExt;
	
	/**
	 * @param array $allowedExtensions; defaults to an empty array
	 * @param int $sizeLimit; defaults to the server's upload_max_filesize setting
	 */
	function __construct(array $allowedExtensions = null, $sizeLimit = null){
		if($allowedExtensions===null) {
			$allowedExtensions = array();
		}
		if($sizeLimit===null) {
			$sizeLimit = $this->toBytes(ini_get('upload_max_filesize'));
		}
		 
		$allowedExtensions = array_map("strtolower", $allowedExtensions);
	
		$this->allowedExtensions = $allowedExtensions;
		$this->sizeLimit = $sizeLimit;
	
		$this->checkServerSettings();
	
		if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') === 0) {
			$this->file = new UploadedFileForm();
		} else {
			$this->file = new UploadedFileXhr();
		}
	}
	
	/**
	 * Get the name of the uploaded file
	 * @return string
	 */
	public function getUploadName(){
		if( isset( $this->uploadName ) )
			return $this->uploadName;
	}
	
	/**
	 * Get the original filename
	 * @return string filename
	 */
	public function getName(){
		if ($this->file)
			return $this->file->getName();
	}
	
	/**
	 * 返回文件名不包含扩展名
	 * @return the $uploadFileName
	 */
	public function getUploadFileName() {
		if( isset( $this->uploadFileName ) )
			return $this->uploadFileName;
	}
	
	/**
	 * 返回文件的扩展名
	 * @return the $uploadExt
	 */
	public function getUploadExt() {
		return $this->uploadExt;
	}
	
	/**
	 * Internal function that checks if server's may sizes match the
	 * object's maximum size for uploads
	 */
	private function checkServerSettings(){
		$postSize = $this->toBytes(ini_get('post_max_size'));
		$uploadSize = $this->toBytes(ini_get('upload_max_filesize'));
	
		if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
			$size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
			die("{'error':'服务端的 post_max_size 和 upload_max_filesize 不足 $size'}");
		}
	}
	
	/**
	 * Convert a given size with units to bytes
	 * @param string $str
	 */
	private function toBytes($str){
		$val = trim($str);
		$last = strtolower($str[strlen($str)-1]);
		switch($last) {
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
		}
		return $val;
	}
	
	/**
	 * Handle the uploaded file
	 * @param string $uploadDirectory
	 * @param string $replaceOldFile=true
	 * @returns array('success'=>true) or array('error'=>'error message')
	 */
	function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
		if (!is_writable($uploadDirectory)){
			return array('error' => "服务器错误,上传目录不可写的.");
		}
	
		if (!$this->file){
			return array('error' => '没有上传的文件.');
		}
	
		$size = $this->file->getSize();
	
		if ($size == 0) {
			return array('error' => '文件是空的');
		}
	
		if ($size > $this->sizeLimit) {
			return array('error' => '文件太大了');
		}
	
		$pathinfo = pathinfo($this->file->getName());
		//$filename = $pathinfo['filename'];
		$filename = md5(uniqid());
		$ext = @$pathinfo['extension'];		// hide notices if extension is empty
	
		if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => '文件有一个无效的扩展名，它应该是一个'. $these . '.');
		}
	
		$ext = ($ext == '') ? $ext : '.' . $ext;
	
		if(!$replaceOldFile){
			/// don't overwrite previous files that were uploaded
			while (file_exists($uploadDirectory . DIRECTORY_SEPARATOR . $filename . $ext)) {
				$filename .= rand(10, 99);
			}
		}
	
		//(自己添加)
		$this->uploadFileName = $filename;
		$this->uploadExt = $ext;
	
		$this->uploadName = $filename . $ext;
	
		if ($this->file->save($uploadDirectory . DIRECTORY_SEPARATOR . $filename . $ext)){
			return array('success' => true);
		} else {
			return array('error'=> '无法保存上传的文件，上传被取消，或服务器遇到的错误');
		}
	
	}
}

?>