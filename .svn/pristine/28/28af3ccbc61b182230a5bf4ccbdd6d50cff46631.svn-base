<?php
namespace Admin\Model;

class CaseReviewCheckModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'number', '非法操作'),
		array('case_review_id', 'number', '非法操作'),
		array('reviewer_name', 'require', '复核人姓名不能为空'),
		array('reviewer_sex', array('男', '女'), '请正确选择复核人性别', 0, 'in'),
		array('reviewer_idno', 'is_id_card', '请正确输入复核人身份证号码', 0, 'function'),
		array('review_type', 'require', '复核方式不能为空'),
		array('review_time', 'is_time', '请正确选择受理时间', 0, 'function'),
		array('police', 'require', '被复核部门民警不能为空'),
		array('reason', 'require', '复核理由不能为空'),
		array('old_opinion', 'require', '原办案单位处理意见不能为空'),
		array('reviewer_result', 'number', '请正确选择复核人意见'),
		array('reviewer_opinion', 'require', '复核人意见不能为空'),
		array('result_info', 'require', '复核人基本情况不能为空'),
		array('result_reason', 'require', '申请的基本事实、理由不能为空'),
		array('result_content', 'require', '经本机关复核认为不能为空'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('status', 0),
	);

}
?>