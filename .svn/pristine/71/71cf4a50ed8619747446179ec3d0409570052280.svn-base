<?php
namespace Admin\Model;

class CaseReviewNoticeModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'number', '案件编号错误'),
		array('case_review_id', 'number', '案件复核编号错误'),
		array('cate', array('0', '1'), '类型错误', 0, 'in'),
		array('action_time', 'is_time', '请正确输入受理/不受理时间', 0, 'function'),
		array('case_client_id', 'number', '案件当事人编号错误'),
		array('case_client_name', 'require', '案件当事人不能为空'),
		array('case_client_relater_id', 'number', '请选择申请人'),
		array('case_client_relater_name', 'require', '申请人不能为空'),
		array('relation', 'require', '与当事人关系不能为空'),
		array('accident_name', 'require', '事故名称不能为空'),
		array('content', 'require', '不予受理缘由不能为空'),
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

}
?>