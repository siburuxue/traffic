<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class PowerViewModel extends ViewModel {
	public $viewFields = array(
		'Power' => array(
			'id',
			'name',
			'title',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'RolePower' => array(
			'role_id',
			'_on' => 'Power.id=RolePower.power_id',
			'_type' => 'LEFT',
		),
		'Role' => array(
			'name' => 'role_name',
			'_on' => 'Role.id=RolePower.role_id and Role.is_del=0',
			'_type' => 'LEFT',
		),
		'UserRole' => array(
			'user_id',
			'_on' => 'UserRole.role_id=Role.id',
		),
	);
}
?>