<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseDirectiveViewModel extends ViewModel {
	public $viewFields = array(
		'CaseDirective' => array(
			'id',
			'case_id',
			'content',
			'directive_time',
			'directive_user_id',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'DirectiveUser' => array(
			'true_name' => 'directive_user_name',
			'_table' => '__USER__',
			'_on' => 'DirectiveUser.id=CaseDirective.directive_user_id',
		),

	);
}
?>