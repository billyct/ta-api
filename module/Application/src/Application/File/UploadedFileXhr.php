<?php

namespace Application\File;

class UploadedFileXhr {
	
	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	public function save($path) {
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);
	
		if ($realSize != $this->getSize()){
			return false;
		}
	
		$target = fopen($path, "w");
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);
	
		return true;
	}
	
	/**
	 * Get the original filename
	 * @return string filename
	 */
	public function getName() {
		return $_GET['ta-files'];
	}
	
	/**
	 * Get the file size
	 * @return integer file-size in byte
	 */
	public function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])){
			return (int)$_SERVER["CONTENT_LENGTH"];
		} else {
			throw new Exception('Getting content length is not supported.');
		}
	}
}

?>