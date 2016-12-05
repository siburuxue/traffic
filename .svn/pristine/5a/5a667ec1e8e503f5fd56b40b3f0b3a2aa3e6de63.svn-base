<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CasePhotoViewModel extends ViewModel {
	public $viewFields = array(
		'CasePhoto' => array(
			'id',
			'case_id',
			'cate',
			'ext_ida',
			'ext_idb',
			'ext_idc',
			'ext_idd',
			'ext_ide',
			'image_id',
			'thumb_id',
			'train',
			'create_time',
			'create_user_id',
			'update_time',
			'update_user_id',
			'is_del',
			'_type' => 'LEFT',
		),
        'Cate' => array(
            '_type' => 'LEFT',
            '_on' => 'CasePhoto.cate = Cate.cate',
        ),
		'Image' => array(
			'name' => 'image_name',
			'path' => 'image_path',
			'file_size' => 'image_file_size',
			'width' => 'image_width',
			'height' => 'image_height',
			'ext' => 'image_ext',
			'is_del' => 'image_is_del',
			'_table' => '__IMAGE__',
			'_on' => 'Image.id=CasePhoto.image_id and Image.is_del=0',
			'_type' => 'LEFT',
		),
		'Thumb' => array(
			'name' => 'thumb_name',
			'path' => 'thumb_path',
			'file_size' => 'thumb_file_size',
			'width' => 'thumb_width',
			'height' => 'thumb_height',
			'ext' => 'thumb_ext',
			'is_del' => 'thumb_is_del',
			'_table' => '__IMAGE__',
			'_on' => 'Thumb.id=CasePhoto.thumb_id and Thumb.is_del=0',
		),

	);
}
?>