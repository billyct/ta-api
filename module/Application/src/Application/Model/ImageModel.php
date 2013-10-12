<?php

namespace Application\Model;

use Application\Lib\EntitySerializer;

class ImageModel extends AbstractModel {
	
	
	/**
	 * 将图片信息存入数据库
	 * @param \Application\Entity\Image $image 
	 * @return \Application\Entity\Image 刚刚插入的图片
	 *  */
	public function insert(\Application\Entity\Image $image) {
		$em = $this->getEntityManager();
		$em->persist($image);
		$em->flush();
		return $image;
	}
	
	/**
	 * 将一堆图片信息存入数据库
	 * @param array:\Application\Entity\Image $images 
	 * @return array:\Application\Entity\Image 刚刚插入的图片信息
	 * */
	public function insertImages(array $images) {
		$imagesInserted = array();
		foreach ($images as $image) {
			$imageInserted = $this->insert($image);
			array_push($imagesInserted, $imageInserted);
		}
		
		return $imagesInserted;
	}
	
	/**
	 * 删除图片
	 * @param int $id
	 * @return boolean  */
	public function delete($id) {
		$em = $this->getEntityManager();
		
		$image = $em->find('Application\Entity\Image', $id);
		
		if (unlink(PUBLICPATH.$image->getPath()) && unlink(PUBLICPATH.$image->getPath_thumb())) {
			$em->remove($image);
			$em->flush();
			return true;
		}
		
		return false;
	}
	
	public function getImage($id) {
		$em = $this->getEntityManager();
		$image = $em->find('Application\Entity\Image', $id);
		return $image;
	}
	
	public function getByUser($user_id) {
		$em = $this->getEntityManager();
		$entitySer = new EntitySerializer($em);
		$images = $em->getRepository('Application\Entity\Image')
						->findBy(array('user_id' => $user_id));
		$imagesArray = array();
		foreach ($images as $image) {
			$image = $entitySer->toArray($image);
			$image['path'] = BASEURL.$image['path'];
			$image['path_thumb'] = BASEURL.$image['path_thumb'];
			$imagesArray[] = $image;
		}
		
		return $imagesArray;
	}
}

?>