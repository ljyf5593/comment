<?php
/**
 * 评论前台控制器
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: comment.php 33 2012-06-29 07:32:34Z Jie.Liu $
 */
class Controller_Comment extends Controller{

	private $content = '';

	public function action_create(){

		if (HTTP_Request::POST == $this->request->method()){

			try {
				$comment = ORM::factory('comment')->values($this->request->post())->save();
				$json['isSucceed'] = TRUE;
				$comment->content = nl2br($comment->content);
				$json['comment'] = $comment->object();

			}catch (ORM_Validation_Exception $e) {

				$json['message'] = $e->errors('comment');

			}
			echo json_encode($json);
		} else {
			$this->content = View::factory('comment/create');
		}
	}

	/**
	 * ajax获取评论
	 * @version 2011-11-17 上午11:39:06 Jie.Liu
	 */
	public function action_list(){

		$id = intval($this->request->param('id'));
		$model = $this->request->param('model');
		if ($id AND !empty($model)){

			$json = $this->getCommentList($model, $id);
			$json['isSucceed'] = TRUE;
			echo json_encode($json);
		}

		$this->content = View::factory('comment/list');
	}

	public function after(){
		$this->response->body($this->content);
	}

	private function getCommentList($model, $id){
		$ormModel = ORM::factory($model, $id);
		$ormModel->comments->where('targettype', '=', $model)
			->where('status', '=', 1);

		$pagination = new Pagination(array(
			'total_items'=>$ormModel->comments->count_all(),
		));

		$comments = $ormModel->comments->limit($pagination->items_per_page)->offset($pagination->offset)->order_by('dateline', 'DESC')->find_all();
		$commentList = array();
		if (count($comments)){
			foreach ($comments as $comment){
				$commentList[] = $comment->object();
			}
		}
		$result = array();
		$result['pagination'] = $pagination->render();
		$result['commentList'] = $commentList;
		return $result;
	}

}