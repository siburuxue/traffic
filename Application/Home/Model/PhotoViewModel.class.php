<?php
namespace Home\Model;
use Think\Model\ViewModel;

class PhotoViewModel extends ViewModel {
	public $viewFields = array(
		'Photo' => array(
			'id',
			'albumid',
			'imageid',
			'teacherid',
			'studentid',
			'summary',
			'createtime',
			'updatetime',
			'_type' => 'LEFT',
		),
		'Image' => array(
			'path',
			'thumb',
			'filesize',
			'width',
			'height',
			'ext',
			'uploadtime',
			'uploaduserid',
			'_on' => 'Photo.imageid=Image.id',
			'_type' => 'LEFT',
		),
		'Album' => array(
			'name' => 'albumname',
			'grade' => 'albumgrade',
			'classesid' => 'albumclassesid',
			'studentid' => 'albumstudentid',
			'teacherid' => 'albumteacherid',
			'albumtype',
			'albumdate',
			'createtime' => 'albumcreatetime',
			'updatetime' => 'albumupdatetime',
			'_on' => 'Photo.albumid=Album.id',
			'_type' => 'LEFT',
		),
		'Classes' => array(
			'name' => 'albumclassesname',
			'_on' => 'Album.classesid=Classes.id',
			'_type' => 'LEFT',
		),
		'Student' => array(
			'name' => 'studentname',
			'schoolid' => 'studentschoolid',
			'_on' => 'Photo.studentid=Student.id',
			'_type' => 'LEFT',
		),
		'Teacher' => array(
			'name' => 'teachername',
			'schoolid' => 'teacherschoolid',
			'_on' => 'Photo.teacherid=Teacher.id',
		),
	);
}
?>