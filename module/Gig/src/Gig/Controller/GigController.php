<?php

namespace Gig\Controller;

use Application\Exceptions\FileException;

use Application\Entity\Image;

use Gig\Entity\Gig;

use Gig\Entity\Tag;

use Zend\View\Model\JsonModel;

class GigController extends AbstractServerController{
	
	public function indexAction() {
		echo "gig";
		return false;
	}
	
	public function deleteimageAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$uid = $this->server->getOwnerId();
			$image_id = $request->getPost('image_id');
			$gig_id = $request->getPost('gig_id');
			$gigModel = $this->getServiceLocator()->get('GigModel');
			$gigModel->deleteImage($gig_id, $image_id, $uid);
			$resultStatus->setCM($resultStatus::SUCCESS, '操作成功');
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function paginatorAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
// 			//获取用户id
 			$uid = $this->server->getOwnerId();
 			$userModel = $this->getServiceLocator()->get('UserModel');
 			$user = $userModel->getUserObjectById($uid);
				
			$count = $request->getQuery('count');
			$page = $request->getQuery('page');
			
			$gigModel = $this->getServiceLocator()->get('GigModel');
			$gigs = $gigModel->getGigs($count, $page);
			
			foreach ($gigs as $key => $gig) {
				$gig = $gigModel->getGig($gig['id']);
				$favorited = $gigModel->favorited($gig, $user);
				$gigs[$key]['favorited'] = ($favorited) ? true : false;
			}
			
			return new JsonModel($gigs);
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function showAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			$uid = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($uid);
			
			$gig_id = $request->getQuery('gig_id');
			$gigModel = $this->getServiceLocator()->get('GigModel');
			$result = $gigModel->getGigArray($gig_id);
			
			
			$gig = $gigModel->getGig($gig_id);
			$favorited = $gigModel->favorited($gig, $user);
			$result['favorited'] = ($favorited) ? true : false;
			
			if ($result != null) {
				return new JsonModel($result);
			}
		}
		return new JsonModel($resultStatus->getCM()); 
	}
	
	public function activateAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$uid = $this->server->getOwnerId();
			$gig_id = $request->getPost('gig_id');
			
			$gigModel = $this->getServiceLocator()->get('GigModel');
			$result = $gigModel->activate($gig_id, $uid);
			if ($result) {
				$resultStatus->setCM($resultStatus::SUCCESS, '操作成功');
			}
			
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function meAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			//获取用户id
			$uid = $this->server->getOwnerId();
// 			$userModel = $this->getServiceLocator()->get('UserModel');
// 			$user = $userModel->getUserObjectById($uid);
		
			$count = $request->getQuery('count');
			$page = $request->getQuery('page');
				
			$gigModel = $this->getServiceLocator()->get('GigModel');
			
			$gigs = $gigModel->getMyGigs($uid, $count, $page);
			
			return new JsonModel($gigs);
			
			
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
		
			$count = $request->getQuery('count');
			$page = $request->getQuery('page');
		
			$gigModel = $this->getServiceLocator()->get('GigModel');
				
			$gigs = $gigModel->getMyGigs($user_id, $count, $page);
			foreach ($gigs as $key => $gig) {
				$gig = $gigModel->getGig($gig['id']);
				$favorited = $gigModel->favorited($gig, $user);
				$gigs[$key]['favorited'] = ($favorited) ? true : false;
			}
				
			return new JsonModel($gigs);
				
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function favoritesAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user') {
			//获取用户id
			$user_id = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($user_id);
			$count = $request->getQuery('count');
			$page = $request->getQuery('page');
			$gigModel = $this->getServiceLocator()->get('GigModel');
		
			$gigs = $gigModel->getFavorites($user_id, $count, $page);
			foreach ($gigs as $key => $gig) {
				$gig = $gigModel->getGig($gig['id']);
				$favorited = $gigModel->favorited($gig, $user);
				$gigs[$key]['favorited'] = ($favorited) ? true : false;
			}
		
			return new JsonModel($gigs);
		
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function favoriteAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$user_id = $this->server->getOwnerId();
			$userModel = $this->getServiceLocator()->get('UserModel');
			$user = $userModel->getUserObjectById($user_id);
			
			$gig_id = $request->getPost('gig_id');
			$gigModel = $this->getServiceLocator()->get('GigModel');
			$gig = $gigModel->getGig($gig_id);
				
			$result = $gigModel->favorite($gig, $user);
			if ($result) {
				$resultStatus->setCM($resultStatus::SUCCESS, '操作成功');
			}
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function deleteAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			$user_id = $this->server->getOwnerId();
			$gig_id = $request->getPost('gig_id');
			$gigModel = $this->getServiceLocator()->get('GigModel');
			
			$gigModel->delete($gig_id, $user_id);
			$resultStatus->setCM($resultStatus::SUCCESS, '操作成功');
		}
		
		return new JsonModel($resultStatus->getCM());
	}
	
	public function saveAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			/*得到当前用户信息  */
			$userModel = $this->getServiceLocator()->get('UserModel');
			$uid = $this->server->getOwnerId();
			$user = $userModel->getUserObjectById($uid);
			
			$title = $request->getPost('title');
			$price = $request->getPost('price');
			$description = $request->getPost('description');
			$instructions = $request->getPost('instructions');
			$day_to_complete = $request->getPost('day_to_complete');
			$gig_id = $request->getPost('gig_id');
			
			$price = ($price == null) ? 15 : $price;
			$day_to_complete = ($day_to_complete == null) ? 0 : $day_to_complete;
			
			$gig_id = $request->getPost('gig_id');			
			$gigModel = $this->getServiceLocator()->get('GigModel');
			$gig = new Gig();
			//判断是创建还是更新
			if ($gig_id != null) {
				$gig = $gigModel->getGig($gig_id, $uid);
			}
			
			$gig->setTitle($title)
				->setDescription($description)
				->setPrice($price)
				->setInstructions($instructions)
				->setDay_to_complete($day_to_complete)
				->setUser($user);
			
			$tags = $request->getPost('tags');
			$images = $request->getPost('images');
			/*获取到tags的数组  */
			$tags = str_replace('，', ',', $tags);
			$tags = explode(',', $tags, -1);
			$tagModel = $this->getServiceLocator()->get('TagModel');
			/*插入到数据库  */
			foreach ($tags as $tag) {
				if ($tag != '') {
					$tag_entity = new Tag();
					$tag_entity->setName($tag);
					$tag = $tagModel->add_tag($tag_entity);
					if (!$gig->getTags()->contains($tag)){
						$gig->getTags()->add($tag);
					}
				}
			}
			
			if (!empty($images)) {
				/*获取到所有将要添加的缓存图片  */
				$imageTempModel = $this->getServiceLocator()->get('Application\Model\ImageTempModel');
				$imageTemps = $imageTempModel->getObjectByIds($images);
			
				try {
					$imageModel = $this->getServiceLocator()->get('Application\Model\ImageModel');
					/*获取到真正存放图片的位置，并且存放图片  */
					$imageThumb = $this->getServiceLocator()->get('ImageThumb');
					$imageThumb->setThumb_width(150);
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
						$gig->getImages()->add($imageInserted);
					}
				} catch (FileException $e) {
					$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
				}
			}
			
			if ($gig_id != null){
				$result = $gigModel->update($gig);
			}else {
				$result = $gigModel->create($gig);
			}
			$resultStatus->setCMD($resultStatus::SUCCESS, '操作成功', array('id' => $result));
			
			
			
			
		}
		
		return new JsonModel($resultStatus->getCM());
	}
}

?>