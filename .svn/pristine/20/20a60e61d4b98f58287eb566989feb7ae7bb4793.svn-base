<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class ArchiveLendViewModel extends ViewModel {
	public $viewFields = array(
		'ArchiveLend' => array(
			'id',
			'archive_id',
			'cate',
			'user',
			'time',
			'content',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'User' => array(
			'id' => 'user_id',
			'true_name' => 'true_name',
			'user_name' => 'user_name',
			'is_del' => 'user_is_del',
			'_on' => 'ArchiveLend.user=User.id',
			'_type' => 'LEFT',
		),
		'UserCreate' => array(
			'id' => 'create_user_id',
			'true_name' => 'create_true_name',
			'user_name' => 'create_user_name',
			'is_del' => 'create_user_is_del',
			'_table' => '__USER__',
			'_on' => 'ArchiveLend.create_user_id=UserCreate.id',
		),

	);
}
?>