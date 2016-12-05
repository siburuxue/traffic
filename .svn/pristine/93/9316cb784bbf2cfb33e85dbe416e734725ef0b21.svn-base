<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseDiscussMemberViewModel extends ViewModel {
	public $viewFields = array(
		'CaseDiscussMember' => array(
			'id',
			'case_id',
			'case_discuss_id',
			'member_user_id',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),

		'Member' => array(
			'true_name' => 'member_user_name',
			'_table' => '__USER__',
			'_on' => 'Member.id=CaseDiscussMember.member_user_id',
		),
	);
}
?>