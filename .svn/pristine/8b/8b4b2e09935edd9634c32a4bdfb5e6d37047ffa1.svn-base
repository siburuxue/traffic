<?php
namespace Home\Model;
use Think\Model\ViewModel;

class ClassesViewModel extends ViewModel {
	public $viewFields = array(
		'Classes' => array(
			'id',
			'grade',
			'name',
			'summary',
			'lifetime',
			'cover',
			'defaultalbumid',
			'updatetime',
			'_type' => 'LEFT',
		),
		'Image' => array(
			'path',
			'thumb',
			'_on' => 'Classes.cover=Image.id',
		),
	);
}
?>