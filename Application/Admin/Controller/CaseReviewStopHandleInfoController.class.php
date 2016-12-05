<?php
namespace Admin\Controller;

/**
 * 复核 终止
 */
class CaseReviewStopHandleInfoController extends CommonController {

	/**
	 * 执行终止
	 * 执行终止必要条件：已受理，未进入审批，未终止
	 */
	public function index() {

		$time = time();
		$userId = $this->my['id'];
		// 复核编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		$Model = M('CaseReview');

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = $Model->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}

		if ($caseReview['accept_status'] != 1) {
			$this->error('复核不是受理状态');
		}

		if ($caseReview['check_status'] != 0) {
			$this->error('复核已进入审批流程');
		}

		if ($caseReview['stop_status'] == 1) {
			$this->error('复核已终止');
		}

		// 开启事务
		$Model->startTrans();

		// 更新状态
		$data = array();
		$data['id'] = $caseReviewId;
		$data['stop_status'] = 1;
		$data['stop_time'] = $time;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$data['is_over'] = 1;
		$res = $Model->save($data);

		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 更新案件主表
		$data = array();
		$data['id'] = $caseReview['case_id'];
		$data['review_result_status'] = 1;
		$data['review_submit_status'] = 0;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = M('Case')->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$Model->commit();
		$this->success('终止成功');

	}

	/**
	 * 终止通知
	 * 通知必要条件：复核已终止
	 */
	public function notice() {

		// 默认选中的当事人编号
		$nowCaseClientId = I('get.case_client_id', 0, 'int');
		$this->assign('nowCaseClientId', $nowCaseClientId);

		// 复核信息
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
		$this->assign('caseReview', $caseReview);

		if ($caseReview['stop_status'] != 1) {
			$this->error('复核尚未终止');
		}

		// 当事人列表
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$map['is_del'] = 0;
		$caseClient = M('CaseClient')->where($map)->select();
		if (empty($caseClient)) {
			$this->error('尚无当事人');
		}
		$this->assign('caseClient', $caseClient);

		// 与当事人关系
		$this->assign('clientRelation', get_custom_config('client_relation'));

		$this->display();

	}

	/**
	 * 更新终止通知
	 */
	public function noticeUpdate() {
		$id = I('post.id', 0, 'int');
		if ($id == 0) {
			unset($_POST['id']);
		}

		$Model = D('CaseReviewStop');

		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$temp = array();
		if (isset($data['id'])) {
			$res = $Model->save($data);
			$temp = M('CaseReviewStop')->getById($data['id']);
		} else {
			$res = $Model->add($data);
			$temp = M('CaseReviewStop')->getById($res);
		}

		$case = M('Case')->getById($temp['case_id']);
		$caseReview = M('CaseReview')->getById($temp['case_review_id']);
		$caseClient = M('CaseClient')->getById($temp['case_client_id']);

		if (!$res) {
			$this->error('数据更新失败');
		}
		$this->saveCaseLog($case['id'], date('Y-m-d H:i', $caseReview['stop_time']) . '对' . $case['code'] . '予以终止处理，并通知' . $caseClient['name']);

		$this->success('保存成功');
	}

	/**
	 * 获取申请人列表
	 */
	public function getApplyer() {

		// 复核编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		// 当事人编号
		$caseClientId = I('get.case_client_id', '', 'int');

		if ($caseReviewId === '' || $caseClientId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = M('CaseReview')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}

		// 已保存的终止下发
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$map['case_review_id'] = $caseReviewId;
		$map['case_client_id'] = $caseClientId;
		$caseReviewStop = M('CaseReviewStop')->where($map)->order('create_time desc')->find();
		$caseReviewStop = empty($caseReviewStop) ? array() : $caseReviewStop;

		// 可下发人员
		$applyer = array();

		// 当事人
		$map = array();
		$map['id'] = $caseClientId;
		$map['is_del'] = 0;
		$caseClient = D('CaseClientView')->where($map)->find();
		if (empty($caseClient)) {
			$this->error('当事人不存在');
		}

		$item = array();
		$item['id'] = 0;
		$item['name'] = $caseClient['name'];
		$applyer[] = $item;

		// 当事人相关人
		$map = array();
		$map['case_client_id'] = $caseClientId;
		$map['case_client_is_del'] = 0;
		$caseClientRelater = D('CaseClientRelaterView')->where($map)->select();

		foreach ($caseClientRelater as $relater) {
			$item = array();
			$item['id'] = $relater['id'];
			$item['name'] = $relater['name'];
			$applyer[] = $item;
		}

		// 返回结果
		$this->success(array(
			'notice' => $caseReviewStop,
			'applyer' => $applyer,
		));

	}

	/**
	 * 短信通知
	 */
	public function sms() {
		$caseReviewId = I('get.case_review_id', '', 'int');
		if ($caseReviewId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = M('CaseReview')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('非法操作');
		}
		$this->assign('caseReview', $caseReview);

		// 当事人
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$map['is_del'] = 0;
		$caseClient = D('CaseClientView')->where($map)->select();
		if (empty($caseClient)) {
			$this->error('当事人不存在');
		}

		// 当事人相关人
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$map['case_client_is_del'] = 0;
		$caseClientRelater = D('CaseClientRelaterView')->where($map)->select();

		foreach ($caseClient as $key => &$value) {
			$value['relater'] = list_search($caseClientRelater, 'case_client_id=' . $value['id']);
		}
		unset($value);

		$this->assign('caseClient', $caseClient);

		$this->assign('documentIssueType', get_custom_config('document_issue_type'));

		$this->display();
	}

	/**
	 * 执行发送
	 */
	public function sentSms() {

		$receiver = I('post.receiver');
		$msgType = I('post.msg_type', '', 'int');
		$msgContent = I('post.msg_content', '', 'trim,htmlspecialchars');
		$caseId = I('post.case_id', '', 'int');
		$caseReviewId = I('post.case_review_id', '', 'int');
		$cate = I('post.cate', '', 'int');
		$time = time();
		$userId = $this->my['id'];

		if (empty($receiver)) {
			$this->error('请选择告知对象');
		}
		if ($msgContent === '') {
			$this->error('告知内容必须填写');
		}

		$address = array();
		$data = array();
		foreach ($receiver as $key => $value) {
			$item = array();
			$item['case_id'] = $caseId;
			$item['case_review_id'] = $caseReviewId;
			$item['cate'] = $cate;
			$item['case_client_id'] = $value['case_client_id'];
			$item['case_client_relater_id'] = $value['case_client_relater_id'];
			$item['msg_mobile'] = $value['msg_mobile'];
			$item['msg_name'] = $value['msg_name'];
			$item['msg_type'] = $msgType;
			$item['msg_content'] = $msgContent;
			$item['create_time'] = $time;
			$item['create_user_id'] = $userId;
			$item['update_time'] = $time;
			$item['update_user_id'] = $userId;
			$data[] = $item;
			$address[] = $value['msg_mobile'];
		}

		$res = M('CaseReviewSms')->addAll($data);
		if (!$res) {
			$this->error('数据保存失败');
		}

		$res = $this->sendSms($address, $msgContent);

		if (true === $res) {
			$this->success('短信发送成功');
		} else {
			$this->error($res);
		}
	}

}