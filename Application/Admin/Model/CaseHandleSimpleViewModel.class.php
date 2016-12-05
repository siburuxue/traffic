<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseHandleSimpleViewModel extends ViewModel {
	public $viewFields = array(
		'CaseHandle' => array(
			'id',
			'case_id',
			'user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'User' => array(
			'id' => 'user_id',
			'true_name' => 'true_name',
			'user_name' => 'user_name',
			'is_del' => 'user_is_del',
			'_on' => 'CaseHandle.user_id=User.id',
		),
	);
}
?>