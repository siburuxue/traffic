<?php
namespace Admin\Controller;

/**
 * 复核 审核
 */
class CaseReviewCheckHandleInfoController extends CommonController {

	/**
	 * 首页
	 */
	public function index() {
		// 复核编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = M('CaseReview')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}
		if ($caseReview['accept_status'] != 1) {
			$this->error('复核信息非受理状态');
		}
		if ($caseReview['stop_status'] == 1) {
			$this->error('复核已终止');
		}

		// 判断是否存在复核审核
		$map = array();
		$map['case_review_id'] = $caseReviewId;
		$caseReviewCheck = M('CaseReviewCheck')->where($map)->find();
		if (empty($caseReviewCheck)) {
			$this->error('非法操作');
			//$this->redirect('add', array('case_review_id' => $caseReviewId));
		} else {
			$this->redirect('edit', array('case_review_id' => $caseReviewId));
		}
	}

	/**
	 * 新增
	 */
	public function add() {

		// 复核编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = D('CaseReviewView')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}
		$this->assign('caseReview', $caseReview);

		$this->display();
	}

	/**
	 * 插入
	 */
	public function insert() {
		$time = time();
		$userId = $this->my['id'];

		$Model = D('CaseReviewCheck');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		// 受理时间
		$data['review_time'] = strtotime($data['review_time']);

		// 判断是否存在复核审核
		$map = array();
		$map['case_review_id'] = $data['case_review_id'];
		$caseReviewCheck = M('CaseReviewCheck')->where($map)->find();
		if (!empty($caseReviewCheck)) {
			$this->error('复核审核信息已存在');
		}

		// 判断是否是死亡事故
		$map = array();
		$map['case_id'] = $data['case_id'];
		$map['is_del'] = 0;
		$case = M('Case')->where($map)->find();
		if (empty($case)) {
			$this->error('非法操作');
		}
		$data['is_death'] = $case['accident_type'] == 3 ? 1 : 0;

		// 开启事务
		$Model->startTrans();

		// 保存
		$id = $Model->add($data);
		if (!$id) {
			$Model->rollback();
			$this->error('数据更新失败1');
		}

		// 更新复核信息
		$cd = array();
		$cd['id'] = $data['case_review_id'];
		$cd['check_status'] = 1;
		$cd['check_time'] = $time;
		$cd['update_time'] = $time;
		$cd['update_user_id'] = $userId;
		$res = M('CaseReview')->save($cd);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 保存
		$Model->commit();
		$this->success('保存成功', U('edit?case_review_id=' . $data['case_review_id']));
	}

	/**
	 * 编辑审批意见
	 */
	public function edit() {
		// 复核信息编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = D('CaseReviewView')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}
		$this->assign('caseReview', $caseReview);

		// 复核审核
		$map = array();
		$map['case_review_id'] = $caseReviewId;
		$caseReviewCheck = M('CaseReviewCheck')->where($map)->find();
		if (empty($caseReviewCheck)) {
			$this->redirect('add', array('case_review_id' => $caseReviewId));
		}
		$this->assign('info', $caseReviewCheck);

		$this->display();
	}

	/**
	 * 审批意见保存
	 */
	public function update() {

		$Model = D('CaseReviewCheck');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['review_time'] = strtotime($data['review_time']);

		$res = $Model->save($data);
		if ($res) {
			$this->success('保存成功');
		} else {
			$this->error('数据更新失败');
		}
	}

	/**
	 * 复核结论
	 */
	public function result() {
		// 复核信息编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = D('CaseReviewView')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}
		$this->assign('caseReview', $caseReview);

		// 复核审核
		$map = array();
		$map['case_review_id'] = $caseReviewId;
		$info = M('CaseReviewCheck')->where($map)->find();
		if (empty($info)) {
			$this->redirect('add', array('case_review_id' => $caseReviewId));
		}
		$this->assign('info', $info);

		// 查找有权限的审核人
		$map = array();
		$map['power_name'] = 'case_review_check_level_2';
		$map['is_del'] = 0;
		$map['power_is_del'] = 0;
		$checkUser = D('UserPowerView')->where($map)->group('User.id')->field('id,user_name,true_name,department_name')->select();
		$this->assign('checkUser', $checkUser);

		$this->display();
	}

