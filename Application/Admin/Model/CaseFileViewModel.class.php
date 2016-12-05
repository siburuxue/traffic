<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseFileViewModel extends ViewModel {
	public $viewFields = array(
		'CaseFile' => array(
			'id',
			'case_id',
			'cate',
			'ext_ida',
			'ext_idb',
			'ext_idc',
			'ext_idd',
			'ext_ide',
			'file_id',
			'train',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
		'File' => array(
			'id' =>'file_id',
			'name',
			'path' => 'file_path',
			'file_size',
			'ext',
			'is_del',
			'_table' => '__FILE__',
			'_on' => 'File.id=CaseFile.file_id and File.is_del=0',
		),
	);
}
?>