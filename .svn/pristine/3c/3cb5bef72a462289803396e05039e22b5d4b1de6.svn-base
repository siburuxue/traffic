<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class BloodtubeCateExeclViewModel extends ViewModel {
	public $viewFields = array(
		'BloodtubeCate' => array(
			'id',
			'case_id',
			'case_client_id',
			"FROM_UNIXTIME(used_time,'%Y-%m-%d %H:%i')" => 'used_time1',
			'used_time',
			'used_user_id',
			'is_used',
			'to_department_time',
			'to_department_user_id',
			'target_department_id',
			'is_to_department',
			'to_user_time',
			'to_user_user_id',
			'target_user_id',
			'is_to_user',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'Bloodtube' => array(
			'id' => 'bloodtube_id',
			'bloodtube_cate_id' => 'bloodtube_cate_id',
			'code' => 'bloodtube_code',
			'recover_type' => 'bloodtube_recover_type',
			'recover_time' => 'bloodtube_recover_time',
			'recover_user_id' => 'bloodtube_recover_user_id',
			'is_recover' => 'bloodtube_is_recover',
			'is_del' => 'bloodtube_is_del',
			'_on' => 'Bloodtube.bloodtube_cate_id=BloodtubeCate.id',
		),
		'Department' => array(
			'name' => 'department_name',
			'cate' => 'department_cate',
			'is_del' => 'department_is_del',
			'_on' => 'BloodtubeCate.target_department_id=Department.id',
			'_type' => 'LEFT',
		),
		'User' => array(
			'id' => 'user_id',
			'true_name' => 'true_name',
			'user_name' => 'user_name',
			'is_del' => 'user_is_del',
			'_on' => 'BloodtubeCate.target_user_id=User.id',
		),

	);
}
?>