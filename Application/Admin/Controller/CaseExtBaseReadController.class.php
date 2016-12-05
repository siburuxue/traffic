<?php
namespace Admin\Controller;

/**
 * 案件08系统--基本信息
 */
class CaseExtBaseReadController extends CommonController {

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
		$case_ext_base = C('custom_case_ext.case_ext_base');
		$this->assign('case_ext_base', $case_ext_base);
		//判断是否已经生成 基本信息
		$map = array();
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		$data = D('CaseExtBase')->where($map)->find();
		if ($data) {
			$this->redirect(U('CaseExtBaseRead/edit', array('case_id' => $case_id)));
		}

		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {

		// 实例化模型
		$Model = D('CaseExtBase');
		// 开启事务
		$Model->startTrans();

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

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
		$map = array();
		$map['case_id'] = $case_id;
		$map['is_del'] = 0;
		$data = D('CaseExtBase')->where($map)->find();
		$this->assign('data', $data);
		if (!$data) {
			$this->error('未能读取有效信息');
		}
		$case_ext_base = C('custom_case_ext.case_ext_base');
		$this->assign('case_ext_base', $case_ext_base);

		// 渲染
		$this->display();
	}

	/**
	 * 编辑
	 */
	public function update() {

		// 实例化模型
		$Model = D('CaseExtBase');
		// 开启事务
		$Model->startTrans();

		// 创建数据
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

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

}
