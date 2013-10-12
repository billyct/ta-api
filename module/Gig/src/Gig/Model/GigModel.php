<?php

namespace Gig\Model;

use Application\Lib\EntitySerializer;

use Doctrine\ORM\Tools\Pagination\Paginator;

use Gig\Entity\Gig;

class GigModel extends AbstractModel{
	
	public function create(Gig $gig) {
		$em = $this->getEntityManager();
		$em->persist($gig);
		$em->flush();
		
		return $gig->getId();
	}
	
	public function getGigArray($gig_id) {
		$em = $this->getEntityManager();
		$gig = $em->find('Gig\Entity\Gig', $gig_id);
		if ($gig != null) {
			$gig = $this->toArray($gig);
		}
		
		return $gig;
	}
	
	public function getGig($gig_id, $user_id = null) {
		$em = $this->getEntityManager();
		if ($user_id != null){
			$gig = $em->getRepository('Gig\Entity\Gig')
					->findOneBy(array(
							'id' => $gig_id,
							'user_id' => $user_id
					));
		}else {
			$gig = $em->find('Gig\Entity\Gig', $gig_id);
		}
		return $gig;
	}
	
	public function update($gig) {
		$em = $this->getEntityManager();
		$em->flush();
		
		return $gig->getId();
	}
	
	public function delete($gig_id, $user_id) {
		$em = $this->getEntityManager();
		$gig = $this->getGig($gig_id, $user_id);
		$em->remove($gig);
		$em->flush();
	}
	
	public function deleteImage($gig_id, $image_id, $user_id) {
		$em = $this->getEntityManager();
		$gig = $this->getGig($gig_id, $user_id);
		$image = $em->find('Application\Entity\Image', $image_id);
		$gig->getImages()->removeElement($image);
		$em->flush();
		
	}
	
	public function getGigs($count=20, $page=1) {
		$em = $this->getEntityManager();
		$dql = "SELECT gig FROM \\Gig\\Entity\\Gig gig WHERE gig.status = 1 ORDER BY gig.create_at DESC";
		
		$first = $count*($page-1);
		$max = $count*$page;
		$query = $em->createQuery($dql)
					->setFirstResult($first)
					->setMaxResults($max);
		
		$paginator = new Paginator($query, $fetchJoinCollection = true);
		
		$gigs = array();
		foreach ($paginator as $gig) {
			$gigs[] = $this->toArray($gig);
		}
		
		return $gigs;
		
	}
	
	public function getMyGigs($user_id, $count=20, $page=1) {
		$em = $this->getEntityManager();
		$dql = "SELECT gig FROM \Gig\Entity\Gig gig WHERE gig.user_id = '$user_id' ORDER BY gig.create_at DESC";
		
		$first = $count*($page-1);
		$max = $count*$page;
		$query = $em->createQuery($dql)
					->setFirstResult($first)
					->setMaxResults($max);
		
		$paginator = new Paginator($query, $fetchJoinCollection = true);

		$gigs = array();
		foreach ($paginator as $gig) {
			$gigs[] = $this->toArray($gig);
		}
		
		return $gigs;
	}
	
	public function getFavorites($user_id, $count=20, $page=1) {
		$em = $this->getEntityManager();
		$dql = $em->createQuery("SELECT gig FROM \Gig\Entity\Gig gig WHERE :user_id MEMBER OF gig.favorited_users ORDER BY gig.create_at DESC");
		$dql->setParameter('user_id', $user_id);
		
		return $this->toPage($dql, $count, $page);
	}
	
	public function toPage($dql, $count=20, $page=1) {
		$first = $count*($page-1);
		$max = $count*$page;
		$query = $dql
			->setFirstResult($first)
			->setMaxResults($max);
		
		$paginator = new Paginator($query, $fetchJoinCollection = true);
		
		$gigs = array();
		foreach ($paginator as $gig) {
			$gigs[] = $this->toArray($gig);
		}
		
		return $gigs;
	}
	
	private function toArray(Gig $gig) {
		$em = $this->getEntityManager();
		$entitySer = new EntitySerializer($em);
		
		if ($gig != null) {
			$extras = array();
			foreach ($gig->getExtras() as $extra) {
				$extras[] = $entitySer->toArray($extra);
			}
			
			$images = array();
			foreach ($gig->getImages() as $image) {
				$image = $entitySer->toArray($image);
				$image['path'] = BASEURL.$image['path'];
				$image['path_thumb'] = BASEURL.$image['path_thumb'];
				$images[] = $image;
			}
			
			$tags = array();
			foreach ($gig->getTags() as $tag) {
				$tags[] = $entitySer->toArray($tag);
			}
			
			$user = $gig->getUser();
			if ($user != null) {
				$user = $entitySer->toArray($user);
			}
			
			$favorited_count = $gig->getFavorited_users()->count();
			$order_count = $gig->getOrders()->count();
			
			$gig = $entitySer->toArray($gig);
			$gig['user'] = $user;
			$gig['images'] = $images;
			$gig['extras'] = $extras;
			$gig['tags'] = $tags;
			$gig['favorited_count'] = $favorited_count;
			$gig['order_count'] = $order_count;
		}
		
		return $gig;
	}
	
	
	public function activate($gig_id, $user_id) {
		$em = $this->getEntityManager();
		$gig = $em->getRepository('Gig\Entity\Gig')
					->findOneBy(array(
							'id' => $gig_id,
							'user_id' => $user_id
							));
		if ($gig != null) {
			$status = ($gig->getStatus() == 0)? 1: 0;
			$gig->setStatus($status);
			$em->flush();
		}

		return true;	
	}
	
	public function favorited_count($gig_id, $user_id) {
		$em = $this->getEntityManager();
		$gig = $em->getRepository('Gig\Entity\Gig')
					->findOneBy(array(
							'id' => $gig_id,
							'user_id' => $user_id
					));
		$count = 0;
		if ($gig != null) {
			$count = $gig->getFavorited_users()->count();
		}
		return $count;
	}
	
	public function favorite($gig, $user) {
		$em = $this->getEntityManager();
		
		$result = $this->favorited($gig, $user);
		if ($result) {
			$gig->getFavorited_users()->removeElement($user);
		}else {
			$gig->getFavorited_users()->add($user);
		}
		
		$em->flush();
		return true;
	}
	
	public function favorited($gig, $user) {
		$favoriteUsers = $gig->getFavorited_users();
		return $favoriteUsers->contains($user);
	}
}

?>