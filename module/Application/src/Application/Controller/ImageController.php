<?php

namespace Application\Controller;

use Application\Entity\ImageTemp;

use Zend\View\Model\JsonModel;
use Application\File\FileUploader;
use Zend\EventManager\EventManagerInterface;

class ImageController extends AbstractServerController {
	
	
	public function indexAction() {

		echo 'fuck image';
		return false;
	}
	
	public function deleteAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$uid = $this->server->getOwnerId();
			$image_id = $request->getPost('image_id');
			
			$imageModel = $this->getServiceLocator()->get('Application\Model\ImageModel');
			
			$imageModel->delete($image_id, $uid);
			
			$resultStatus->setCM($resultStatus::SUCCESS, '操作成功');
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function meAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$user_id = $this->server->getOwnerId();
				
			$imageModel = $this->getServiceLocator()->get('Application\Model\ImageModel');		
			$images = $imageModel->getByUser($user_id);
				
			return new JsonModel($images);
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function userAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$user_id = $request->getQuery('user_id');
		
			$imageModel = $this->getServiceLocator()->get('Application\Model\ImageModel');
			$images = $imageModel->getByUser($user_id);
		
			return new JsonModel($images);
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
}

?>