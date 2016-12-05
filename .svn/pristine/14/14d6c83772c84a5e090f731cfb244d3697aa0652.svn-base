<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseBloodtubeCateViewModel extends ViewModel {
	public $viewFields = array(
		'BloodtubeCate' => array(
			'id',
			'case_id',
			'case_client_id',
			'used_time',
			'used_user_id',
			'is_used',
			'to_department_time',
			'to_department_user_id',
			'target_department_id',
			'is_to_department',
			'to_user_time',
			'to_user_user_id',
			'target_user_id',
			'is_to_user',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'Bloodtube' => array(
			'id' => 'bloodtube_id',
			'bloodtube_cate_id',
			'code' => 'bloodtube_code',
			'recover_type' => 'bloodtube_recover_type',
			'recover_time' => 'bloodtube_recover_time',
			'recover_user_id' => 'bloodtube_recover_user_id',
			'is_recover' => 'bloodtube_is_recover',
			'create_time' => 'bloodtube_create_time',
			'create_user_id' => 'bloodtube_create_user_id',
			'update_time' => 'bloodtube_update_time',
			'update_user_id' => 'bloodtube_update_user_id',
			'is_del' => 'bloodtube_is_del',
			'_on' => 'Bloodtube.bloodtube_cate_id=BloodtubeCate.id',
			'_type' => 'LEFT',
		),
		'CaseClient' => array(
			'name' => 'case_client_name',
			'_on' => 'BloodtubeCate.case_client_id=CaseClient.id and CaseClient.is_del=0',
			'_type' => 'LEFT',
		),
		'Department' => array( // 所属大队
			'name' => 'department_name',
			'_on' => 'BloodtubeCate.target_department_id=Department.id and Department.is_del=0',
			'_type' => 'LEFT',
		),
		'Handle' => array( // 使用采血管的办案人
			'true_name' => 'handle_true_name',
			'_table' => '__USER__',
			'_on' => 'Handle.id=BloodtubeCate.used_user_id and Handle.is_del=0',
			'_type' => 'LEFT',
		),
		'CaseInfo' => array(
			'accident_time' => 'case_accident_time',
			'accident_place' => 'case_accident_place',
			'_table' => '__CASE__',
			'_on' => 'CaseInfo.id=BloodtubeCate.case_id',
		),

	);
}
?>