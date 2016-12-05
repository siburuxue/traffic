<?php
namespace Admin\Controller;

/**
 * 复核后答复
 */
class CaseReplyHandleController extends CommonController {

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
		$caseReply = D('CaseReplyView')->where($map)->order('create_time asc')->group('CaseReply.id')->select();
		$this->assign('caseReply', $caseReply);

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
		$case = D('CaseView')->where($map)->find();
		if (empty($case)) {
			$this->error('案件不存在');
		}
		$this->assign('case', $case);
		// dump($case);

		$this->display();

	}

	/**
	 * 插入
	 */
	public function insert() {

		$Model = D('CaseReply');

		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['reply_time'] = strtotime($data['reply_time']);
		$data['reply_user_id'] = $this->my['id'];

		$Model->startTrans();

		$id = $Model->add($data);

		if (!$id) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$Model->commit();
		$this->success('新增成功');

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
		$info = D('CaseReplyView')->where($map)->find();
		if (empty($info)) {
			$this->error('集体研究不存在');
		}

		$this->assign('info', $info);
		$this->display();

	}

	/**
	 * 编辑
	 */
	public function edit() {
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		$map = array();
		$map['id'] = $id;
		$info = D('CaseReplyView')->where($map)->find();
		if (empty($info)) {
			$this->error('集体研究不存在');
		}

		$this->assign('info', $info);
		$this->display();

	}

	public function update() {

		$Model = D('CaseReply');

		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['reply_time'] = strtotime($data['reply_time']);

		$Model->startTrans();

		$res = $Model->save($data);

		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$Model->commit();
		$this->success('更新成功');
	}

	public function delete() {
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');

		}
		$map = array();
		$map['id'] = $id;

		M('CaseReply')->where($map)->delete();

		$this->success('删除成功');
	}
}