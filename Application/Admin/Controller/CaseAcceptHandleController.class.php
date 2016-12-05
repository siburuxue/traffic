<?php
namespace Admin\Controller;

/**
 * 受案信息 - 受案登记
 */
class CaseAcceptHandleController extends CaseController {

	/**
	 * 添加受案登记
	 */
	public function add() {
		$id = I('get.case_id');
		$map = array();
		$map['case_id'] = $id;
		$info = D('CaseAcceptInfoView')->where($map)->order('Alarm.id desc')->find();
		//审批人
		$list = $this->getCheckUserList("case_accept_check_level_1");
		//案件来源
		$this->assign('caseSource', get_dict('case_source'));
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 新增保存
	 */
	public function insert() {
		// 实例化模型
		$Model = D('CaseAccept');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$data['alarm_time'] = strtotime($data['alarm_time']);
		// 开启事务
		$Model->startTrans();

		$map = array();
		$map['case_id'] = $this->case['id'];
		$countRs = M('CaseAccept')->where($map)->find();

		if (!empty($countRs)) {
			$this->error('该案件已经存在受案登记信息');
		}

		$id = $Model->add($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		$caseData = array();
		$caseData['id'] = $this->case['id'];
		$caseData['accept_status'] = 1;
		$caseRs = D('Case')->save($caseData);
		if ($caseRs === false) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		//操作日志
		$content = I('post.alarm_time').$this->myBrigade['name']."接到".I('post.reporter_name')."报警称".I('post.content');
		$this->saveCaseLog($this->case['id'],$content);
		// 成功
		$Model->commit();
		$this->success('新增成功', U('edit?case_id=' . $this->case['id'] . '&id=' . $id));
	}

	/**
	 * 编辑受案登记
	 */
	public function edit() {
		$id = I('get.id', '', 'int');
		$map = array();
		$map['id'] = $id;
		$info = M('CaseAccept')->where($map)->find();
		
		//审批人
		$list = $this->getCheckUserList("case_accept_check_level_1");

		//案件来源
		$this->assign('caseSource', get_dict('case_source'));
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 更新受案登记
	 */
	public function update() {
		// 实例化模型
		$Model = D('CaseAccept');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 报警信息编号
		$id = $data['id'];
		// 转换时间格式
		$data['alarm_time'] = strtotime($data['alarm_time']);
		// 开启事务
		$Model->startTrans();
		$result = $Model->save($data);

		// 数据保存失败
		if (!$result) {
			$Model->rollback();
			$this->error('数据保存失败');
		}
		//操作日志
		$content = I('post.alarm_time').$this->myBrigade['name']."接到".I('post.reporter_name')."报警称".I('post.content');
		$this->saveCaseLog($this->case['id'],$content);
		// 成功
		$Model->commit();
		$this->success('更新成功');
	}

	/**
	 * 审核记录
	 */
	public function editCheckTable() {
		$this->display('CaseAcceptHandle/edit/checkTable');
	}

	/**
	 * 受案登记图片
	 */
	public function editPhotoTable() {
		$this->display('CaseAcceptHandle/edit/photoTable');
	}

	/**
	 * 提请审批界面
	 */
	public function check() {
		$this->display();
	}

	/**
	 * 获取图片列表
	 */
	public function photoList() {
		$cate = 1;
		$id = I('post.id','','int');
		$list = parent::getPhotoList($cate,$id);
		$this->assign('list', $list);
		$this->display('CaseAcceptHandle/edit/photoTable');
	}

	/**
	 * 提审
	 */
	public function caseSubmitCheck() {
		$check_user_id = I('post.check_user_id', '', 'int');
		$id = I('post.id', '', 'int');
		$model = D('CaseAccept');
		// 创建数据
		$data = $model->create();
		if (false === $data) {
			$this->error($model->getError());
		}
		$data['alarm_time'] = strtotime($data['alarm_time']);
		$data['status'] = 1;
		$data['check_status'] = 0;
		$model->startTrans();

		if($id == ""){
			$result = $model->add($data);
			$id = $result;
		}else{
			$result = $model->save($data);
		}

		if ($result === false) {
			$model->rollback();
			$this->error('提请审批失败');
		}

		$caseData = array();
		$caseData['id'] = $this->case['id'];
		$caseData['accept_status'] = 1;
		$caseData['accept_check_status'] = 1;
		$caseData['update_time'] = time();
		$caseData['update_user_id'] = $this->my['id'];
		$caseRs = M('Case')->save($caseData);
		if ($caseRs === false) {
			$model->rollback();
			$this->error('数据保存失败');
		}

		$rs = $this->submitCheck($check_user_id, 0, $id);
		if ($rs === false) {
			$model->rollback();
			$this->error('提请审批失败');
		} else {
			//操作日志
			$content = I('post.alarm_time').$this->myBrigade['name']."接到".I('post.reporter_name')."报警称".I('post.content');
			$this->saveCaseLog($this->case['id'],$content);
			$model->commit();
			$this->success('提请审批成功', U('edit?case_id=' . $this->case['id'] . '&id=' . $id));
		}
	}

	/**
	 * 审批列表
	 */
	public function checkTable() {
		$id = I('post.id', '', 'int');
		$map = array();
		$map['item_id'] = $id;
		$map['cate'] = 0;
		$map['case_id'] = $this->case['id'];
		$model = D('CaseCheckView');
		$list = $model->where($map)->select();
		$array = array('待审核', '通过', '拒绝');
		foreach ($list as $key => $val) {
			$list[$key]['status'] = $array[$val['status']];
		}
		$this->assign('list', $list);
		$this->display('CaseAcceptHandle/edit/checkTable');
	}

	/**
	 * 检测必须入力项
	 */
	public function inputCheck(){
		// 实例化模型
		$Model = D('CaseAccept');

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$this->success('success');
	}

	//查看详细
	public function viewInfo(){
		$id = I('get.id', '', 'int');
		$map = array();
		$map['id'] = $id;
		$info = M('CaseAccept')->where($map)->find();

		//审批人
		$list = $this->getCheckUserList("case_accept_check_level_1");

		//案件来源
		$caseSource = get_dict('case_source');
		foreach($caseSource as $key => $val){
			if($val['id'] == $info['case_src']){
				$info['case_src'] = $val['name'];
			}
		}
		$this->assign('caseSource', $caseSource);
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->display();
	}
}