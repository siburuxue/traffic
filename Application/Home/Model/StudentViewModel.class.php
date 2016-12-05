<?php
namespace Home\Model;
use Think\Model\ViewModel;

class StudentViewModel extends ViewModel {
	public $viewFields = array(
		'Student' => array(
			'id',
			'grade',
			'classesid',
			'schoolid',
			'name',
			'sex',
			'mobile',
			'summary',
			'updatetime',
			'_type' => 'LEFT',
		),

	);
}
?>