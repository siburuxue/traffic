<?php
namespace Admin\Model;

use Think\Model\ViewModel;

class CheckupOrgDepartmentViewModel extends ViewModel
{
	public $viewFields = array(
		'CheckupOrgDepartment' => array(
			'id',
			'checkup_org_id',
			'department_id',
			'_type' => 'LEFT',
		),
		'CheckupOrg' => array(
			'id' => 'checkuporg_id',
			'name' => 'checkuporg_name',
			'status' => 'checkuporg_status',
			'is_end' => 'checkuporg_is_end',
			'create_time' => 'create_time',
			'is_del' => 'checkuporg_is_del',
			'_on' => 'CheckupOrg.id=CheckupOrgDepartment.checkup_org_id',
		),
	);
}

?>