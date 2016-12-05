<?php
namespace Admin\Model;

class CaseReviewApplyModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_review_id', 'number', '复核编号错误'),
		array('case_client_id', 'number', '请正确选择申请人'),
		array('case_client_relater_id', 'number', '请正确选择申请人'),
		array('case_client_relater_id', 'uniqueCaseClientRelaterId', '申请人已存在', 1, 'callback', 1),
		array('sex', array('0', '1'), '请正确选择性别', 0, 'in'),
		array('idno', 'require', '请正确输入身份证号码'),
		// array('idno', 'is_id_card', '请正确输入身份证号码', 0, 'function'),
		array('date_of_birth', 'is_date', '请正确输入出生日期', 0, 'function'),
		array('traffic_type', 'number', '请正确选择交通方式', 2),
		array('relation', 'require', '与当事人关系不能为空'),
		array('content', 'require', '申请理由及事实不能为空'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
	);

	protected function uniqueCaseClientRelaterId() {
		$caseReviewId = I('post.case_review_id', '', 'int');
		$caseClientId = I('post.case_client_id', '', 'int');
		$caseClientRelaterId = I('post.case_client_relater_id', '', 'int');
		if ($caseReviewId === '' || $caseClientId === '' || $caseClientRelaterId === '') {
			return false;
		}
		$map = array();
		$map['case_review_id'] = $caseReviewId;
		$map['case_client_id'] = $caseClientId;
		$map['case_client_relater_id'] = $caseClientRelaterId;
		$unique = $this->where($map)->find();
		return empty($unique);
	}

}
?>