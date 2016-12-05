<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class UserPowerViewModel extends ViewModel {
	public $viewFields = array(
		'User' => array(
			'id',
			'user_name',
			'password',
			'salt',
			'true_name',
			'police_no',
			'department_id',
			'tel',
			'traffic_level_id',
			'last_login_time',
			'last_login_ip',
			'login_count',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_locked',
			'is_del',
			'_type' => 'LEFT',
		),
		'UserRole' => array(
			'role_id',
			'_on' => 'User.id=UserRole.user_id',
			'_type' => 'LEFT',
		),
		'RolePower' => array(
			'power_id',
			'_on' => 'RolePower.role_id=UserRole.role_id',
			'_type' => 'LEFT',
		),
		'Power' => array(
			'name' => 'power_name',
			'title' => 'power_title',
			'is_del' => 'power_is_del',
			'_on' => 'Power.id=RolePower.power_id and Power.is_del=0',
			'_type' => 'LEFT',
		),
		'UserPost' => array(
			'post_id',
			'_on' => 'User.id=UserPost.user_id',
			'_type' => 'LEFT',
		),
		'Post' => array(
			'name' => 'user_post_name',
			'is_del' => 'user_post_is_del',
			'_on' => 'UserPost.post_id=Post.id and Post.is_del=0',
			'_type' => 'LEFT',
		),
		'Department' => array(
			'name' => 'department_name',
			'cate' => 'department_cate',
			'is_del' => 'department_is_del',
			'_on' => 'User.department_id=Department.id and Department.is_del=0',
			'_type' => 'LEFT',
		),
	);
}
?>