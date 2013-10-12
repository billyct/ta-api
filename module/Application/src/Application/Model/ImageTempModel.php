<?php

namespace Application\Model;

use Application\Lib\EntitySerializer;

use Application\Entity\ImageTemp;

class ImageTempModel extends AbstractModel{
	/**
	 * 删除图片
	 * @param int $id
	 * @return boolean  
	 * */
	public function delete($id, $user) {
		$em = $this->getEntityManager();
	
		$image = $em->find('Application\Entity\ImageTemp', $id);
		
		if ( $image->getUser()->getId() == $user->getId() ) {
			if (file_exists(PUBLICPATH.$image->getPath())) {
				unlink(PUBLICPATH.$image->getPath());
			}
			$em->remove($image);
			$em->flush();
			return true;
		}
		
		return false;
	}
	
	/**
	 * 删除一堆图片
	 * @param int $id
	 * @param OAuth\Entity\User $user
	 * @return boolean
	 * */
	public function deleteByIds($ids, $user) {
		foreach ($ids as $id) {
			$this->delete($id, $user);
		}
	}
	
	
	/**
	 * 添加图片数据到数据库
	 * @param ImageTemp $imageTemp  
	 * @return int 图片ID
	 * */
	public function insert(ImageTemp $imageTemp) {
		$em = $this->getEntityManager();
		$em->persist($imageTemp);
		$em->flush();
		return $imageTemp->getId();
	}
	
	/**
	 * 获取缓存图片的对象
	 * @param int $id
	 * @return ImageTemp  
	 * */
	public function getObjectById($id) {
		$em = $this->getEntityManager();
		$imageTemp = $em->find('Application\Entity\ImageTemp', $id);
		return $imageTemp;		
	}
	
	
	/**
	 * 获取缓存图片的数组
	 * @param int $id
	 * @return array  
	 * */
	public function getArrayById($id) {
		$em = $this->getEntityManager();
		$imageTemp = $em->find('Application\Entity\ImageTemp', $id);
		if ($imageTemp != null) {
			$entitySer = new EntitySerializer($em);
			$imageTemp = $entitySer->toArray($imageTemp);
		}
		return $imageTemp;
	}
	
	
	/**
	 * 获取缓存图片的对象数组
	 * @param array $ids
	 * @return array:ImageTemp  
	 * */
	public function getObjectByIds(array $ids) {
		$imageTemps = array();
		foreach ($ids as $id) {
			array_push($imageTemps, $this->getObjectById($id));
		}	
		return $imageTemps;
	}
	
	
	/**
	 * 获取缓存图片的数组的数组
	 * @param array $ids
	 * @return array:array
	 * */
	public function getArrayByIds(array $ids) {
		$imageTemps = array();
		foreach ($ids as $id) {
			array_push($imageTemps, $this->getArrayById($id));
		}
		return $imageTemps;
	}
}

?>