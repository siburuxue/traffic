<?php
namespace Admin\Controller;

/**
 * 复核科 - 复核申请 - 受理/不受理
 */
class CaseReviewAcceptHandleController extends CommonController {

	/**
	 * 受理
	 */
	public function accept() {
		// 复核信息编号
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
		if ($caseReview['apply_status'] == 0) {
			$this->error('尚未提交复核申请');
		}
		if ($caseReview['accept_status'] == 1) {
			$this->error('复核申请已受理');
		} elseif ($caseReview['accept_status'] == 2) {
			$this->error('复核申请已拒绝受理');
		}

		$Model = M('CaseReview');

		$Model->startTrans();

		$time = time();
		$userId = $this->my['id'];

		// 更新复核主表
		$data = array();
		$data['id'] = $caseReviewId;
		$data['accept_status'] = 1;
		$data['accept_time'] = $time;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = $Model->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		// 更新案件主表
		$data = array();
		$data['id'] = $caseReview['case_id'];
		$data['review_accept_status'] = 1;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = M('Case')->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$this->saveCaseLog($caseReview['case_id'], date('Y-m-d H:i', $time) . '复核申请予以受理');

		$Model->commit();
		$this->success('操作成功');

	}

	/**
	 * 受理消息通知
	 */
	public function acceptNotice() {
		// 默认当前当事人编号
		$nowCaseClientId = I('get.case_client_id', 0, 'int');
		$this->assign('nowCaseClientId', $nowCaseClientId);

		// 复核信息编号
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
		if ($caseReview['accept_status'] != 1) {
			$this->error('复核尚未受理');
		}
		$this->assign('caseReview', $caseReview);

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
	 * 更新受理消息
	 */
	public function updateAcceptNotice() {

		$id = I('post.id', 0, 'int');
		if ($id == 0) {
			unset($_POST['id']);
		}

		$Model = D('CaseReviewNotice');
		$data = $Model->create();
		if (false === $data) {
			$this->error($Model->getError());
		}
		$data['action_time'] = strtotime($data['action_time']);

		if (isset($data['id'])) {
			$res = $Model->save($data);
		} else {
			$res = $Model->add($data);
		}

		if (!$res) {
			$this->error('数据更新失败');
		}

		$this->success('更新成功');
	}

	/**
	 * 获取接受申请人列表
	 */
	public function getAcceptApplyer() {
		$applyer = array();
		// 复核编号
		$caseReviewId = I('get.case_review_id', '', 'int');
		// 当事人编号
		$caseClientId = I('get.case_client_id', '', 'int');

		if ($caseReviewId === '' || $caseClientId === '') {
			$this->error('非法操作');
		}

		// 申请人 当事人
		$map = array();
		$map['id'] = $caseClientId;
		$map['is_del'] = 0;
		$caseClient = D('CaseClientView')->where($map)->find();
		if (empty($caseClient)) {
			$this->error('当事人不存在');
		}

		// 当事人在复核受理消息表的数据
		$map = array();
		$map['case_id'] = $caseClient['case_id'];
		$map['case_review_id'] = $caseReviewId;
		$map['cate'] = 1;
		$map['case_client_id'] = $caseClientId;
		$caseClientCaseReviewNotice = M('CaseReviewNotice')->where($map)->order('create_time desc')->find();
		if (empty($caseClientCaseReviewNotice)) {
			$caseClientCaseReviewNotice = array();
		} else {
			$caseClientCaseReviewNotice['action_time'] = date('Y-m-d H:i', $caseClientCaseReviewNotice['action_time']);
		}

		$item = array();
		$item['id'] = 0;
		$item['name'] = $caseClient['name'];
		$item['relation'] = '本人';
		$item['contact'] = $caseClient['tel'];
		$applyer[] = $item;

		// 申请人 当事人相关人
		$map = array();
		$map['case_client_id'] = $caseClientId;
		$map['case_client_is_del'] = 0;
		$caseClientRelater = D('CaseClientRelaterView')->where($map)->select();

		foreach ($caseClientRelater as $relater) {
			$item = array();
			$item['id'] = $relater['id'];
			$item['name'] = $relater['name'];
			$item['relation'] = get_custom_config('client_relation.' . $relater['relation']);
			$item['contact'] = $relater['tel'];
			$applyer[] = $item;
		}

		$this->success(array(
			'notice' => $caseClientCaseReviewNotice,
			'applyer' => $applyer,
		));

	}

	/**
	 * 选择发送短信
	 */
	public function smsAccept() {
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
		// dump($caseClient);
		$this->assign('caseClient', $caseClient);

		$this->assign('documentIssueType', get_custom_config('document_issue_type'));

		$this->display();
	}

	/**
	 * 执行发送
	 */
	public function sendSmsAccept() {

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
		}
		$Model = M('CaseReviewSms');

		$res = $Model->addAll($data);
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

	/**
	 * 不受理
	 */
	public function refuse() {
		// 复核信息编号
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
		if ($caseReview['apply_status'] == 0) {
			$this->error('尚未提交复核申请');
		}
		if ($caseReview['accept_status'] == 1) {
			$this->error('复核申请已受理');
		} elseif ($caseReview['accept_status'] == 2) {
			$this->error('复核申请已拒绝受理');
		}

		$time = time();
		$userId = $this->my['id'];

		$Model = M('CaseReview');

		$Model->startTrans();

		$data['id'] = $caseReviewId;
		$data['accept_status'] = 2;
		$data['accept_time'] = $time;
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
		$data['review_accept_status'] = 2;
		$data['review_result_status'] = 1;
		$data['review_submit_status'] = 0;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$res = M('Case')->save($data);
		if (!$res) {
			$Model->rollback();
			$this->error('数据更新失败');
		}

		$this->saveCaseLog($caseReview['case_id'], date('Y-m-d H:i', $time) . '复核申请不予受理');

		$Model->commit();
		$this->success('操作成功');

	}

	/**
	 * 受理消息通知
	 */
	public function refuseNotice() {
		// 默认当前当事人编号
		$nowCaseReviewApplyId = I('get.case_review_apply_id', 0, 'int');
		$this->assign('nowCaseReviewApplyId', $nowCaseReviewApplyId);

		// 复核信息编号
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
		if ($caseReview['accept_status'] != 2) {
			$this->error('复核尚未拒绝受理');
		}
		$this->assign('caseReview', $caseReview);

		// 申请人列表
		$map = array();
		$map['case_review_id'] = $caseReviewId;
		$caseReviewApply = M('caseReviewApply')->where($map)->select();
		if (empty($caseReviewApply)) {
			$this->error('尚无复核申请人');
		}
		$this->assign('caseReviewApply', $caseReviewApply);

		// 与当事人关系
		$this->assign('clientRelation', get_custom_config('client_relation'));

		$this->display();
	}

	/**
	 * 保存不予受理通知
	 */
	public function updateRefuseNotice() {
		$id = I('post.id', 0, 'int');
		$caseReviewApplyId = I('post.case_review_apply_id', '', 'int');
		$accidentName = I('post.accident_name', '', 'trim,htmlspecialchars');
		$content = I('post.content', '', 'trim,htmlspecialchars');
		if ($caseReviewApplyId === '') {
			$this->error('非法操作');
		}
		if ($accidentName === '') {
			$this->error('事故名称必须填写');
		}
		if ($content === '') {
			$this->error('不予受理缘由必须填写');
		}

		// 复核申请
		$caseReviewApply = D('CaseReviewApplyView')->getById($caseReviewApplyId);
		if (empty($caseReviewApply)) {
			$this->error('申请不存在');
		}

		// 复核信息
		$caseReview = M('CaseReview')->getById($caseReviewApply['case_review_id']);
		if (empty($caseReview)) {
			$this->error('复核信息不存在');
		}
		if ($caseReview['apply_status'] == 0) {
			$this->error('尚未提交复核申请');
		}
		if ($caseReview['accept_status'] != 2) {
			$this->error('复核申请未拒绝受理');
		}
		if ($caseReview['stop_status'] == 1) {
			$this->error('复核已终止');
		}
		if ($caseReview['check_status'] != 0) {
			$this->error('复核流程已进入审核过程');
		}

		$Model = M('CaseReviewNotice');
		$time = time();
		$userId = $this->my['id'];
		$data = array();
		$id && $data['id'] = $id;
		$data['case_id'] = $caseReviewApply['case_id'];
		$data['case_review_id'] = $caseReviewApply['case_review_id'];
		$data['cate'] = 0;
		$data['action_time'] = $caseReview['accept_time'];
		$data['case_client_id'] = $caseReviewApply['case_client_id'];
		$data['case_client_name'] = $caseReviewApply['case_client_name'];
		$data['case_client_relater_id'] = $caseReviewApply['case_client_relater_id'];
		$data['case_client_relater_name'] = $caseReviewApply['case_client_relater_id'] == 0 ? $caseReviewApply['case_client_name'] : $caseReviewApply['case_client_relater_name'];
		$data['relation'] = $caseReviewApply['relation'];
		$data['contact'] = $caseReviewApply['case_client_relater_id'] == 0 ? $caseReviewApply['case_client_tel'] : $caseReviewApply['case_client_relater_tel'];
		$data['accident_name'] = $accidentName;
		$data['content'] = $content;
		$data['create_time'] = $time;
		$data['create_user_id'] = $userId;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;

		if ($id) {
			$res = $Model->save($data);
		} else {
			$res = $Model->add($data);
		}

		if ($res) {
			$this->success('更新成功');
		} else {
			$this->error('数据更新失败');
		}

	}

	/**
	 * 获取不接受申请人列表
	 */
	public function getRefuseApplyer() {
		$applyer = array();

		// 申请记录
		$caseReviewApplyId = I('get.case_review_apply_id', '', 'int');

		if ($caseReviewApplyId === '') {
			$this->error('非法操作');
		}

		// 申请人
		$map = array();
		$map['id'] = $caseReviewApplyId;
		$caseReviewApply = D('CaseReviewApplyView')->where($map)->find();
		if (empty($caseReviewApply)) {
			$this->error('申请人不存在');
		}

		// 当事人在复核受理消息表的数据
		$map = array();
		$map['case_id'] = $caseReviewApply['case_id'];
		$map['case_review_id'] = $caseReviewApply['case_review_id'];
		$map['cate'] = 0;
		$map['case_client_id'] = $caseReviewApply['case_client_id'];
		$map['case_client_relater_id'] = $caseReviewApply['case_client_relater_id'];
		$CaseReviewNotice = M('CaseReviewNotice')->where($map)->order('create_time desc')->find();
		if (empty($CaseReviewNotice)) {
			$CaseReviewNotice = array();
		} else {
			$CaseReviewNotice['action_time'] = date('Y-m-d H:i', $CaseReviewNotice['action_time']);
		}

		$this->success(array(
			'notice' => $CaseReviewNotice,
			'applyer' => $caseReviewApply,
		));
	}

	/**
	 * 选择发送短信
	 */
	public function smsRefuse() {
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

		// 申请人
		$map = array();
		$map['case_review_id'] = $caseReviewId;
		$caseClientIds = D('CaseReviewApply')->where($map)->getField('case_client_id', true);
		if (empty($caseClientIds)) {
			$this->error('申请人不存在');
		}

		// 当事人
		$map = array();
		$map['id'] = array('in', $caseClientIds);
		$map['is_del'] = 0;
		$caseClient = D('CaseClientView')->where($map)->select();
		if (empty($caseClient)) {
			$this->error('当事人不存在');
		}

		// 当事人相关人
		$map = array();
		$map['case_id'] = $caseReview['case_id'];
		$map['case_client_id'] = array('in', $caseClientIds);
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
	public function sendSmsRefuse() {

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
		}
		$Model = M('CaseReviewSms');

		$res = $Model->addAll($data);
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