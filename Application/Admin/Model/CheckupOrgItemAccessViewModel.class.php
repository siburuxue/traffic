<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CheckupOrgItemAccessViewModel extends ViewModel {
	public $viewFields = array(
		'CheckupOrgItem' => array(
			'id',
			'checkup_org_obj',
			'name',
			'create_time',
			'create_user_id',
			'create_time',
			'update_time',
			'create_time',
			'update_user_id',
			'_type' => 'LEFT',
		),
		'CheckupOrgItemAccess' => array(
			'id' => 'checkup_org_item_access_id',
			'checkup_org_id' => 'checkup_org_id',
			'checkup_org_item_id' => 'checkup_org_item_id',
			'_on' => 'CheckupOrgItemAccess.checkup_org_item_id=CheckupOrgItem.id',
			'_type' => 'LEFT',
		),

	);
}
?>