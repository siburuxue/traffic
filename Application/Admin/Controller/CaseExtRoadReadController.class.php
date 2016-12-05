<?php
namespace Admin\Controller;

/**
 * 案件08系统--道路信息
 */
class CaseExtRoadReadController extends CommonController {

	/**
	 * 新增界面
	 */
	public function add() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}

		$this->assign('caseData', $caseData);
		$case_ext_road = C('custom_case_ext.case_ext_road');
		$this->assign('case_ext_road', $case_ext_road);
		//判断是否已经生成 基本信息
		$map = array();
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		$data = D('CaseExtRoad')->where($map)->find();
		if ($data) {
			$this->redirect(U('CaseExtRoadRead/edit', array('case_id' => $case_id)));
		}

		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {
		if ($_POST['hidden_danger_pid']) {
			$hidden_danger_pid = serialize($_POST['hidden_danger_pid']);
			$hidden_danger_id = serialize($_POST['hidden_danger_id']);
		}
		// 实例化模型
		$Model = D('CaseExtRoad');
		// 开启事务
		$Model->startTrans();

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$data['hidden_danger_pid'] = $hidden_danger_pid;
		$data['hidden_danger_id'] = $hidden_danger_id;

		$id = $Model->add($data);

		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('操作失败');
		}

		// 成功
		$Model->commit();
		$this->success('操作成功');
	}

	/**
	 * 新增界面
	 */
	public function edit() {
		$case_id = I('get.case_id', '', 'strip_tags');
		$map = array();
		$map['is_del'] = 0;
		$map['id'] = $case_id;
		$caseData = D('Case')->where($map)->find();
		if (!$caseData) {
			$this->error('未能读取有效案件信息');
		}
		$this->assign('caseData', $caseData);
		$case_ext_road = C('custom_case_ext.case_ext_road');
		$this->assign('case_ext_road', $case_ext_road);

		$map = array();
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		$data = D('CaseExtRoad')->where($map)->find();
		$this->assign('data', $data);
		if (!$data) {
			$this->error('未能读取有效信息');
		}
		$hidden_danger_data = unserialize($data['hidden_danger_pid']);
		unset($data['hidden_danger_pid']);
		unset($data['hidden_danger_id']);
		$this->assign('dataJson', json_encode(array(0 => $data)));

		$this->assign('hidden_danger_data', $hidden_danger_data);

		// 渲染
		$this->display();
	}

	/**
	 * 编辑
	 */
	public function update() {
		if ($_POST['hidden_danger_pid']) {
			$hidden_danger_pid = serialize($_POST['hidden_danger_pid']);
			$hidden_danger_id = serialize($_POST['hidden_danger_id']);
		}
		// 实例化模型
		$Model = D('CaseExtRoad');
		// 开启事务
		$Model->startTrans();
		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$data['hidden_danger_pid'] = $hidden_danger_pid;
		$data['hidden_danger_id'] = $hidden_danger_id;

		$id = $Model->save($data);
		// 数据保存失败
		if (!$id) {
			$Model->rollback();
			$this->error('操作失败');
		}
		// 成功
		$Model->commit();
		$this->success('操作成功');
	}
	//ajax获取隐藏危险子选项
	public function getHiddenDangerId() {
		$pid = I('post.pid', '', 'strip_tags');
		if (!$pid) {
			$this->error('无相关数据');
		}
		$caseExtRoad = C('custom_case_ext.case_ext_road');
		$hidden_danger_id = $caseExtRoad['hidden_danger_pid'][$pid]['value'];
		$this->success(array($hidden_danger_id));
	}
}
