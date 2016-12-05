<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class PostViewModel extends ViewModel {
	public $viewFields = array(
		'Post' => array(
			'id',
			'name',
			'remark',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'UserPost' => array(
			'user_id',
			'_on' => 'UserPost.post_id=Post.id',
		),
	);
}
?>