<?php

namespace OAuth\Model;

use OAuth2\Storage\ClientInterface;
use OAuth\Lib\EntitySerializer;

class ClientModel extends AbstractModel implements ClientInterface {
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\ClientInterface::getClient()
	 */
	public function getClient($clientId = null, $clientSecret = null, $redirectUri = null) {
		// TODO Auto-generated method stub
		$condition = array();
		if ( $clientId != null ) {
			$condition['client_id'] = $clientId; 
		}
		
		if ( $clientSecret != null ) {
			$condition['secret'] = $clientSecret;
		}
		
		if ( $redirectUri != null ) {
			$condition['redirect_url'] = $redirectUri;
		}
		
		$em = $this->getEntityManager();
		$oauthClient = $em->getRepository('OAuth\Entity\OAuthClient')
				->findOneBy( $condition );
		
		if ($oauthClient != null) {
			$entitySer = new EntitySerializer($em);
			$oauthClient = $entitySer->toArray($oauthClient);
		}
		
		return $oauthClient;
	}
}

?>