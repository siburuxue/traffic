<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class DictOptionViewModel extends ViewModel {
	public $viewFields = array(
		'DictOption' => array(
			'id',
			'dict_id',
			'name',
			'code',
			'train',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'Dict' => array(
			'name' => 'dict_name',
			'title' => 'dict_title',
			'remark' => 'dict_remark',
			'is_custom' => 'dict_is_custom',
			'is_del' => 'dict_is_del',
			'_on' => 'Dict.id=DictOption.dict_id',
		),
	);
}
?>