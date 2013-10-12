<?php

namespace Application\Controller;

use Application\Exceptions\FileException;

use Application\Entity\Image;

use Zend\View\Model\JsonModel;

use Application\Entity\Comment;

use Application\Controller\AbstractServerController;

class CommentController extends AbstractServerController {
	
	public function createAction() {
		$request = $this->getRequest();
		$resultStatus = $this->resultStatus;
		if ($this->server->getOwnerType() == 'user' && $request->isPost()) {
			/*得到当前用户信息  */
			$userModel = $this->getServiceLocator()->get('UserModel');
			$uid = $this->server->getOwnerId();
			$user = $userModel->getUserObjectById($uid);
			
			$content = $request->getPost('content');
			$image = $request->getPost('image');
			$timeline_id = $request->getPost('timeline_id');
			
			
			$timelineModel = $this->getServiceLocator()->get('Application\Model\TimeLineModel');
			$timeline = $timelineModel->getTimeline($timeline_id);
			
			$comment = new Comment();
			$comment->setContent($content)
				->setTimeline($timeline)
				->setUser($user);
			
			if ($image != null) {
				/*获取到所有将要添加的缓存图片  */
				$imageTempModel = $this->getServiceLocator()->get('Application\Model\ImageTempModel');
				$imageTemp = $imageTempModel->getObjectById($image);
				
				try {
					$imageModel = $this->getServiceLocator()->get('Application\Model\ImageModel');
					$imageThumb = $this->getServiceLocator()->get('ImageThumb');
					$image_path = $imageThumb->moveOne($imageTemp);
					/*删除缓存图片  */
					$imageTempModel->delete($image, $user);
					
					$image = new Image();
					$image->setPath($image_path['path'])
						->setPath_thumb($image_path['path_thumb'])
						->setUser($user);
					
					$image = $imageModel->insert($image);
					
					$comment->setImage($image);
					
				} catch (FileException $e) {
					$resultStatus->setCM($resultStatus::FAILED, $e->getMessage());
				}
			}
			
			$commentModel = $this->getServiceLocator()->get('Application\Model\CommentModel');
			$comment = $commentModel->create($comment);
			$resultStatus->setCMD($resultStatus::SUCCESS, '发布成功', array('comment' => $comment));
		}
		
		return new JsonModel($resultStatus->getCMD());
	}
}

?>