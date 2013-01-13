<?php
/**
 * 评论前台控制器
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: Comment.php 33 2012-06-29 07:32:34Z Jie.Liu $
 */
class Controller_Comment extends Controller{

	public function action_create(){

		if (HTTP_Request::POST == $this->request->method()){

			try {

				if($user = Auth::instance()->get_user()){ //如果评论时用户已经登录
					$user_info = array(
						'author_id' => $user->pk(),
						'author' => $user->realname ? $user->realname : $user->username,
					);
				} else {
					$user_info = array(
						'author_id' => 0,
						'author' => '游客',
					);
				}

				ORM::factory('comment')->values($this->request->post()+$user_info)->save();

				$this->request->redirect($this->request->referrer());

			} catch (ORM_Validation_Exception $e) {

				$message = $e->errors('validate');
                $this->showmessage(current($message), 'error');
			}

		} else {
			$this->content = View::factory('comment/create')->set($this->request->query());
		}
	}

	/**
	 * ajax获取评论
	 * @version 2011-11-17 上午11:39:06 Jie.Liu
	 */
	public function action_list(){

		$id = intval($this->request->param('id'));
		$model = $this->request->param('model');
		$comment = ORM::factory('comment');
		if ($id AND !empty($model)){

			$comment->where('targettype', '=', $model)->where('targetid', '=', $id)
                    ->where('status', '=', Model_Comment::audited);

		}

		$pagination = new Pagination(array(
			'total_items'=>$comment->reset(FALSE)->count_all(),
		));

		// 获取当前登录用户，如果存在的话
		if($user = Auth::instance()->get_user()){
			$user_id = $user->pk();
		} else {
			$user_id = 0;
		}

		$this->content = View::factory('comment/list')->set(array(
			'pagination' => $pagination,
			'user_id' => $user_id,
			'list' => $comment->limit($pagination->items_per_page)->offset($pagination->offset)->find_all(),
		));
	}

	public function action_delete(){
		$id = intval($this->request->param('id'));
		$user = Auth::instance()->get_user();
		if($user){
			$comment = new Model_Comment(array('id' => $id, 'author_id' => $user->pk()));
			if($comment->loaded()){
				$comment->delete();
				$this->request->redirect($this->request->referrer());
			}
		} else {
			$this->request->redirect('/login');
		}
	}

	public function after(){
		$this->response->body($this->content);
	}
}