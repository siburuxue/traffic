<?php
namespace Admin\Controller;

/**
 * 领导工作 复核审核
 */
class CaseCheckCaseReviewLeaderController extends CommonController {

	/**
	 * 首页
	 */
	public function index() {
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}

		// 审核信息
		$map = array();
		$map['id'] = $id;
		$map['is_del'] = 0;
		$caseCheck = M('CaseCheck')->where($map)->find();
		$this->assign('caseCheck', $caseCheck);

		// 审核对象信息 复核审批
		$map = array();
		$map['id'] = $caseCheck['item_id'];
		$caseReviewCheck = M('CaseReviewCheck')->where($map)->find();
		$this->assign('caseReviewCheck', $caseReviewCheck);

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewCheck['case_review_id'];
		$caseReview = M('CaseReview')->where($map)->find();
		$this->assign('caseReview', $caseReview);

		// 如果是一级审批并且是死亡事故
		if ($caseCheck['level'] == 0 && $caseReviewCheck['is_death'] == 1) {
			// 查找有权限的审核人
			$map = array();
			$map['power_name'] = 'case_review_check_level_3';
			$map['is_del'] = 0;
			$map['power_is_del'] = 0;
			$checkUser = D('UserPowerView')->where($map)->group('User.id')->field('id,user_name,true_name,department_name')->select();
			$this->assign('checkUser', $checkUser);
		}

