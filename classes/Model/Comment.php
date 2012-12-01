<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * 评论Model
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: comment.php 56 2012-07-24 08:30:32Z Jie.Liu $
 */
class Model_Comment extends ORM{

	const unaudited = 0; //未审核
	const audited = 1; //已审核

	const author_min = 2;
	const author_max = 32;
	const content_min = 6;
	const content_max = 600;

	protected $_list_row = array( 'id', 'targetid', 'author', 'dateline', 'status' );

	protected $_search_row = array(	'author', 'status' );

	protected $_created_column = array(
		'column' => 'dateline',
		'format' => TRUE,
	);

	public function rules(){
		return array(
			'author' => array(
				array('min_length', array(':value', self::author_min)),
				array('max_length', array(':value', self::author_max)),
			),
			'content' => array(
				array('min_length', array(':value', self::content_min)),
				array('max_length', array(':value', self::content_max)),
			),
		);
	}

	public function filters(){
		return array(
			'author' => array(
				array('trim'),
			),
			'content' => array(
				array('trim'),
			),

			'targetid' => array(
				array('intval'),
			),
		);
	}

	/**
	 * 审核评论
	 */
	public function audited() {
		$this->status = self::audited;
		$this->save();
	}

	/**
	 * 取消审核评论
	 */
	public function unaudited() {
		$this->status = self::unaudited;
		$this->save();
	}

	/**
	 * 后台评论列表操作评论状态
	 */
	public function get_status(){
		return ($this->status == self::audited) ?
			'<a class="ajax" href="'.Route::url('admin/global', array('controller' => 'comment', 'action' => 'single', 'operation' => 'unaudited', 'id' => $this->pk())).'"><span class="label label-success"><i class="icon-eye-open icon-large"></i>'.__('audited').'</span></a>' :
			'<a class="ajax" href="'.Route::url('admin/global', array('controller' => 'comment', 'action' => 'single', 'operation' => 'audited', 'id' => $this->pk())).'"><span class="label label-warning"><i class="icon-eye-close icon-large"></i>'.__('unaudited').'</span></a>';
	}

	/**
	 * 后台评论编辑时，评论状态显示为下拉菜单
	 * @return string
	 */
	public function status_show(){
		return Form::select('status',
			array(
				''=>__('Please select'),
				self::audited => __('audited'),
				self::unaudited => __('unaudited')
			),
			$this->status,
			array('class' => 'input-small')
		);
	}

}