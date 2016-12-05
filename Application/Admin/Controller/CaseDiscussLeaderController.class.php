<?php
namespace Admin\Controller;

/**
 * 集体研究
 */
class CaseDiscussLeaderController extends CommonController {

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
		$caseDiscuss = D('CaseDiscussView')->where($map)->order('create_time asc')->group('CaseDiscuss.id')->select();
		$this->assign('caseDiscuss', $caseDiscuss);

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

		$_POST['reporter'] = key($_POST['reporter']);
		$_POST['recorder'] = key($_POST['recorder']);

		$Model = D('CaseDiscuss');

		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['discuss_time'] = strtotime($data['discuss_time']);
		$data['accident_time'] = strtotime($data['accident_time']);

		$Model->startTrans();

		$id = $Model->add($data);

		if (!$id) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$member = $_POST['member'];
		$time = time();
		$discussMember = array();
		$userId = $this->my['id'];
		if (!empty($member)) {
			foreach ($member as $key => $value) {
				$item = array();
				$item['case_id'] = $data['case_id'];
				$item['case_discuss_id'] = $id;
				$item['member_user_id'] = $key;
				$item['create_time'] = $time;
				$item['create_user_id'] = $userId;
				$item['update_time'] = $time;
				$item['update_user_id'] = $userId;
				$discussMember[] = $item;

			}
			$res = M('CaseDiscussMember')->addAll($discussMember);
			if (false === $res) {
				$Model->rollback();
				$this->error('数据更新失败111');
			}
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
		$info = D('CaseDiscussView')->where($map)->find();
		if (empty($info)) {
			$this->error('集体研究不存在');
		}

		$map = array();
		$map['case_discuss_id'] = $id;
		$info['member'] = D('CaseDiscussMemberView')->where($map)->getField('member_user_name', true);

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
		$info = D('CaseDiscussView')->where($map)->find();
		if (empty($info)) {
			$this->error('集体研究不存在');
		}

		$map = array();
		$map['case_discuss_id'] = $id;
		$info['member'] = D('CaseDiscussMemberView')->where($map)->getField('member_user_id,member_user_name');
		$info['member_str'] = D('CaseDiscussMemberView')->where($map)->getField('member_user_name', true);

		$this->assign('info', $info);
		$this->display();

	}

	public function update() {

		$_POST['reporter'] = key($_POST['reporter']);
		$_POST['recorder'] = key($_POST['recorder']);

		$Model = D('CaseDiscuss');

		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['discuss_time'] = strtotime($data['discuss_time']);

		$Model->startTrans();

		$res = $Model->save($data);

		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$id = $data['id'];

		$map = array();
		$map['case_discuss_id'] = $id;
		$res = M('CaseDiscussMember')->where($map)->delete();
		if ($res === false) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$member = $_POST['member'];
		$time = time();
		$discussMember = array();
		$userId = $this->my['id'];
		if (!empty($member)) {
			foreach ($member as $key => $value) {
				$item = array();
				$item['case_id'] = $data['case_id'];
				$item['case_discuss_id'] = $id;
				$item['member_user_id'] = $key;
				$item['create_time'] = $time;
				$item['create_user_id'] = $userId;
				$item['update_time'] = $time;
				$item['update_user_id'] = $userId;
				$discussMember[] = $item;

			}
			$res = M('CaseDiscussMember')->addAll($discussMember);
			if (!$res) {
				$Model->rollback();
				$this->error('数据更新失败');
			}
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

		M('CaseDiscuss')->where($map)->delete();

		$map = array();
		$map['case_discuss_id'] = $id;
		M('CaseDiscussMember')->where($map)->delete();

		$this->success('删除成功');
	}
}