		$this->display();

	}

	/**
	 * 不同意
	 */
	public function refuse() {

		$id = I('post.id', '', 'int');
		$remark = I('post.remark', '', 'trim,htmlspecialchars');
		$time = time();
		$userId = $this->my['id'];

		if ($id === '') {
			$this->error('非法操作');
		}

		$Model = M('CaseCheck');

		// 审核信息
		$map = array();
		$map['id'] = $id;
		$map['is_del'] = 0;
		$caseCheck = $Model->where($map)->find();
		if (empty($caseCheck)) {
			$this->error('审核信息不存在');
		}

		$Model->startTrans();

		$data = array();
		$data['id'] = $id;
		$data['check_time'] = $time;
		$data['status'] = 2;
		$data['remark'] = $remark;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = $Model->save($data);

		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$data = array();
		$data['id'] = $caseCheck['item_id'];
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$data['check_status'] = 0;
		$data['status'] = 2;
		$res = M('CaseReviewCheck')->save($data);

		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$Model->commit();
		$this->success('提交成功');

	}

	/**
	 * 完成同意
	 */
	public function resume() {

		$id = I('post.id', '', 'int');
		$remark = I('post.remark', '', 'trim,htmlspecialchars');
		$time = time();
		$userId = $this->my['id'];

		if ($id === '') {
			$this->error('非法操作');
		}

		$Model = M('CaseCheck');

		// 审核信息
		$map = array();
		$map['id'] = $id;
		$map['is_del'] = 0;
		$caseCheck = $Model->where($map)->find();
		if (empty($caseCheck)) {
			$this->error('审核信息不存在');
		}

		$map = array();
		$map['id'] = $caseCheck['item_id'];
		$caseReviewCheck = M('CaseReviewCheck')->where($map)->find();
		if (empty($caseReviewCheck)) {
			$this->error('复核审核信息不存在');
		}

		$Model->startTrans();

		// 更新审核表
		$data = array();
		$data['id'] = $id;
		$data['check_time'] = $time;
		$data['status'] = 1;
		$data['remark'] = $remark;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = $Model->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 标记复核审核通过
		$data = array();
		$data['id'] = $caseCheck['item_id'];
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$data['status'] = 1;
		$res = M('CaseReviewCheck')->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 标记复核已完成
		$data = array();
		$data['id'] = $caseReviewCheck['case_review_id'];
		$data['is_over'] = 1;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = M('CaseReview')->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 如果是撤销事故认定重做
		if ($caseReviewCheck['reviewer_result'] == 0) {
			// 更新事故认定表
			$map = array();
			$map['case_id'] = $caseCheck['case_id'];
			$caseCognizance = M('CaseCognizance')->where($map)->order('id desc')->find();
			if (empty($caseCognizance)) {
				$Model->rollback();
				$this->error('事故尚未认定');
			}
			$caseCognizance['check_status'] = 3;
			$caseCognizance['is_make'] = 0;
			$caseCognizance['update_time'] = $time;
			$caseCognizance['update_user_id'] = $userId;
			$res = M('CaseCognizance')->save($caseCognizance);
			if (!$res) {
				$Model->rollback();
				$this->error('数据更新失败');
			}

			// 更新调查报告
			$map = array();
			$map['case_cognizance_id'] = $caseCognizance['id'];
			$caseCognizanceReport = M('CaseCognizanceReport')->where($map)->order('create_time desc')->find();
			if (empty($caseCognizanceReport)) {
				$Model->rollback();
				$this->error('调查报告不存在');
			}
			$caseCognizanceReport['check_status'] = 3;
			$caseCognizanceReport['update_time'] = $time;
			$caseCognizanceReport['update_user_id'] = $userId;
			$res = M('CaseCognizanceReport')->save($caseCognizanceReport);
			if (!$res) {
				$Model->rollback();
				$this->error('数据更新失败');
			}

			// 更新案件完结信息
			$map = array();
			$map['id'] = $caseCheck['case_id'];
			$case = M('Case')->where($map)->find();
			if (empty($case)) {
				$Model->rollback();
				$this->error('案件信息不存在');
			}
			$case['update_time'] = $time;
			$case['update_user_id'] = $userId;
			$case['is_over'] = 0;
			$case['review_result_status'] = 2;
			$case['review_submit_status'] = 0;
			$case['cognizance_check_status'] = 5;
			$res = M('Case')->save($case);
			if (!$res) {
				$Model->rollback();
				$this->error('数据更新失败');
			}
			$msg = date('Y-m-d H:i', $time) . '出具' . $caseCheck['item_id'] . '道路交通事故认定复核结论，撤销原道路交通事故认定';
		} else {
			// 更新案件完结信息
			$map = array();
			$map['id'] = $caseCheck['case_id'];
			$case = M('Case')->where($map)->find();
			if (empty($case)) {
				$Model->rollback();
				$this->error('案件信息不存在');
			}
			$case['update_time'] = $time;
			$case['update_user_id'] = $userId;
			$case['review_result_status'] = 1;
			$case['review_submit_status'] = 0;
			$res = M('Case')->save($case);
			if (!$res) {
				$Model->rollback();
				$this->error('数据更新失败');
			}

			$msg = date('Y-m-d H:i', $time) . '出具' . $caseCheck['item_id'] . '道路交通事故认定复核结论，维持原道路交通事故认定';

		}

		$this->saveCaseLog($caseCheck['case_id'], $msg);

		$Model->commit();
		$this->success('提交成功');
	}

	/**
	 * 提交到下级审批
	 */
	public function applyCheck() {

		$checkUserId = I('post.check_user_id', '', 'int');
		$id = I('post.id', '', 'int');
		$remark = I('post.remark', '', 'trim,htmlspecialchars');
		$time = time();
		$userId = $this->my['id'];

		if ($checkUserId === '') {
			$this->error('请选择审核人');
		}
		if ($id === '') {
			$this->error('非法操作');
		}

		$Model = M('CaseCheck');

		// 审核信息
		$map = array();
		$map['id'] = $id;
		$map['is_del'] = 0;
		$caseCheck = $Model->where($map)->find();
		if (empty($caseCheck)) {
			$this->error('非法操作');
		}

		$Model->startTrans();

		// 更新
		$data = array();
		$data['id'] = $id;
		$data['check_time'] = $time;
		$data['status'] = 1;
		$data['remark'] = $remark;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = $Model->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 新增
		$data = array();
		$data['case_id'] = $caseCheck['case_id'];
		$data['cate'] = $caseCheck['cate'];
		$data['item_id'] = $caseCheck['item_id'];
		$data['check_user_id'] = $checkUserId;
		$data['check_time'] = 0;
		$data['submit_user_id'] = $userId;
		$data['submit_time'] = $time;
		$data['status'] = 0;
		$data['remark'] = '';
		$data['pid'] = $caseCheck['id'];
		$data['level'] = 1;
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

		$Model->commit();
		$this->success('提请成功');
	}

	/**
	 * 审批信息
	 */
	public function table() {
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
			$map['status'] = array('neq', 0);
			$map['case_id'] = $caseReview['case_id'];
			$map['cate'] = 20;
			$map['item_id'] = $caseReviewCheck['id'];
			$caseCheck = D('CaseCheckView')->where($map)->order('check_time asc')->select();
		}
		$this->assign('caseCheck', $caseCheck);

		$this->display();

	}
}