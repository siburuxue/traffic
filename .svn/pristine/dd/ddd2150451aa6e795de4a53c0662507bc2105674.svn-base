<?php
namespace Home\Controller;

class IndexController extends CommonController {

	public function __construct() {

		parent::__construct();

	}

	public function index() {

		$this->display();

	}

	public function classes() {
		$classesid = I('get.id', '', 'int');
		if ($classesid === '') {
			$this->error('非法操作');
		}

		// 班级信息
		$map['id'] = $classesid;
		$classes = M('Classes')->where($map)->find();
		unset($map);
		if (empty($classes)) {
			$this->error('班级不存在');
		}
		$this->assign('classes', $classes);

		// 相册信息
		if ($classes['defaultalbumid']) {
			$map['id'] = $classes['defaultalbumid'];
		} else {
			$map['classesid'] = $classesid;
			$map['albumtype'] = 2;
		}

		$album = M('Album')->where($map)->order('createtime desc')->find();
		unset($map);

		$map['albumid'] = $album['id'];
		$photo = D('PhotoView')->where($map)->select();
		unset($map);

		// 学生信息
		$map['classesid'] = $classesid;
		$student = D('Student')->where($map)->order('schoolid asc')->select();
		foreach ($student as $key => $value) {
			$p = list_search($photo, array('studentid' => $value['id']));
			$student[$key]['photo'] = empty($p) ? array() : $p[0];
		}
		$this->assign('student', $student);

		$this->display();
	}

}