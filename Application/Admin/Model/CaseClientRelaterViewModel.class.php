<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseClientRelaterViewModel extends ViewModel {
	public $viewFields = array(
		'CaseClientRelater' => array(
			'id',
			'case_client_id',
			'name',
			'sex',
			'age',
			'idno',
			'tel',
			'address',
			'relation',
			'add_type',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'CaseClient' => array(
			'case_id',
			'name' => 'case_client_name',
			'sex' => 'case_client_sex',
			'id_type' => 'case_client_id_type',
			'idno' => 'case_client_idno',
			'age' => 'case_client_age',
			'tel' => 'case_client_tel',
			'driver_licence_type' => 'case_client_driver_licence_type',
			'first_driver_licence_time' => 'case_client_first_driver_licence_time',
			'traffic_type' => 'case_client_traffic_type',
			'grade_type' => 'case_client_grade_type',
			'car_no' => 'case_client_car_no',
			'blame_type' => 'case_client_blame_type',
			'hurt_type' => 'case_client_hurt_type',
			'death_time' => 'case_client_death_time',
			'is_escape' => 'case_client_is_escape',
			'escape_catch_man_time' => 'case_client_escape_catch_man_time',
			'escape_catch_car_time' => 'case_client_escape_catch_car_time',
			'address' => 'case_client_address',
			'is_checked_breath' => 'case_client_is_checked_breath',
			'breath_val' => 'case_client_breath_val',
			'is_checked_blood' => 'case_client_is_checked_blood',
			'blood_time' => 'case_client_blood_time',
			'is_checked_urine' => 'case_client_is_checked_urine',
			'urine_result' => 'case_client_urine_result',
			'detain_time' => 'case_client_detain_time',
			'detain_parking' => 'case_client_detain_parking',
			'detain_force_id' => 'case_client_detain_force_id',
			'detain_driver_licence' => 'case_client_detain_driver_licence',
			'detain_return_time' => 'case_client_detain_return_time',
			'detain_return_user_id' => 'case_client_detain_return_user_id',
			'punish_is_warning' => 'case_client_punish_is_warning',
			'punish_is_fine' => 'case_client_punish_is_fine',
			'punish_money' => 'case_client_punish_money',
			'punish_score' => 'case_client_punish_score',
			'punish_is_seize' => 'case_client_punish_is_seize',
			'punish_seize_time' => 'case_client_punish_seize_time',
			'punish_is_revoke' => 'case_client_punish_is_revoke',
			'punish_revoke_time' => 'case_client_punish_revoke_time',
			'punish_is_detain' => 'case_client_punish_is_detain',
			'punish_detain_time' => 'case_client_punish_detain_time',
			'criminal_case_type' => 'case_client_criminal_case_type',
			'criminal_measure' => 'case_client_criminal_measure',
			'create_time' => 'case_client_create_time',
			'create_user_id' => 'case_client_create_user_id',
			'update_time' => 'case_client_update_time',
			'update_user_id' => 'case_client_update_user_id',
			'is_del' => 'case_client_is_del',
			'_on' => 'CaseClient.id=CaseClientRelater.case_client_id and CaseClient.is_del=0',
		),
	);
}
?>