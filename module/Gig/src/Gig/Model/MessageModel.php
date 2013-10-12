<?php

namespace Gig\Model;

use Doctrine\Common\Collections\Criteria;

use OAuth\Lib\EntitySerializer;

class MessageModel extends AbstractModel {
	public function create($message) {
		$em = $this->getEntityManager();
		$em->persist($message);
		$em->flush();
		return $this->toArray($message);
	}
	
	public function all($user_id) {
		$em = $this->getEntityManager();
		
		$dql = "SELECT DISTINCT (msg.to_user_id) as to_user_id FROM \\Gig\\Entity\\Message msg WHERE (msg.user_id = $user_id OR msg.to_user_id = $user_id) ORDER BY msg.create_at DESC";
		$query = $em->createQuery($dql);
		$to_user_ids = $query->getResult();
		
		$messagesArray = array();
		foreach ($to_user_ids as $to_user_id) {
			$criteria = $this->detail_criteria($user_id, $to_user_id)->setMaxResults(1);
			$messages = $em->getRepository('Gig\Entity\Message')->matching($criteria);
			if ($messages[0] != null) {
				$messagesArray[] = $this->toArray($messages[0]);
			}
		}

		
		return $messagesArray;
	}
	
	private function detail_criteria($user_id , $to_user_id) {
		$criteria = new Criteria();
		$criteria->where($criteria->expr()->orX(
				$criteria->expr()->andX(
						$criteria->expr()->eq('user_id', $user_id),
						$criteria->expr()->eq('to_user_id', $to_user_id)
				),
				$criteria->expr()->andX(
						$criteria->expr()->eq('user_id', $to_user_id),
						$criteria->expr()->eq('to_user_id', $user_id)
				)
		))->orderBy(array('create_at' => Criteria::DESC));
		
		return $criteria;
	}
	
	public function detail($user_id , $to_user_id) {
		$em = $this->getEntityManager ();
		
		$criteria = $this->detail_criteria($user_id, $to_user_id);
		$messages = $em->getRepository('Gig\Entity\Message')->matching($criteria);
		
		$messagesArray = array();
		foreach ($messages as $message) {
			$messagesArray[] = $this->toArray($message);
		}
		
		return $messagesArray;
	}
	
	public function toArray($message) {
		$em = $this->getEntityManager();
		$entitySer = new EntitySerializer($em);
		if ($message != null) {
			$user = $message->getUser();
			if ($user != null) {
				$user = $entitySer->toArray($user);
				unset($user['password']);
				unset($user['email']);
			}
			
			$to = $message->getTo();
			if ($to != null) {
				$to = $entitySer->toArray($to);
				unset($to['password']);
				unset($to['email']);
			}
			
			$message = $entitySer->toArray($message);
			$message['user'] = $user;
			$message['to'] = $to;
		}
		
		return $message;
	}
}

?>