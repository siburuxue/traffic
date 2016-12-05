<?php
namespace Admin\Controller;

/**
 * 复核科 复核结论下发
 */
class CaseReviewCheckNoticeHandleInfoController extends CommonController {

	/**
	 * 结论下发
	 */
	public function index() {
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

		if ($caseReview['check_status'] != 1) {
			$this->error('复核尚未进入审批流程');
		}

		// 审批信息
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$map['case_review_id'] = $caseReview['id'];
		$caseReviewCheck = M('CaseReviewCheck')->where($map)->find();
		if (empty($caseReviewCheck)) {
			$this->error('审批信息不存在');
		}
		$this->assign('caseReviewCheck', $caseReviewCheck);

		if ($caseReviewCheck['status'] != 1) {
			$this->error('复核结论未通过审批');
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
	 * 更新结论下发
	 */
	public function noticeUpdate() {

		$id = I('post.id', 0, 'int');
		if ($id == 0) {
			unset($_POST['id']);
		}

		$Model = D('CaseReviewCheckNotice');

		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}

		$data['post_time'] = strtotime($data['post_time']);
		$data['target_user_id'] = $data['case_client_relater_id'];
		if ($data['case_client_relater_id'] == 0) {
			$map = array();
			$map['id'] = $data['case_client_id'];
			$data['target_user_name'] = M('CaseClient')->where($map)->getField('name');

		} else {
			$map = array();
			$map['id'] = $data['case_client_relater_id'];
			$data['target_user_name'] = M('CaseClientRelater')->where($map)->getField('name');
		}

		if (isset($data['id'])) {
			$res = $Model->save($data);
		} else {
			$res = $Model->add($data);
		}

		if (!$res) {
			$this->error('数据更新失败');
		}

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
		// 复核结论编号
		$caseReviewCheckId = I('get.case_review_check_id', '', 'int');

		if ($caseReviewId === '' || $caseClientId === '' || $caseReviewCheckId === '') {
			$this->error('非法操作');
		}

		// 复核信息
		$map = array();
		$map['id'] = $caseReviewId;
		$caseReview = M('CaseReview')->where($map)->find();
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}

		// 复核结论
		$map = array();
		$map['id'] = $caseReviewCheckId;
		$caseReviewCheck = M('CaseReviewCheck')->where($map)->find();
		if (empty($caseReviewCheck)) {
			$this->error('复核结论不存在');
		}

		// 已保存的送达回执
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$map['case_review_id'] = $caseReviewId;
		$map['case_review_check_id'] = $caseReviewCheckId;
		$map['case_client_id'] = $caseClientId;
		$caseReviewCheckNotice = M('CaseReviewCheckNotice')->where($map)->order('create_time desc')->find();

		if (empty($caseReviewCheckNotice)) {
			$caseReviewCheckNotice = array();
		} else {
			$caseReviewCheckNotice['post_time'] = date('Y-m-d H:i', $caseReviewCheckNotice['post_time']);
		}

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
			'notice' => $caseReviewCheckNotice,
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
