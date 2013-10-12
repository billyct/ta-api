<?php

namespace Gig\Controller;

use Zend\View\Model\ViewModel;

use Gig\Entity\Order;

use Zend\View\Model\JsonModel;

class OrderController extends AbstractServerController{
	
	public function indexAction() {
		
	}
	
	public function createAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$user_id = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($user_id);
			
			$sum = $request->getPost('sum');
			$gig_id = $request->getPost('gig_id');
			
			$gigModel = $this->getServiceLocator()->get('GigModel');
			$gig = $gigModel->getGig($gig_id);
			
			$extras = $request->getPost('extras');
			
			$order = new Order();
			$order->setUser($user)
				->setGig($gig)
				->setOwner($gig->getUser())
				->setSum($sum);
			
			$total_price = 0;
			$total_price += $gig->getPrice();
			
			
			if (!empty($extras)) {
				$extraModel = $this->getServiceLocator()->get('ExtraModel');
				$extras = $extraModel->getByIds($extras);
				foreach ($extras as $extra){
					$total_price += $extra->getPrice();
					$order->getExtras()->add($extra);
				}
			}
			
			$order->setTotal($total_price);
			
			$orderModel = $this->getServiceLocator()->get('OrderModel');
			$order_id = $orderModel->create($order);
			
			if ($order_id != null) {
				$resultStatus->setCMD($resultStatus::SUCCESS, '订单创建成功', array('order_id' => $order_id));
			}
			 
			
		}
		
		return new JsonModel($resultStatus->getCMD());
	}
	
	
	public function myBuysAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$user_id = $this->server->getOwnerId();
			
			$order_id = $request->getQuery('order_id');
			$orderModel = $this->getServiceLocator()->get('OrderModel');
			$result = null;
			if ($order_id != null) {
				$result = $orderModel->getByIdArray($order_id, $user_id);
			} else {
				$result = $orderModel->getByUser($user_id);
			}

			return new JsonModel($result);
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function mySellsAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$user_id = $this->server->getOwnerId();
			
			$order_id = $request->getQuery('order_id');
			
			$orderModel = $this->getServiceLocator()->get('OrderModel');
			
			$result = null;
			if ($order_id != null) {
				$result = $orderModel->getByIdOwnerArray($order_id, $user_id);
			}else {
				$result = $orderModel->getByOwner($user_id);
			}
	
			return new JsonModel($result);
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function incomeAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$user_id = $this->server->getOwnerId();
				
			$orderModel = $this->getServiceLocator()->get('OrderModel');
			$result = $orderModel->getOrderPayed($user_id);
		
			return new JsonModel($result);
		}
		
		return new JsonModel($resultStatus->getCM());
	}


}

?>