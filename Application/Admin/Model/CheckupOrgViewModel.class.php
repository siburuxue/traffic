<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CheckupOrgViewModel extends ViewModel {
	public $viewFields = array(
		'CheckupOrg' => array(
			'id',
			'name',
			'create_time' => 'createtime',
			'del_reason',
			'is_end',
			'_type' => 'LEFT',
		),
		'CheckupOrgItemAccess' => array(
			'_on' => 'CheckupOrgItemAccess.checkup_org_id=CheckupOrg.id',
			'_type' => 'LEFT',
		),
		'CheckupOrgItem' => array(
			'group_concat(DISTINCT CheckupOrgItem.name)' => 'checknames',
			'_type' => 'LEFT',
			'_on' => 'CheckupOrgItem.id=CheckupOrgItemAccess.checkup_org_item_id and CheckupOrgItem.is_del = 0',
		),
		'CheckupOrgDepartment' => array(
			'_on' => 'CheckupOrgDepartment.checkup_org_id=CheckupOrg.id',
			'_type' => 'LEFT',
		),
		'Department' => array(
			'group_concat(DISTINCT Department.name)' => 'departments',
			'_on' => 'Department.id=CheckupOrgDepartment.department_id and Department.is_del = 0',
		),
	);
}
?>