<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class RoleViewModel extends ViewModel {
	public $viewFields = array(
		'Role' => array(
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
		'UserRole' => array(
			'user_id',
			'_on' => 'UserRole.role_id=Role.id',
		),
	);
}
?>