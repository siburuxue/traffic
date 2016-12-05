<?php
namespace Admin\Controller;

/**
 * 案件 现场勘察
 */
class CaseSurveyHandleController extends CaseCommonController {

	/**
	 * 新增现场勘察
	 */
	public function add() {
		$map = array();
		$map['case_id'] = $this->case['id'];
		$caseSurveyList = M('CaseSurvey')->where($map)->select();
		if(count($caseSurveyList) > 0){
			$this->error('非法访问');
		}
		$departmentId = $this->my['department_id'];
		$map = array();
		$map['department_id'] = $departmentId;
		$userList = M('User')->where($map)->select();
		$this->assign('userId',$this->my['id']);
		$this->assign('userList',$userList);
		$this->display();
	}

	/**
	 * 执行新增
	 */
	public function insert() {
		$case_id = I('post.case_id');
		$Model = D('CaseSurvey');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		// 转换时间格式
		$data['start_time'] = strtotime($data['start_time']);
		$data['end_time'] = strtotime($data['end_time']);
		// 开启事务
		$Model->startTrans();
		$id = $Model->add($data);
		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		$map = array();
		$map['survey_status'] = 1;
		$map['id'] = $case_id;
		$rs = M('Case')->save($map);
		if ($rs === false) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		$user1 = M('User')->getById(I('post.first_user_id'));
		$user2 = M('User')->getById(I('post.second_user_id'));
		//操作日志
		$content = $this->myBrigade['name']."事故处理民警".$user1['true_name']."、".$user2['true_name']."在".I('post.start_time')."到达现场开始勘查".I('post.end_time')."勘查结束";
		$this->saveCaseLog($this->case['id'],$content);
		// 成功
		$Model->commit();
		$this->success('新增成功', U("edit?case_id={$case_id}&id=" . $id));
	}

	/**
	 * 编辑现场勘察
	 */
	public function edit() {
		$id = I('get.id','','int');
		if($id == ""){
			$this->redirect(U('add?case_id='.$this->case['id'].'&id='));
		}
		$info = M('CaseSurvey')->getById($id);
		if(empty($info)){
			$this->error('该案件的现场勘查信息不存在');
		}
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

	/**
	 * 更新现场勘察
	 */
	public function update() {
		$Model = D('CaseSurvey');
		$data = $Model->create($_POST);
		if (false === $data) {
			$this->error($Model->getError());
		}
		// 转换时间格式
		if($data['start_time'] != ''){
			$data['start_time'] = strtotime($data['start_time']);
		}
		if($data['end_time'] != ""){
			$data['end_time'] = strtotime($data['end_time']);
		}
		// 开启事务
		$Model->startTrans();

		$rs = $Model->save($data);
		// 数据保存失败
		if ($rs === false) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		//操作日志
		$user1 = M('User')->getById(I('post.first_user_id'));
		$user2 = M('User')->getById(I('post.second_user_id'));
		$content = $this->myBrigade['name']."事故处理民警".$user1['true_name']."、".$user2['true_name']."在".I('post.start_time')."到达现场开始勘查".I('post.end_time')."勘查结束";
		$this->saveCaseLog($this->case['id'],$content);
		// 成功
		$Model->commit();
		$this->success('数据保存成功',U('edit?case_id='.$this->case['id'].'&id='.$data['id']));
	}

	/**
	 * 图片列表
	 */
	public function photoList() {
		//图片类型 3-10
		$cate = I('post.cate','','int');
		//图片表格文件名后缀数字 1-8
		$index = (int)$cate - 2;
		$id = I('post.id','','int');
		$list = parent::getPhotoList($cate,$id);
		$this->assign("list{$index}", $list);
		$this->display("CaseSurveyHandle/edit/photoTable{$index}");
	}

	/**
	 * 编辑现场勘查信息数据加载页面
	 */
	public function editInfo(){
		$id = I('get.id','','int');
		if($id == ""){
			$this->error('非法访问');
		}
		$info = M('CaseSurvey')->getById($id);
		if(empty($info)){
			$this->error('该案件的现场勘查信息不存在');
		}
		$departmentId = $this->my['department_id'];
		$map = array();
		$map['department_id'] = $departmentId;
		$userList = M('User')->where($map)->select();
		$this->assign('info',$info);
		$this->assign('userList',$userList);
		$this->assign('id',$id);
		$this->display();
	}
}