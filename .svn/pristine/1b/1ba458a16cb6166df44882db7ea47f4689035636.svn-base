<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class AlarmEscapeCarViewModel extends ViewModel {
	public $viewFields = array(
		'AlarmEscapeCar' => array(
			'alarm_id',
			'car_no',
			'car_type',
			'color',
			'direction',
			'other',
			'body_des',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'CarType' => array(
			'name' => 'car_type_name',
			'_table' => '__DICT_OPTION__',
			'_on' => 'AlarmEscapeCar.car_type=CarType.id',
		),
	);
}
?>