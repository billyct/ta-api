<?php

namespace OAuth\Model;

use OAuth\Lib\EntitySerializer;
use OAuth\Entity\OAuthSession;
use OAuth2\Storage\SessionInterface;

class SessionModel extends AbstractModel implements SessionInterface {
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::createSession()
	 */
	public function createSession($clientId, $redirectUri, $type = 'user', $typeId = null, $authCode = null, $accessToken = null, $refreshToken = null, $accessTokenExpire = null, $stage = 'requested') {
		// TODO Auto-generated method stub
		$now = time();
		$em = $this->getEntityManager();
		$user = $em->find('OAuth\Entity\User', $typeId);
		$oauthSession = new OAuthSession();
		$oauthSession->setClient_id($clientId)
					->setRedirect_url($redirectUri)
					->setOwner_type($type)
					->setUser($user)
					->setAuth_code($authCode)
					->setAccess_token($accessToken)
					->setRefresh_token($refreshToken)
					->setAccess_token_expires($accessTokenExpire)
					->setStage($stage)
					->setFirst_requested($now)
					->setLast_update($now);
		$em->persist($oauthSession);
		$em->flush();
		return $oauthSession->getId();
	}
	
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::updateSession()
	 */
	public function updateSession($sessionId, $authCode = null, $accessToken = null, $refreshToken = null, $accessTokenExpire = null, $stage = 'requested') {
		// TODO Auto-generated method stub
		$now = time();
		$em = $this->getEntityManager();
		$oauthSession = $em->find('OAuth\Entity\OAuthSession', $sessionId);
		$oauthSession->setAuth_code($authCode)
					->setAccess_token($accessToken)
					->setRefresh_token($refreshToken)
					->setAccess_token_expires($accessTokenExpire)
					->setStage($stage)
					->setLast_update($now);
		$em->flush();
	}
	
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::deleteSession()
	 */
	public function deleteSession($clientId, $type, $typeId) {
		// TODO Auto-generated method stub
		$em = $this->getEntityManager();
		$oauthSession = $em->getRepository('OAuth\Entity\OAuthSession')
							->findOneBy(
									array(
											'client_id' => $clientId,
											'owner_type' => $type,
											'owner_id' => $typeId,
											)
									);
		if ($oauthSession != null) {
			$em->remove($oauthSession);
			$em->flush();
		}
	}
	
	public function endSession($access_token) {
		$em = $this->getEntityManager();
		$oauthSession = $em->getRepository('OAuth\Entity\OAuthSession')
							->findOneBy(
									array(
											'access_token' => $access_token,
											)
									);
		if ($oauthSession != null) {
			$em->remove($oauthSession);
			$em->flush();
		}
	}
	
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::validateAuthCode()
	 */
	public function validateAuthCode($clientId, $redirectUri, $authCode) {
		// TODO Auto-generated method stub
		
		$em = $this->getEntityManager();
		$oauthSession = $em->getRepository('OAuth\Entity\OAuthSession')
							->findOneBy(
									array(
											'client_id' => $clientId,
											'redirect_url' => $redirectUri,
											'auth_code' => $authCode,
									)
							);
		
		if ($oauthSession != null) {
			$entitySer = new EntitySerializer($em);
			$oauthSession = $entitySer->toArray($oauthSession);
		}
		
		return $oauthSession;
	}
	
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::validateAccessToken()
	 */
	public function validateAccessToken($accessToken) {
		// TODO Auto-generated method stub
		
		$em = $this->getEntityManager();
		$oauthSession = $em->getRepository('OAuth\Entity\OAuthSession')
							->findOneBy(
									array(
											'access_token' => $accessToken
									)
							);
		if ($oauthSession != null) {
			$entitySer = new EntitySerializer($em);
			$oauthSession = $entitySer->toArray($oauthSession);
		}
		
		return $oauthSession;
	}
	
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::getAccessToken()
	 */
	public function getAccessToken($sessionId) {
		// TODO Auto-generated method stub
	}
	
	/*
	 * (non-PHPdoc) @see
	 * \OAuth2\Storage\SessionInterface::validateRefreshToken()
	 */
	public function validateRefreshToken($refreshToken, $clientId) {
		// TODO Auto-generated method stub
	}
	
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::updateRefreshToken()
	 */
	public function updateRefreshToken($sessionId, $newAccessToken, $newRefreshToken, $accessTokenExpires) {
		// TODO Auto-generated method stub
	}
	
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::associateScope()
	 */
	public function associateScope($sessionId, $scopeId) {
		// TODO Auto-generated method stub
		$em = $this->getEntityManager();
		$oauthSession = $em->find('OAuth\Entity\OAuthSession', $sessionId);
		$oauthScope = $em->find('OAuth\Entity\OAuthScope', $scopeId);
		$oauthSession->getScopes()->add($oauthScope);
		$em->flush();
		
	}
	
	/*
	 * (non-PHPdoc) @see \OAuth2\Storage\SessionInterface::getScopes()
	 */
	public function getScopes($sessionId) {
		// TODO Auto-generated method stub
		$em = $this->getEntityManager();
		$oauthSession = $em->find('OAuth\Entity\OAuthSession', $sessionId);
		$scopes = $oauthSession->getScopes();
		
		return $scopes->toArray();
			
	}
}

?>