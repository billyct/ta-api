<?php

namespace Gig\Controller;

use Zend\EventManager\EventManagerInterface;

use OAuth\Lib\ResultStatus;

use Zend\View\Model\JsonModel;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractServerController extends AbstractActionController {
	
	protected $server;
	protected $resultStatus;
	
	public function setEventManager(EventManagerInterface $events) {
		parent::setEventManager($events);
		$this->init();
	}
	
	public function init() {
		$request = new \OAuth2\Util\Request();
		$sessionModel = $this->getServiceLocator()->get('OAuth\Model\SessionModel');
		$this->server = new \OAuth2\ResourceServer($sessionModel);
		$resultStatus = $this->resultStatus;
		try {
			$this->server->isValid();
		} catch (\OAuth2\Exception\InvalidAccessTokenException $e) {
			$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
			return new JsonModel($resultStatus->getCM());
		}
	}
	
	public function __construct() {
		$this->resultStatus = new ResultStatus();
	}
}

?>