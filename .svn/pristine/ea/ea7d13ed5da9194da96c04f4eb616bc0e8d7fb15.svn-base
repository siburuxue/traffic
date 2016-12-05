<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCheckCheckupViewModel extends ViewModel {
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
		'UserPost' => array(
			'post_id',
			'_on' => 'User.id=UserPost.user_id',
			'_type' => 'LEFT',
		),
		'Department' => array(
			'name' => 'department_name',
			'cate' => 'department_cate',
			'_on' => 'User.department_id=Department.id',
			'_type' => 'LEFT',
		),
		'TrafficLevel' => array(
			'_table' => '__DICT_OPTION__',
			'name' => 'traffic_level_name',
			'_on' => 'User.traffic_level_id=TrafficLevel.id',
		),
	);
}
?>