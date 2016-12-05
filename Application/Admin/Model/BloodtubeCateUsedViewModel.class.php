<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class BloodtubeCateUsedViewModel extends ViewModel {
	public $viewFields = array(
		'Bloodtube' => array(
			'id' => 'bloodtube_id',
			'bloodtube_cate_id' => 'bloodtube_cate_id',
			'code' => 'bloodtube_code',
			'recover_type' => 'bloodtube_recover_type',
			'recover_time' => 'bloodtube_recover_time',
			'recover_user_id' => 'bloodtube_recover_user_id',
			'is_recover' => 'bloodtube_is_recover',
			'is_del' => 'bloodtube_is_del',
			'_type' => 'LEFT',
		),
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
			'_on' => 'BloodtubeCate.id=Bloodtube.bloodtube_cate_id',
			'_type' => 'LEFT',
		),
		// 'BloodtubeUsedLog' => array(
		// 	'id' => 'used_id',
		// 	'bloodtube_cate_id' => 'used_bloodtube_cate_id',
		// 	'user_id' => 'used_user_id',
		// 	'reason' => 'used_reason',
		// 	'status' => 'used_status',
		// 	'is_del' => 'used_is_del',
		// 	'_on' => 'BloodtubeCate.id=BloodtubeUsedLog.bloodtube_cate_id',
		// 	'_type' => 'LEFT',
		// ),

	);
}
?>