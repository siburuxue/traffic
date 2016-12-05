<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseDiscussViewModel extends ViewModel {
	public $viewFields = array(
		'CaseDiscuss' => array(
			'id',
			'case_id',
			'accident_area',
			'accident_place',
			'accident_time',
			'discuss_time',
			'reporter',
			'recorder',
			'case_summary',
			'research_opinion',
			'result',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'Reporter' => array(
			'true_name' => 'reporter_user_name',
			'_table' => '__USER__',
			'_on' => 'Reporter.id=CaseDiscuss.reporter',
			'_type' => 'LEFT',
		),
		'Recorder' => array(
			'true_name' => 'recorder_user_name',
			'_table' => '__USER__',
			'_on' => 'Recorder.id=CaseDiscuss.recorder',
		),
	);
}
?>