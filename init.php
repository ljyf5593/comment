<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 初始化文件 包含路由信息
 *
 * @author  Jie.Liu (ljyf5593@gmail.com)
 * @Id $Id: init.php 52 2012-07-18 02:09:12Z Jie.Liu $
 */
Route::set('comment/list', 'comment/list(/<target>(/<id>))')
	->defaults(array(
	'controller' => 'comment',
	'action' => 'list',
));
Route::set('comment', 'comment(/<action>(/<id>))')
    ->defaults(array(
        'controller' => 'comment',
    ));