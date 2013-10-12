<?php

namespace Application\Controller;

use Application\Exceptions\TimelineException;

use Application\Entity\TimeLine;

use Application\Entity\Image;

use Application\Exceptions\FileException;

use Zend\View\Model\JsonModel;

class TimeLineController extends AbstractServerController {
	
	public function paginatorAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			//获取用户id
			$uid = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($uid);
			
			$count = $request->getQuery('count');
			$page = $request->getQuery('page');
			
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timelinesArray = $timelineModel->getTimelines($page, $count);
			foreach ($timelinesArray as $key => $timelineArray) {
				$timeline = $timelineModel->getTimeline($timelineArray['id']);
				$favorited = $timelineModel->favorited($timeline, $user);
				$timelinesArray[$key]['favorited'] = ($favorited) ? true : false;
				$joined = $timelineModel->joined($timeline, $user);
				$timelinesArray[$key]['joined'] = ($joined) ? true : false;
			}
			
			return new JsonModel($timelinesArray);
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function showAction() {
		
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType()) {
			//获取用户id
			$uid = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($uid);
			
			$id = $request->getQuery('timeline_id');
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timelineArray = $timelineModel->getTimelineArray($id);
			$timeline = $timelineModel->getTimeline($id);
			
			$favorited = $timelineModel->favorited($timeline, $user);
			$timelineArray['favorited'] = ($favorited) ? true : false;
			$joined = $timelineModel->joined($timeline, $user);
			$timelineArray['joined'] = ($joined) ? true : false;
			
			return new JsonModel($timelineArray);
		}
		
		return new JsonModel($resultStatus->getCM());
		
	}
	
	/**
	 * 发布
	 * @return \Zend\View\Model\JsonModel  */
	public function publishAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			/*得到当前用户信息  */
			$userModel = $this->getServiceLocator()->get('UserModel');
			$uid = $this->server->getOwnerId();
			$user = $userModel->getUserObjectById($uid);
			
			$content = $request->getPost('content');
			$deadline = $request->getPost('deadline');
			$images = $request->getPost('images');
			
			$timeline = new TimeLine();
			$timeline->setContent($content)
				->setDeadline($deadline)
				->setUser($user);
			
			if (!empty($images)) {
				/*获取到所有将要添加的缓存图片  */
				$imageTempModel = $this->getServiceLocator()->get('Application\Model\ImageTempModel');
				$imageTemps = $imageTempModel->getObjectByIds($images);

				try {
					$imageModel = $this->getServiceLocator()->get('Application\Model\ImageModel');
					/*获取到真正存放图片的位置，并且存放图片  */
					$imageThumb = $this->getServiceLocator()->get('ImageThumb');
					$image_paths = $imageThumb->move($imageTemps);
					$imageTempModel->deleteByIds($images, $user);
					
					$imagearray = array();
					foreach ($image_paths as $image_path) {
						$image = new Image();
						$image->setPath($image_path['path'])
							->setPath_thumb($image_path['path_thumb'])
							->setUser($user);
						array_push($imagearray, $image);
					}
					
					$imagesInserted = $imageModel->insertImages($imagearray);
					
					foreach ($imagesInserted as $imageInserted) {
						$timeline->getImages()->add($imageInserted);
					}
				} catch (FileException $e) {
					$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
				}
			}
			
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timeline_id = $timelineModel->publish($timeline);
			
			$resultStatus->setCMD($resultStatus::SUCCESS, '发布成功', array('id' => $timeline_id));

		}
		
		
		return new JsonModel($resultStatus->getCMD());
	}
	
	
	
	/**
	 * 收藏
	 * @return \Zend\View\Model\JsonModel  */
	public function favoriteAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			//获取用户id
			$uid = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($uid);
			//获取post的timeline_id
			$timeline_id = $request->getPost('timeline_id');
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timeline = $timelineModel->getTimeline($timeline_id);
			try {
				$result = $timelineModel->favorite($timeline, $user);
				if ($result) {
					$resultStatus->setCM($resultStatus::SUCCESS, '收藏操作成功');
				}
			} catch (TimelineException $e) {
				$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
			}
				
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function favoritedAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			//获取用户id
			$uid = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($uid);
			//获取post的timeline_id
			$timeline_id = $request->getQuery('timeline_id');
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timeline = $timelineModel->getTimeline($timeline_id);
			
			try {
				$result = $timelineModel->favorited($timeline, $user);
				if ($result) {
					$resultStatus->setCM($resultStatus::SUCCESS, '操作成功');
				}else {
					$resultStatus->setCM($resultStatus::FAILED, '操作成功');
				}
			} catch (TimelineException $e) {
				$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
			}
			
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	
	/**
	 * 参加一个timeline
	 * @return \Zend\View\Model\JsonModel  */
	public function joinAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			//获取用户
			$uid = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($uid);
			//获取post的timeline
			$timeline_id = $request->getPost('timeline_id');
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timeline = $timelineModel->getTimeline($timeline_id);
			try {
				$result = $timelineModel->join($timeline, $user);
				if ($result) {
					$resultStatus->setCM($resultStatus::SUCCESS, '参加操作成功');
				}
			} catch (TimelineException $e) {
				$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
			}
		}
		return new JsonModel($resultStatus->getCM());
	}
	
	
	public function joinedAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			//获取用户id
			$uid = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($uid);
			//获取post的timeline_id
			$timeline_id = $request->getQuery('timeline_id');
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timeline = $timelineModel->getTimeline($timeline_id);
				
			try {
				$result = $timelineModel->joined($timeline, $user);
				if ($result) {
					$resultStatus->setCM($resultStatus::SUCCESS, '操作成功');
				}else {
					$resultStatus->setCM($resultStatus::FAILED, '操作成功');
				}
			} catch (TimelineException $e) {
				$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
			}
				
		}
	
		return new JsonModel($resultStatus->getCM());
	}
	
	
	public function favoritesAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$user_id = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($user_id);
			
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timelines = $timelineModel->getFavorites($user_id);
			foreach ($timelines as $key => $timelineArray) {
				$timeline = $timelineModel->getTimeline($timelineArray['id']);
				$favorited = $timelineModel->favorited($timeline, $user);
				$timelines[$key]['favorited'] = ($favorited) ? true : false;
				$joined = $timelineModel->joined($timeline, $user);
				$timelines[$key]['joined'] = ($joined) ? true : false;
			}
			return new JsonModel($timelines);
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function meAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			//获取用户id
			$user_id = $this->server->getOwnerId();
			
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timelines = $timelineModel->getByUser($user_id);
			
			return new JsonModel($timelines);
		}
		
		return new JsonModel($resultStatus->getCM());
			
	}
	
	public function userAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			//获取用户id
			
			$user_id = $request->getQuery('user_id');
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($user_id);
			
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timelines = $timelineModel->getByUser($user_id);
			
			foreach ($timelines as $key => $timelineArray) {
				$timeline = $timelineModel->getTimeline($timelineArray['id']);
				$favorited = $timelineModel->favorited($timeline, $user);
				$timelines[$key]['favorited'] = ($favorited) ? true : false;
				$joined = $timelineModel->joined($timeline, $user);
				$timelines[$key]['joined'] = ($joined) ? true : false;
			}
				
			return new JsonModel($timelines);
		}
		
		return new JsonModel($resultStatus->getCM());
	}

}

?>