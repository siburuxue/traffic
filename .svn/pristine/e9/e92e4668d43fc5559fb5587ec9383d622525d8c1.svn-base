<?php
namespace Admin\Controller;

/**
 * 领导指示
 */
class CaseDirectiveLeaderController extends CommonController {

	/**
	 * 首页
	 */
	public function index() {
		// 案件编号
		$caseId = I('get.case_id', '', 'int');
		if ($caseId === '') {
			$this->error('非法操作');
		}

		// 案件信息
		$map = array();
		$map['id'] = $caseId;
		$map['is_del'] = 0;
		$case = M('Case')->where($map)->find();
		if (empty($case)) {
			$this->error('案件不存在');
		}
		$this->assign('case', $case);

		$map = array();
		$map['case_id'] = $caseId;
		$caseDirective = D('CaseDirectiveView')->where($map)->order('create_time asc')->group('CaseDirective.id')->select();
		$this->assign('caseDirective', $caseDirective);

		$this->display();

	}

	/**
	 * 新增
	 */
	public function add() {
		// 案件编号
		$caseId = I('get.case_id', '', 'int');
		if ($caseId === '') {
			$this->error('非法操作');
		}

		// 案件信息
		$map = array();
		$map['id'] = $caseId;
		$map['is_del'] = 0;
		$case = M('Case')->where($map)->find();
		if (empty($case)) {
			$this->error('案件不存在');
		}
		$this->assign('case', $case);

		$this->display();

	}

	/**
	 * 插入
	 */
	public function insert() {
		$Model = D('CaseDirective');

		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['directive_time'] = strtotime($data['directive_time']);

		$res = $Model->add($data);

		if (!$res) {
			$this->error('数据更新失败');
		} else {
			$this->success('新增成功');
		}

	}

	/**
	 * 查看
	 */
	public function detail() {
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		$map = array();
		$map['id'] = $id;
		$info = D('CaseDirectiveView')->where($map)->find();
		if (empty($info)) {
			$this->error('指示意见不存在');
		}

		$this->assign('info', $info);
		$this->display();

	}

}