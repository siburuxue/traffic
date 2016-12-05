<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class ArchiveCaseViewViewModel extends ViewModel {
	public $viewFields = array(
		'Archive' => array(
			'id',
			'case_id',
			'code',
			'name',
			'catalog',
			'dossier',
			'case_no',
			'place',
			'handle',
			'department_id',
			'status',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'CaseInfo' => array(
			'id' => 'case_info_id',
			'code' => 'case_info_code',
			'num' => 'case_info_num',
			'cate' => 'case_info_cate',
			'accident_name' => 'case_info_accident_name',
			'accident_time' => 'case_info_accident_time',
			'accident_place' => 'case_info_accident_place',
			'lng' => 'case_info_lng',
			'lat' => 'case_info_lat',
			'death_num' => 'case_info_death_num',
			'hurt_num' => 'case_info_hurt_num',
			'property_loss' => 'case_info_property_loss',
			'accident_type' => 'case_info_accident_type',
			'first_cognizance' => 'case_info_first_cognizance',
			'department_id' => 'case_info_department_id',
			'accept_status' => 'case_info_accept_status',
			'survey_status' => 'case_info_survey_status',
			'client_status' => 'case_info_client_status',
			'record_status' => 'case_info_record_status',
			'checkup_status' => 'case_info_checkup_status',
			'law_doc_status' => 'case_info_law_doc_status',
			'cognizance_status' => 'case_info_cognizance_status',
			'punish_status' => 'case_info_punish_status',
			'mediate_status' => 'case_info_mediate_status',
			'catalog_status' => 'case_info_catalog_status',
			'create_time' => 'case_info_create_time',
			'create_user_id' => 'case_info_create_user_id',
			'update_time' => 'case_info_update_time',
			'update_user_id' => 'case_info_update_user_id',
			'is_del' => 'case_info_is_del',
			'del_reason' => 'case_info_del_reason',
			'is_over' => 'case_info_is_over',
			'cognizance_type' => 'case_info_cognizance_type',
			'_table' => '__CASE__',
			'_on' => 'CaseInfo.id=Archive.case_id',
			'_type' => 'LEFT',
		),

		'CaseClient' => array(
			'id' => 'CaseClient_id',
			'case_id' => 'CaseClient_case_id',
			'name' => 'CaseClient_name',
			'sex' => 'CaseClient_sex',
			'id_type' => 'CaseClient_id_type',
			'idno' => 'CaseClient_idno',
			'age' => 'CaseClient_age',
			'tel' => 'CaseClient_tel',
			'driver_licence_type' => 'CaseClient_driver_licence_type',
			'first_driver_licence_time' => 'CaseClient_first_driver_licence_time',
			'traffic_type' => 'CaseClient_traffic_type',
			'grade_type' => 'CaseClient_grade_type',
			'car_no' => 'CaseClient_car_no',
			'blame_type' => 'CaseClient_blame_type',
			'hurt_type' => 'CaseClient_hurt_type',
			'death_time' => 'CaseClient_death_time',
			'is_escape' => 'CaseClient_is_escape',
			'escape_catch_man_time' => 'CaseClient_escape_catch_man_time',
			'escape_catch_car_time' => 'CaseClient_escape_catch_car_time',
			'address' => 'CaseClient_address',
			'is_checked_breath' => 'CaseClient_is_checked_breath',
			'breath_val' => 'CaseClient_breath_val',
			'is_checked_blood' => 'CaseClient_is_checked_blood',
			'blood_time' => 'CaseClient_blood_time',
			'is_checked_urine' => 'CaseClient_is_checked_urine',
			'urine_result' => 'CaseClient_urine_result',
			'detain_time' => 'CaseClient_detain_time',
			'detain_parking' => 'CaseClient_detain_parking',
			'detain_force_id' => 'CaseClient_detain_force_id',
			'detain_driver_licence' => 'CaseClient_detain_driver_licence',
			'detain_return_time' => 'CaseClient_detain_return_time',
			'detain_return_user_id' => 'CaseClient_detain_return_user_id',
			'punish_is_warning' => 'CaseClient_punish_is_warning',
			'punish_is_fine' => 'CaseClient_punish_is_fine',
			'punish_money' => 'CaseClient_punish_money',
			'punish_score' => 'CaseClient_punish_score',
			'punish_is_seize' => 'CaseClient_punish_is_seize',
			'punish_seize_time' => 'CaseClient_punish_seize_time',
			'punish_is_revoke' => 'CaseClient_punish_is_revoke',
			'punish_revoke_time' => 'CaseClient_punish_revoke_time',
			'punish_is_detain' => 'CaseClient_punish_is_detain',
			'punish_detain_time' => 'CaseClient_punish_detain_time',
			'criminal_case_type' => 'CaseClient_criminal_case_type',
			'criminal_measure' => 'CaseClient_criminal_measure',
			'_on' => 'CaseClient.case_id=Archive.case_id',
			'is_del' => 'CaseClient_is_del',
		),
	);
}
?>