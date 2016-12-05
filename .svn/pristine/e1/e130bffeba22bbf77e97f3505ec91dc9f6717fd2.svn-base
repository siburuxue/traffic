<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseReplyViewModel extends ViewModel {
	public $viewFields = array(
		'CaseReply' => array(
			'id',
			'case_id',
			'reply_user_id',
			'reply_time',
			'content',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'Replyer' => array(
			'true_name' => 'replyer_user_name',
			'_table' => '__USER__',
			'_on' => 'Replyer.id=CaseReply.reply_user_id',
		),

	);
}
?>