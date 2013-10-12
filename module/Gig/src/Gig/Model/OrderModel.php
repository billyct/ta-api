<?php

namespace Gig\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;

use OAuth\Lib\EntitySerializer;

class OrderModel extends AbstractModel {
	public function create($order) {
		$em = $this->getEntityManager();
		$em->persist($order);
		$em->flush();
		
		return $order->getId();
	}
	
	public function getById($order_id) {
		$em = $this->getEntityManager();
		$order = $em->find('Gig\Entity\Order', $order_id);
		
		return $order;
	}
	
	public function getByIdArray($order_id, $user_id ) {
		$order = $this->getById($order_id);
		if ($order != null) {
			$order = $this->toArray($order);
		}
		
		if ($order['user_id'] == $user_id){
			return $order;
		}
		
		
		return null;
	}
	
	public function getByIdOwnerArray($order_id, $owner_id) {
		$order = $this->getById($order_id);
		if ($order != null) {
			$order = $this->toArray($order);
		}
		
		if ($order['owner_id'] == $owner_id) {
			return $order;
		}
		
		return null;
	}
	
	public function getOrderPayed($user_id) {
		$em = $this->getEntityManager();
		$orders = $em->getRepository('Gig\Entity\Order')
					->findBy(array(
							'owner_id' => $user_id,
							'status' => 1
					));
		$ordersArray = array();
		foreach ($orders as $order) {
			$ordersArray[] = $this->toArray($order);
		}
		
		return $ordersArray;
	}
	
	public function getByOwner($user_id) {
		
 		$em = $this->getEntityManager();
		
		
		$orders = $em->getRepository('Gig\Entity\Order')
					->findBy(array(
							'owner_id' => $user_id
							));
		$ordersArray = array();
		foreach ($orders as $order) {
			$ordersArray[] = $this->toArray($order);
		}
		
		return $ordersArray;
	}
	
	public function getByUser($user_id) {
		$em = $this->getEntityManager();

		$orders = $em->getRepository('Gig\Entity\Order')
						->findBy(array(
								'user_id' => $user_id
								));
						
		$orderArray = array();
		
		foreach ($orders as $order) {
			$orderArray[] = $this->toArray($order);
		}
		
		return $orderArray;
	}
	
	public function toArray($order) {
		$em = $this->getEntityManager();
		$entitySer = new EntitySerializer($em);
		
		$gig = $order->getGig();
		if ($gig != null) {
			$gig = $entitySer->toArray($gig);
		}


		
		
		$extras = array();
		foreach ($order->getExtras() as $extra) {
			$extras[] = $entitySer->toArray($extra);
		}
		
		$order = $entitySer->toArray($order);
		
		$order['gig'] = $gig;
		$order['extras'] = $extras;
		
		return $order;
	}
}

?>