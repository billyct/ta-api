<?php

namespace Application\Controller;
use Zend\View\Model\JsonModel;

use Zend\Mvc\Controller\AbstractActionController;
class PublicController extends AbstractActionController{
	
	public function timelinesAction() {
		$request = $this->getRequest();
		$count = $request->getQuery('count');
		$page = $request->getQuery('page');
		$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
		
		$timelines = ($count != null)? $timelineModel->getTimelines($page, $count): $timelineModel->getTimelines($page);

		return new JsonModel($timelines);
		
	}
	
	public function timelineAction() {
		$request = $this->getRequest();
		$timeline_id = $request->getQuery('timeline_id');
		$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
		$timeline = $timelineModel->getTimelineArray($timeline_id);
		
		return new JsonModel($timeline);
		
	}
	
	public function commentsAction() {
		$request = $this->getRequest();
		$timeline_id = $request->getQuery('timeline_id');
		$commentModel = $this->getServiceLocator()->get('Application\Model\CommentModel');
		$comments = $commentModel->getComments($timeline_id);
		return new JsonModel($comments);
	}
	
// 	public function userAction() {
// 		$request = $this->getRequest();
// 		$userModel = $this->getServiceLocator()->get('UserModel');
// 		$user_id = $request->getQuery('user_id');
// 		$user = $userModel->getUserArrayById($user_id);
// 		return new JsonModel($user);
// 	}

	public function gigsAction() {
		$request = $this->getRequest();
		$count = $request->getQuery('count');
		$page = $request->getQuery('page');
			
		$gigModel = $this->getServiceLocator()->get('GigModel');
		$gigs = $gigModel->getGigs($count, $page);
		return new JsonModel($gigs);
	}
	
	public function gigAction() {
		$request = $this->getRequest();
		$gig_id = $request->getQuery('gig_id');
		$gigModel = $this->getServiceLocator()->get('GigModel');
		$gig = $gigModel->getGigArray($gig_id);
		return new JsonModel($gig);
	}
	
	public function imagesAction() {
		
	}
	
	public function imageAction() {
		
	}
}

?>