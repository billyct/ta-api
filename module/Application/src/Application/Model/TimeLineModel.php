<?php

namespace Application\Model;

use Application\Exceptions\TimelineException;

use Application\Lib\EntitySerializer;

use Doctrine\ORM\Tools\Pagination\Paginator;

use Application\Entity\TimeLine;

class TimeLineModel extends AbstractModel {
	
	/**
	 * 发布一个timeline
	 * @param TimeLine $timeline  
	 * @return int timeline的id
	 **/
	public function publish(TimeLine $timeline) {
		$em = $this->getEntityManager();
		$em->persist($timeline);
		$em->flush();
		return $timeline->getId();
	}
	
	/**
	 * 获取一个timeline数组
	 * @param int $id
	 * @return Ambigous <multitype:, \Application\Model\Application\Entity\TimeLine>  */
	public function getTimelineArray($id) {
		$em = $this->getEntityManager();
		$timeline = $em->find('Application\Entity\TimeLine', $id);
		$timeline = $this->toArray($timeline);
		return $timeline;
	}
	
	/**
	 * 获取一个timeline
	 * @param int $id
	 * @return Application\Entity\TimeLine $timeline */
	public function getTimeline($id) {	
		$em = $this->getEntityManager();
		$timeline = $em->find('Application\Entity\TimeLine', $id);
		return $timeline;
		
	}
	
	
	/**
	 * 将timeline的关联数据也都变成数组
	 * @param Application\Entity\TimeLine $timeline
	 * @return array  */
	private function toArray($timeline) {
		$em = $this->getEntityManager();
		$entitySer = new EntitySerializer($em);
		if ($timeline != null) {
			$images = array();
			foreach ($timeline->getImages() as $image){
				$image = $entitySer->toArray($image);
				$image['path'] = BASEURL.$image['path'];
				$image['path_thumb'] = BASEURL.$image['path_thumb'];
				$images[] = $image;
			}
				
			$user = $timeline->getUser();
			if ($user != null) {
				$user = $entitySer->toArray($user);
				unset($user['password']);
				unset($user['email']);
			}
			
			$comments_count = $timeline->getComments()->count();
			$favorited_count = $timeline->getFavorited_users()->count();
			$joined_count = $timeline->getJoined_users()->count();
			
			$timeline = $entitySer->toArray($timeline);
			$timeline['images'] = $images;
			$timeline['user'] = $user;
			$timeline['comments_count'] = $comments_count;
			$timeline['favorited_count'] = $favorited_count;
			$timeline['joined_count'] = $joined_count;
		}
		
		return $timeline;
	}
	
	/**
	 * 获取某分页的一堆timeline
	 * @param int $page
	 * @param int $count
	 * @return multitype:Ambigous <multitype:, \Application\Model\Application\Entity\TimeLine>  */
	public function getTimeLines( $page=1, $count=20 ) {
		$em = $this->getEntityManager();
		$dql = "SELECT timeline, user FROM \Application\Entity\TimeLine timeline JOIN timeline.user user ORDER BY timeline.create_at DESC";
		
		$first = $count*($page-1);
		$max = $count*$page;
		$query = $em->createQuery($dql)
							->setFirstResult($first)
							->setMaxResults($max);
		
		$paginator = new Paginator($query, $fetchJoinCollection = true);
		
		$timelines = array();
		
		foreach ($paginator as $timeline) {	
			$timeline = $this->toArray($timeline);
			$timelines[] = $timeline;
		}
		
		return $timelines;
	}
	
	/**
	 * 判断是否已经收藏
	 * @param Application\Entity\Timeline $timeline
	 * @param User\Entity\User $user
	 * @return boolean */
	public function favorited(\Application\Entity\Timeline $timeline, \OAuth\Entity\User $user) {
		$em = $this->getEntityManager();
		$favoriteUsers = $timeline->getFavorited_users();
		return $favoriteUsers->contains($user);
	}
	
	
	/**
	 * 收藏，如果已经收藏了的，则取消收藏
	 * @param Application\Entity\Timeline $timeline
	 * @param User\Entity\User $user
	 * @return boolean */
	public function favorite(\Application\Entity\Timeline $timeline, \OAuth\Entity\User $user) {
		$em = $this->getEntityManager();
		$result = $this->favorited($timeline, $user);
		if ($result) {
			$timeline->getFavorited_users()->removeElement($user);
		} else {
			$timeline->getFavorited_users()->add($user);
		}
		
		$em->flush();
		
		return true;
		
	}
	
	
	/**
	 * 判断是否已经参加
	 * @param Application\Entity\Timeline $timeline
	 * @param User\Entity\User $user
	 * @return boolean */
	public function joined(\Application\Entity\Timeline $timeline, \OAuth\Entity\User $user) {
		$em = $this->getEntityManager();
		$joinedUsers = $timeline->getJoined_users();
		return $joinedUsers->contains($user);
	}
	/**
	 * 报名参加，如果参加过则取消参加
	 * @param Application\Entity\Timeline $timeline
	 * @param User\Entity\User $user
	 * @throws EventTimelineException
	 * @return boolean  */
	public function join(\Application\Entity\Timeline $timeline, \OAuth\Entity\User $user) {
		$em = $this->getEntityManager();
		$result = $this->joined($timeline, $user);
		
		if (time() >= $timeline->getDeadline()) {
			throw new TimelineException('该活动已经到期,不能参加或者取消参加了！');
		}
		
		if ($result) {
			$timeline->getJoined_users()->removeElement($user);
		} else {
			$timeline->getJoined_users()->add($user);
		}
	
		$em->flush();
		
		return true;
	}
	
	
	public function getByUser($user_id) {
		$em = $this->getEntityManager();
		$timelines = $em->getRepository('Application\Entity\TimeLine')
						->findBy(array('user_id' => $user_id));
		
		$timelinesArray = array();
		
		foreach ($timelines as $timeline) {
			$timeline = $this->toArray($timeline);
			$timelinesArray[] = $timeline;
		}
		
		return $timelinesArray;
		
	}
	
	public function getFavorites($user_id) {
		$em = $this->getEntityManager();
		$dql = $em->createQuery("SELECT timeline FROM \Application\Entity\TimeLine timeline WHERE :user_id MEMBER OF timeline.favorited_users ORDER BY timeline.create_at DESC");
		$dql->setParameter('user_id', $user_id);
		
		return $this->toPage($dql);
	}
	
	public function toPage($dql, $page=1, $count=20) {
		$em = $this->getEntityManager();
		$first = $count*($page-1);
		$max = $count*$page;
		$query = $dql->setFirstResult($first)
			->setMaxResults($max);
		
		$paginator = new Paginator($query, $fetchJoinCollection = true);
		
		$timelines = array();
		
		foreach ($paginator as $timeline) {
			$timeline = $this->toArray($timeline);
			$timelines[] = $timeline;
		}
		
		return $timelines;
	}
}

?>