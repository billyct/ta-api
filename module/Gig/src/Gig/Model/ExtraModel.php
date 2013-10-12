<?php

namespace Gig\Model;

use OAuth\Lib\EntitySerializer;

use Gig\Entity\Extra;

class ExtraModel extends AbstractModel{
	
	public function create(Extra $extra) {
		$em = $this->getEntityManager();
		$entitySer = new EntitySerializer($em);
		
		$em->persist($extra);
		$em->flush();
		
		if ($extra != null) {
			$extra = $entitySer->toArray($extra);
		}
		return $extra;
	}
	
	public function getById($extra_id) {
		$em = $this->getEntityManager();
		$extra = $em->find('Gig\Entity\Extra', $extra_id);
		return $extra;
	}
	
	public function getByIds($extra_ids) {
		$extras = array();
		foreach ($extra_ids as $extra_id) {
			array_push($extras, $this->getById($extra_id));
		}
		
		return $extras;
	}
}

?>