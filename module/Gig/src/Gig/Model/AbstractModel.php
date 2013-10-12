<?php

namespace Gig\Model;

use Doctrine\ORM\EntityManager;

abstract class AbstractModel {
	protected $entityManager;
	
	public function setEntityManager(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
		return $this;
	}
	
	public function getEntityManager() {
		return $this->entityManager;
	}
}

?>