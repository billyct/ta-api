<?php

namespace Application\Controller;

use Application\Entity\ImageTemp;
use Zend\View\Model\JsonModel;
use Application\File\FileUploader;

class ImageTempController extends AbstractServerController {
	
	
	
	public function indexAction() {

		echo 'fuck TEMP image';
		return false;
	}
	

	
	public function uploadAction() {
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			
			//配置文件上传限制
			$allowedExtensions = array("jpeg", "jpg", "png");
			$sizeLimit = 5 * 1024 * 1024;
			$uploader = new FileUploader($allowedExtensions, $sizeLimit);
			//上传图片，以md5(uniqid())来生成图片名称
			$result = $uploader->handleUpload(PUBLICPATH.'/uploads/temp/images');
			//定义大小图片图片的基于public的路径
 			$path = '/uploads/temp/images/'.$uploader->getUploadName();
// 			$path_thumb = '/uploads/temp/image_thumbs/'.$uploader->getUploadFileName().'_thumb'.$uploader->getUploadExt();
			
 			$image_id = $this->saveImage($path);
			if ($image_id) {
				$result['image'] = array(
						'path' => BASEURL.$path,
						'id' => $image_id
						);	
			}
			return new JsonModel($result);
		}
		return new JsonModel($resultStatus->getCM());
	}
	
	public function deleteAction() {
		$resultStatus = $this->resultStatus;
		$request = $this->getRequest();
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$imageTempModel = $this->getServiceLocator()->get('Application\Model\ImageTempModel');
			
			$userModel = $this->getServiceLocator()->get('UserModel');
			$uid = $this->server->getOwnerId();
			$user = $userModel->getUserObjectById($uid);
			
			if ($imageTempModel->delete($request->getPost('image_id'), $user)) {
				$resultStatus->setCM($resultStatus::SUCCESS, '删除成功');
			}
		}
		return new JsonModel($resultStatus->getCM());
	}
	
	

	
	public function saveImage($path) {
		$userModel = $this->getServiceLocator()->get('UserModel');
		$uid = $this->server->getOwnerId();
		$user = $userModel->getUserObjectById($uid);
		$imageTemp = new ImageTemp();
		$imageTemp->setPath($path)
		->setUser($user);
			
		$imageTempModel = $this->getServiceLocator()->get('Application\Model\ImageTempModel');
		return $imageTempModel->insert($imageTemp);
	}
}

?>