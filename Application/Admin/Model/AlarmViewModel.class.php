<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class AlarmViewModel extends ViewModel {
	public $viewFields = array(
		'Alarm' => array(
			'id',
			'alarm_time',
			'case_source',
			'alarm_name',
			'contact',
			'is_protect',
			'accident_time',
			'accident_place',
			'casualties',
			'remark',
			'scene_end_time',
			'handle_type',
			'is_link',
			'case_id',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'del_reason',
			'_type' => 'LEFT',
		),
		'CaseSource' => array(
			'name' => 'case_source_name',
			'_table' => '__DICT_OPTION__',
			'_on' => 'Alarm.case_source=CaseSource.id',
			'_type' => 'LEFT',
		),
		'Receiver' => array(
			'true_name' => 'receiver_true_name',
			'_table' => '__USER__',
			'_on' => 'Receiver.id=Alarm.create_user_id',
		),
	);
}
?>