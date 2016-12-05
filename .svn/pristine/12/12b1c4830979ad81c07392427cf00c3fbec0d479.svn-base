<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class DutyGroupViewModel extends ViewModel {
	public $viewFields = array(
		'DutyGroup' => array(
			'id',
			'name',
			'group_type',
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
			'_on' => 'DutyGroup.department_id=Department.id',
		),
	);
}
?>