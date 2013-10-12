<?php

namespace Gig\Controller;


use Zend\View\Model\JsonModel;

use Gig\Entity\Extra;

class ExtraController extends AbstractServerController {
	
	public function indexAction() {
		echo "xxx";
		return false;
	}
	
	
	/**
	 * need post {gig_id, title, price, time, access_token}
	 * @return \Zend\View\Model\JsonModel  */
	public function createAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$user_id = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($user_id);
			
			$gig_id = $request->getPost('gig_id');
			$gigModel = $this->getServiceLocator()->get('GigModel');
			$gig = $gigModel->getGig($gig_id, $user_id);
			
			$title = $request->getPost('title');
			$price = $request->getPost('price');
			$extra_time = $request->getPost('time');
				
			$extra = new Extra();
			$extra->setTitle($title)
				->setPrice($price)
				->setExtra_time($extra_time)
				->setUser($user)
				->setGig($gig);
			
			$extraModel = $this->getServiceLocator()->get('ExtraModel');
			$extra = $extraModel->create($extra);
			
			$resultStatus->setCMD($resultStatus::SUCCESS, "添加额外服务成功", array('extra'=>$extra));
			
		}
		
		return new JsonModel($resultStatus->getCMD());
	}
}

?>