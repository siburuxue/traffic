<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class AlarmAccidentCarViewModel extends ViewModel {
	public $viewFields = array(
		'AlarmAccidentCar' => array(
			'alarm_id',
			'car_no',
			'car_type',
			'is_danger',
			'danger_info',
			'is_bus',
			'is_school',
			'remark',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'CarType' => array(
			'name' => 'car_type_name',
			'_table' => '__DICT_OPTION__',
			'_on' => 'AlarmAccidentCar.car_type=CarType.id',
		),
	);
}
?>