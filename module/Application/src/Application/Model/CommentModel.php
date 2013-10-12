<?php

namespace Application\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;

use Application\Lib\EntitySerializer;

class CommentModel extends AbstractModel{
	/**
	 * 创建一条评论
	 * @param \Application\Entity\Comment $comment
	 * @return \Application\Entity\Comment int id */
	public function create(\Application\Entity\Comment $comment) {
		$em = $this->getEntityManager();
		$em->persist($comment);
		$em->flush();
		$comment = $this->toArray($comment);
		return $comment;
	}
	
	
	public function getComments($timeline_id, $page=1, $count=20) {
		$em = $this->getEntityManager();
		
		$dql = "SELECT comment FROM \Application\Entity\Comment comment WHERE comment.timeline_id = '".$timeline_id."' ORDER BY comment.create_at DESC";
		
		$first = $count*($page-1);
		$max = $count*$page;
		$query = $em->createQuery($dql)
			->setFirstResult($first)
			->setMaxResults($max);
		
		$paginator = new Paginator($query, $fetchJoinCollection = true);
		
		$comments = array();

		foreach ($paginator as $comment) {
			$comment = $this->toArray($comment);
			$comments[] = $comment;
		}
		
		return $comments;
	}
	
	private function toArray($comment) {
		$em = $this->getEntityManager();
		$entitySer = new EntitySerializer($em);
		
		if ($comment != null) {
			
			$image = $comment->getImage();
			if ($image != null) {
				$image = $entitySer->toArray($image);
				$image['path'] = BASEURL.$image['path'];
				$image['path_thumb'] = BASEURL.$image['path_thumb'];
			}

			$user = $comment->getUser();
			if ($user != null){
				$user = $entitySer->toArray($user);
				unset($user['password']);
				unset($user['email']);
			}
			
			$comment = $entitySer->toArray($comment);
			$comment['image'] = $image;
			$comment['user'] = $user;
			
		}
		
		return $comment;
		
	}
	
}

?>