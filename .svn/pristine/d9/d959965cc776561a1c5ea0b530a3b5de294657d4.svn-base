<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseHandleViewModel extends ViewModel {
	public $viewFields = array(
		'CaseInfo' => array(
			'id',
			'code',
			'num',
			'cate',
			'accident_name',
			'accident_time',
			'accident_place',
			'lng',
			'lat',
			'death_num',
			'hurt_num',
			'property_loss',
			'accident_type',
			'first_cognizance',
			'department_id',
			'accept_status',
			'survey_status',
			'client_status',
			'record_status',
			'checkup_status',
			'law_doc_status',
			'cognizance_status',
			'punish_status',
			'mediate_status',
			'catalog_status',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_table' => '__CASE__',
			'_type' => 'LEFT',
		),
		'CaseHandle' => array(
			'id' => 'case_handle_id',
			'case_id' => 'case_handle_case_id',
			'user_id' => 'case_handle_user_id',
			'start_time' => 'case_handle_start_time',
			'end_time' => 'case_handle_end_time',
			'is_now' => 'case_handle_is_now',
			'is_del' => 'case_handle_is_del',
			'_on' => 'CaseHandle.case_id=CaseInfo.id',
		),
	);
}
?>