<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCheckViewModel extends ViewModel {
	public $viewFields = array(
		'CaseCheck' => array(
			'id',
			'case_id',
			'cate',
			'item_id',
			'check_user_id',
			'check_time',
			'submit_user_id',
			'submit_time',
			'status',
			'remark',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'CheckUser' => array(
			'true_name' => 'check_user_name',
			'_table' => '__USER__',
			'_type' => 'LEFT',
			'_on' => 'CheckUser.id = CaseCheck.check_user_id',
		),
		'SubmitUser' => array(
			'true_name' => 'submit_user_name',
			'_table' => '__USER__',
			'_on' => 'SubmitUser.id = CaseCheck.submit_user_id',
		),
	);
}
?>