<?php

namespace Gig\Controller;

use Gig\Entity\Message;

use Zend\View\Model\JsonModel;

class MessageController extends AbstractServerController{
	
	public function indexAction(){
		echo "ta api";
		return false;
	}
	
	public function sendAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$user_id = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($user_id);
			
			$msg = $request->getPost('msg');
			$to_id = $request->getPost('to');
			$to = $userModel->getUserObjectById($to_id);
			
			$message = new Message();
			$message->setMsg($msg)
				->setTo($to)
				->setUser($user);
			
			
			$messageModel = $this->getServiceLocator()->get('MessageModel');
			$message = $messageModel->create($message);
			$resultStatus->setCMD($resultStatus::SUCCESS, '发送成功', array('msg' => $message));
		}
		
		return new JsonModel($resultStatus->getCMD());
	}
	
	public function allAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$user_id = $this->server->getOwnerId();
			
			$messageModel = $this->getServiceLocator()->get('MessageModel');
			$messages = $messageModel->all($user_id);
			
			return new JsonModel($messages);
		}
		return new JsonModel($resultStatus->getCM());
	}
	
	public function detailAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$user_id = $this->server->getOwnerId();

			$to_id = $request->getQuery('to');
			
			
			$messageModel = $this->getServiceLocator()->get('MessageModel');
			$messages = $messageModel->detail($user_id, $to_id);
			
			return new JsonModel($messages);
		}
		
		return new JsonModel($resultStatus->getCM());
	}
}

?>