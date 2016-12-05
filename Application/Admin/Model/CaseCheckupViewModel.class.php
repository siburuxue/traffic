<?php
namespace Admin\Model;

use Think\Model\ViewModel;

class CaseCheckupViewModel extends ViewModel
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
		'CheckupOrg' => array(
			'name' => 'checkup_org_name',
			'_on' => 'CaseCheckup.checkup_org_id = CheckupOrg.id',
			'_type' => 'LEFT',
		),
		'CheckupOrgItemId' => array(
			'name' => 'checkup_org_item_id_name',
			'_table' => '__CHECKUP_ORG_ITEM__',
			'_on' => 'CaseCheckup.checkup_org_item_id=CheckupOrgItemId.id',
			'_type' => 'LEFT',
		),
		'CaseCheckupReport' => array(
			'result' => 'case_checkup_report_result',
			'_on' => 'CaseCheckupReport.case_checkup_id=CaseCheckup.id and CaseCheckupReport.is_back=0 and CaseCheckupReport.is_del=0',
		),
	);
}

?>