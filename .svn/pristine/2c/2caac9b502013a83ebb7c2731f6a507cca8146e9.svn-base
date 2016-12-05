<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class DutyViewModel extends ViewModel {
	public $viewFields = array(
		'Duty' => array(
			'id',
			'duty_group_id',
			'user_id',
			'start_time',
			'end_time',
			'department_id',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'Department' => array(
			'name' => 'department_name',
			'cate' => 'department_cate',
			'is_del' => 'department_is_del',
			'_on' => 'Duty.department_id=Department.id',
			'_type' => 'LEFT',
		),
		'User' => array(
			'id' => 'user_id',
			'true_name' => 'true_name',
			'user_name' => 'user_name',
			'is_del' => 'user_is_del',
			'_on' => 'Duty.user_id=User.id',
		),
		'DutyGroup' => array(
			'id' => 'duty_group_group_id',
			'name' => 'duty_group_name',
			'group_type' => 'duty_group_group_type',
			'department_id' => 'duty_group_department_id',
			'is_del' => 'duty_group_is_del',
			'_type' => 'LEFT',
			'_on' => 'Duty.duty_group_id=DutyGroup.id',
		),

	);
}
?>