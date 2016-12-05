<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCheckupNoticeReportViewModel extends ViewModel {
	public $viewFields = array(
		'CaseCheckupNotice' => array(
			'id',
			'case_id',
			'case_client_id',
			'case_checkup_id',
			'case_checkup_report_id',
			'notice_department',
			'notice_time',
			'notice_place',
			'notice_person',
			'target_person',
			'content',
			'is_clear',
			'is_again',
			'create_time',
			'update_time',
			'create_user_id',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'CaseCheckupReport' => array(
			'id' => 'report_id',
			'is_back' => 'report_is_back',
			'is_del' => 'report_is_del',
			'_on' => 'CaseCheckupReport.id=CaseCheckupNotice.case_checkup_report_id',
			'_type' => 'LEFT',
		),
	);
}
?>