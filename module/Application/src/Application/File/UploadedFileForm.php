<?php

namespace Application\File;

class UploadedFileForm {
	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	public function save($path) {
		return move_uploaded_file($_FILES['ta-files']['tmp_name'], $path);
	}
	
	/**
	 * Get the original filename
	 * @return string filename
	 */
	public function getName() {
		return $_FILES['ta-files']['name'];
	}
	
	/**
	 * Get the file size
	 * @return integer file-size in byte
	 */
	public function getSize() {
		return $_FILES['ta-files']['size'];
	}
}

?>