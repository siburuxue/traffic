<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCognizanceBackViewModel extends ViewModel {
	public $viewFields = array(
		'CaseCognizance' => array(
			'id',
			'case_id',
			'cognizance_type',
			'is_submit',
			'submit_time',
			'submit_user_id',
			'check_status',
			'is_stop',
			'is_printed',
			'is_make',
			'is_back',
			'back_time',
			'back_user_id',
			'back_reason',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'BackUser' => array(
			'true_name' => 'back_user_name',
			'_table' => '__USER__',
			'_type' => 'LEFT',
			'_on' => 'BackUser.id = CaseCognizance.back_user_id',
		),
		'SubmitUser' => array(
			'true_name' => 'submit_user_name',
			'_table' => '__USER__',
			'_on' => 'SubmitUser.id = CaseCognizance.submit_user_id',
		),
	);
}
?>