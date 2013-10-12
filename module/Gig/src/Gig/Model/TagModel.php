<?php

namespace Gig\Model;

class TagModel extends AbstractModel {
	
	
	public function add_tag($tag) {
		$em = $this->getEntityManager();
		$result = $this->find($tag->getName());
		if ($result == null) {
			$em->persist($tag);
			$em->flush();
			$result = $tag;
		}
		return $result;
	}
	
	public function find($name) {
		$em = $this->getEntityManager();
		$tag = $em->getRepository('Gig\Entity\Tag')
			->findOneBy(array('name' => $name));
		return $tag;
	}
	
	public function add_tags($tags) {
		$tagsArray = array();
		foreach ($tags as $tag) {
			$tag = $this->add_tag($tag);
			$tagsArray[] = $tag;
		}
		
		return $tagsArray;
	}
}

?>