	/**
	 * 复核结论更新
	 */
	public function resultUpdate() {

		$Model = D('CaseReviewCheck');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['result_status'] = 1;

		$res = $Model->save($data);

		if ($res) {
			$this->success('保存成功');
		} else {
			$this->error('数据更新失败');
		}
	}

	/**
	 * 提请审批
	 */
	public function applyCheck() {

		$caseId = I('post.case_id', '', 'int');
		$caseReviewCheckId = I('post.case_review_check_id', '', 'int');
		$checkUserId = I('post.check_user_id', '', 'int');
		$time = time();
		$userId = $this->my['id'];

		if ($caseId === '' || $caseReviewCheckId === '' || $checkUserId === '') {
			$this->error('非法操作');
		}

		$case = M('Case')->getById($caseId);

		$Model = M('CaseReviewCheck');
		// 复核 审批信息
		$map = array();
		$map['id'] = $caseReviewCheckId;
		$caseReviewCheck = $Model->where($map)->find();
		if (empty($caseReviewCheck)) {
			$this->error('非法操作');
		}

		if ($caseReviewCheck['result_status'] == 0) {
			$this->error('复核结论未保存');
		}

		if ($caseReviewCheck['check_status'] == 1) {
			$this->error('审核已提交');
		}

		if ($caseReviewCheck['status'] == 1) {
			$this->error('审核已通过');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewCheck['case_review_id'];
		$caseReview = M('CaseReview')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('非法操作');
		}

		if ($caseReview['check_status'] != 1) {
			$this->error('未进入复核审批流程');
		}

		$Model->startTrans();

		$data = array();
		$data['id'] = $caseReviewCheckId;
		// $data['check_1_user_id'] = $checkUserId;
		// $data['check_1_time'] = 0;
		// $data['check_1_status'] = 0;
		// $data['check_2_user_id'] = 0;
		// $data['check_2_time'] = 0;
		// $data['check_2_status'] = 0;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$data['check_status'] = 1;
		$data['status'] = 0;
		$res = $Model->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$data = array();
		$data['case_id'] = $caseId;
		$data['cate'] = 20;
		$data['item_id'] = $caseReviewCheckId;
		$data['check_user_id'] = $checkUserId;
		$data['check_time'] = 0;
		$data['submit_user_id'] = $userId;
		$data['submit_time'] = $time;
		$data['status'] = 0;
		$data['remark'] = '';
		$data['pid'] = 0;
		$data['level'] = 0;
		$data['create_time'] = $time;
		$data['create_user_id'] = $userId;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$data['is_del'] = 0;
		$res = M('CaseCheck')->add($data);

		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$this->saveCaseLog($caseId, date('Y-m-d H:i', $time) . '对' . $case['code'] . '的复核结论及审批意见提请审批');

		$Model->commit();
		$this->success('提交成功');
	}

	/**
	 * 答复
	 */
	public function reply() {
		// 复核信息编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = D('CaseReviewView')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}
		$this->assign('caseReview', $caseReview);

		// 复核审核
		$map = array();
		$map['case_review_id'] = $caseReviewId;
		$caseReviewCheck = M('CaseReviewCheck')->where($map)->order('create_time desc')->find();
		$this->assign('caseReviewCheck', $caseReviewCheck);

		$caseCheck = array();
		if (!empty($caseReviewCheck)) {
			$map = array();
			$map['case_id'] = $caseReview['case_id'];
			$map['cate'] = 20;
			$map['item_id'] = $caseReviewCheck['id'];
			$caseCheck = D('CaseCheckView')->where($map)->order('check_time asc')->select();
		}
		$this->assign('caseCheck', $caseCheck);

		$this->display();

	}

}