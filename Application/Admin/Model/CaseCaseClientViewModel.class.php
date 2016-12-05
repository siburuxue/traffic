<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCaseClientViewModel extends ViewModel {
	public $viewFields = array(
		'CaseInfo' => array(
			'id',
			'code',
			'num',
			'cate',
			'accident_name',
			'accident_time',
			'accident_place',
			'lng',
			'lat',
			'death_num',
			'hurt_num',
			'property_loss',
			'accident_type',
			'first_cognizance',
			'department_id',
			'accept_status',
			'survey_status',
			'client_status',
			'record_status',
			'checkup_status',
			'law_doc_status',
			'cognizance_status',
			'punish_status',
			'mediate_status',
			'catalog_status',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'del_reason',
			'is_over',
			'cognizance_type',
			'_table' => '__CASE__',
			'_type' => 'LEFT',
		),
		'CaseClient' => array(
			'id' => 'case_client_id',
			'case_id' => 'case_client_case_id',
			'name' => 'case_client_name',
			'address' => 'case_client_address',
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
			'is_del' => 'case_client_is_del',
			'create_time' => 'case_client_create_time',
			'train1' => 'train1',
			'train2' => 'train2',
			'train3' => 'train3',
			'_on' => 'CaseClient.case_id=CaseInfo.id',
			'_type' => 'LEFT',

		),
	);
}
?>