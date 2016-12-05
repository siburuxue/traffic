<?php
namespace Admin\Controller;

/**
 * 案件 现场勘察
 */
class CaseSurveyHandleInfoController extends CaseDetailController {

	/**
	 * 编辑现场勘察
	 */
	public function detail() {
		$id = I('get.id','','int');
		$info = M('CaseSurvey')->getById($id);
		$departmentId = $this->my['department_id'];
		$map = array();
		$map['department_id'] = $departmentId;
		$userList = M('User')->where($map)->select();
		//现场图
		$cate = 3;
		$list1 = parent::getPhotoList($cate,$id);
		$this->assign('list1', $list1);
		//现场照片
		$cate = 4;
		$list2 = parent::getPhotoList($cate,$id);
		$this->assign('list2', $list2);
		//方位照相
		$cate = 5;
		$list3 = parent::getPhotoList($cate,$id);
		$this->assign('list3', $list3);
		//概览照相
		$cate = 6;
		$list4 = parent::getPhotoList($cate,$id);
		$this->assign('list4', $list4);
		//局部照相
		$cate = 7;
		$list5 = parent::getPhotoList($cate,$id);
		$this->assign('list5', $list5);
		//元素照相
		$cate = 8;
		$list6 = parent::getPhotoList($cate,$id);
		$this->assign('list6', $list6);
		//现场勘查笔录
		$cate = 9;
		$list7 = parent::getPhotoList($cate,$id);
		$this->assign('list7', $list7);
		//现场遗留物品清单
		$cate = 10;
		$list8 = parent::getPhotoList($cate,$id);
		$this->assign('list8', $list8);

		$this->assign('userId',$this->my['id']);
		$this->assign('userList',$userList);
		$this->assign('sceneState',get_custom_config('scene_state'));
		$this->assign('info',$info);
		$this->assign('id',$id);
		$this->display();
	}
}