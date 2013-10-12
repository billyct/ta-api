<?php

namespace OAuth\Model;

use OAuth2\Storage\ScopeInterface;
use OAuth\Lib\EntitySerializer;

class ScopeModel extends AbstractModel implements ScopeInterface {
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\ScopeInterface::getScope()
	 */
	public function getScope($scope) {
		// TODO Auto-generated method stub
		$em = $this->getEntityManager();
		$oauthScope = $em->getRepository('OAuth\Entity\OAuthScope')
					->findOneBy(
							array(
									'scope' => $scope,
							)
					);
		
		if ( $oauthScope != null ) {
			$entitySer = new EntitySerializer($em);
			$oauthScope = $entitySer->toArray($oauthScope);
		}
		
		return $oauthScope;
	}
}

?>