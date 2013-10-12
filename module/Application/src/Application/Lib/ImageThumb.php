<?php

namespace Application\Lib;

use Application\Exceptions\FileException;

class ImageThumb {
	
	private $thumbnailer;
	
	private $thumb_width;
	
	
	public function __construct() {
		$this->thumb_width = 150;
	}
	
	/**
	 * @return the $thumbnailer
	 */
	public function getThumbnailer() {
		return $this->thumbnailer;
	}

	/**
	 * @param field_type $thumbnailer
	 */
	public function setThumbnailer($thumbnailer) {
		$this->thumbnailer = $thumbnailer;
	}
	

	/**
	 * 将一堆缓存图片移动到真正的存放图片的地方，并生成小图片
	 * @param array Application\Entity\ImageTemp $imageTemps
	 * @throws FileException
	 * @return multitype: array('path' => $real_path, 'path_thumb' => $real_path_thumb)
	 * */
	public function move($imageTemps) {
		$image_paths = array();
		foreach ($imageTemps as $imageTemp) {
			$image_path = $this->moveOne($imageTemp);
			array_push($image_paths, $image_path);
		}
	
		return $image_paths;
	}
	
	
	/**
	 * 将一张缓存图片移动到真正的存放图片的地方，并生成小图片
	 * @param Application\Entity\ImageTemp $imageTemp
	 * @throws FileException
	 * @return array  */
	public function moveOne(\Application\Entity\ImageTemp $imageTemp) {
		$temp_path = $imageTemp->getPath();
		if (!file_exists(PUBLICPATH.$temp_path)) {
			throw new FileException($temp_path.'文件不存在');
		}
		$path_parts = pathinfo(PUBLICPATH.$temp_path);
		$filename = $path_parts['basename'];
		$extension = $path_parts['extension'];
		
		$real_path = '/uploads/images/'.$filename;
		$real_path_thumb = '/uploads/image_thumbs/'.$filename;
		$result = copy($this->publicpath($temp_path), $this->publicpath($real_path));
		
		if (!$result) {
			throw new FileException('文件拷贝错误');
		}
		
		$this->thumb($this->publicpath($real_path), $this->publicpath($real_path_thumb));
		$image_path = array('path' => $real_path, 'path_thumb' => $real_path_thumb);
		return $image_path;
	}
	
	public function publicpath($path) {
		return PUBLICPATH.$path;
	}
	
	/**
	 * 为path路径的图片生成path_thumb路径的小图片
	 * @param string $path
	 * @param string $path_thumb
	 * */
	public function thumb($path, $path_thumb) {
		$thumbnailer = $this->getThumbnailer();
		$thumb = $thumbnailer->create($path);
		$thumb_width = $this->getThumb_width();
		$imageInfo = getimagesize($path);
		$imageInfo = ($imageInfo[0]>$imageInfo[1])?$imageInfo[0]:$imageInfo[1];
			
		if ($imageInfo > $thumb_width) {
			$thumb->resizePercent($thumb_width/$imageInfo*100);
		}
		$thumb->save($path_thumb);
	}
	/**
	 * @return the $thumb_width
	 */
	public function getThumb_width() {
		return $this->thumb_width;
	}

	/**
	 * @param field_type $thumb_width
	 */
	public function setThumb_width($thumb_width) {
		$this->thumb_width = $thumb_width;
	}

}

?>