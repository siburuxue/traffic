<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CasePunishViewModel extends ViewModel {
	public $viewFields = array(
		'CasePunish' => array(
			'id',
			'case_id',
			'case_client_id',
			'opinion',
			'illegal',
			'punish_is_warning',
			'punish_warning_time',
			'punish_is_fine',
			'punish_fine_time',
			'punish_fine_money',
			'punish_fine_score',
			'punish_is_seize',
			'punish_seize_time',
			'punish_seize_date',
			'punish_is_revoke',
			'punish_revoke_time',
			'punish_revoke_date',
			'punish_is_detain',
			'punish_detain_time',
			'punish_detain_date',
			'criminal_time',
			'criminal_case_type',
			'criminal_is_detain',
			'criminal_detain_time',
			'criminal_is_arrest',
			'criminal_arrest_time',
			'criminal_is_remand',
			'criminal_remand_time',
			'criminal_is_sue',
			'criminal_sue_time',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'CaseClient' => array(
			'name' => 'case_client_name',
			'_on' => 'CasePunish.case_client_id=CaseClient.id',
		),
	);
}
?>