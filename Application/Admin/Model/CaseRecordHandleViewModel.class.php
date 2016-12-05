<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseRecordHandleViewModel extends ViewModel {
	public $viewFields = array(
		'CaseRecord' => array(
			'id',
			'case_id' => 'record_case_id',
			'name',
			'record_count',
			'record_type',
			'start_time',
			'end_time',
		)
	);
}
?>