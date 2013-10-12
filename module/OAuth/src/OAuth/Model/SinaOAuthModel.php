<?php

namespace OAuth\Model;

use Application\Lib\EntitySerializer;

use OAuth\Entity\SinaOAuth;

class SinaOAuthModel extends AbstractModel{
	
	public function insert(SinaOAuth $sina_oauth) {
		$em = $this->getEntityManager();
		$em->persist($sina_oauth);
		$em->flush();
	}
	
	public function find($uid) {
		$em = $this->getEntityManager();
		//$sina_oauth_user = $em->find('Application\Entity\SinaOAuth', $uid);
		$sina_user = $em->getRepository('OAuth\Entity\SinaOAuth')
							->findOneBy(array('uid' => $uid));
		
		return $sina_user;
	}
	
	public function findToArray($uid) {
		$em = $this->getEntityManager();
		$sina_user = $this->find($uid);
		if ($sina_user != null) {
			$entitySer = new EntitySerializer($em);
			$sina_user = $entitySer->toArray($sina_user);
		}
		
		return $sina_user;
	}
	
	
	public function updateUser($sina_user, $user) {
		$em = $this->getEntityManager();
		$sina_user->setUser($user);
		$em->flush();
	}
	
	public function updateToken($token) {
		$sina_user = $this->find($token['uid']);
		$sina_user->setAccess_token($token['access_token'])
					->setExpires_in($token['expires_in'])
					->setRemind_in($token['remind_in']);
		$em = $this->getEntityManager();
		$em->flush();
	}
}

?>