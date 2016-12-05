<?php
namespace Admin\Model;

use Think\Model\ViewModel;

class CaseCheckupStatisticsModel extends ViewModel
{
	public $viewFields = array(
		'CaseCheckup' => array(
			'id',
			'case_id',
			'pid',
			'checkup_org_item_pid',
			'checkup_org_item_id',
			'target_case_client_id',
			'target_car_no',
			'target_other',
			'checkup_org_id',
			'finish_time',
			'is_delay',
			'is_out',
			'out_checked',
			'delay_checked',
			'is_cancel',
			'cancel_reason',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'is_over',
			'_type' => 'LEFT',
		),
		'CaseInfo' => array(
			'accident_time' => 'case_accident_time',
			'department_id' => 'case_department_id',
			'_on' => 'CaseCheckup.case_id=CaseInfo.id',
			'_table' => '__CASE__',
			'_type' => 'LEFT',
		),
		'Department' => array(
			'name' => 'department_name',
			'_on' => 'Department.id=CaseInfo.department_id',
			'_type' => 'LEFT',
		),
		'Handle' => array(
			'true_name' => 'handle_true_name',
			'_on' => 'CaseCheckup.create_user_id=Handle.id and Handle.is_del=0',
			'_table' => '__USER__',
			'_type' => 'LEFT',
		),
		'TargetCaseClient' => array(
			'name' => 'target_case_client_name',
			'car_no' => 'target_case_car_no',
			'_table' => '__CASE_CLIENT__',
			'_on' => 'TargetCaseClient.id=CaseCheckup.target_case_client_id and TargetCaseClient.is_del=0',
			'_type' => 'LEFT',
		),
		'CaseCheckupReport' => array(
			'finish_time' => 'case_checkup_report_finish_time',
			'_on' => 'CaseCheckupReport.case_checkup_id=CaseCheckup.id and CaseCheckupReport.is_del=0',
			'_type' => 'LEFT',
		),
		'CheckupOrgItem' => array(
			'name' => 'checkup_org_item_name',
			'_on' => 'CheckupOrgItem.id=CaseCheckup.checkup_org_item_id and CheckupOrgItem.is_del=0',
			'_type' => 'LEFT',
		),
		'CheckupOrg' => array(
			'name' => 'checkup_org_name',
			'_on' => 'CheckupOrg.id=CaseCheckup.checkup_org_id and CheckupOrg.is_del=0',
		),
	);

}