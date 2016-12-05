<?php
namespace Admin\Model;
class CaseCheckupEntrustModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('case_id', 'require', '案件ID必须填写'),
		array('case_checkup_id', 'require', '鉴定检验编号必须填写'),
		//array('code', 'require', '委托书编号 必须填写'),
		array('from_department_id', 'require', '委托鉴定单位必须填写'),
		array('to_checkup_org_id', 'require', '鉴定机构编号必须填写'),
		array('to_checkup_org_name', 'require', '鉴定机构名称'),
		array('entrust_time', 'require', '委托时间 必须填写'),
		array('carry_name_1', 'require', '送检人姓名一 必须填写'),
		// array('carry_post_1', 'require', '送检人职务一 必须填写'),
		array('carry_idno_1', 'require', '送检人证件名称及号码一 必须填写'),
		array('carry_name_2', 'require', '送检人姓名二 必须填写'),
		// array('carry_post_2', 'require', '送检人职务二 必须填写'),
		array('carry_idno_2', 'require', '送检人证件名称及号码二 必须填写'),
		// array('carry_address', 'require', '送检人通讯地址 必须填写'),
		// array('carry_zip', 'is_zip', '请输入有效的邮编号码', 0, 'function'),
		// array('carry_tel', 'is_fax', '请输入有效的送检人 联系电话', 0, 'function'),
		// array('carry_fax', 'is_fax', '请输入有效的送检人 传真号码', 0, 'function'),
		array('case_name', 'require', '案件名称 必须填写'),
		array('case_code', 'require', '案件编号 必须填写'),
		//array('target_client_id', 'require', '被鉴定人姓名 必须选择'),
		// array('target_name', 'require', '被鉴定人姓名 必须选择'),
		// array('target_sex', 'require', '被鉴定人性别 必须填写'),
		// array('target_age', 'require', '被鉴定人年龄 必须填写'),
		// array('target_tel', 'require', '被鉴定人电话 必须填写'),
		// array('target_company', 'require', '被鉴定人单位 必须填写'),
		//array('target_address', 'require', '被鉴定人现住址 必须填写'),
		array('summary', 'require', '简要案情 必须填写'),
		array('old_result', 'require', '原鉴定情况 必须填写'),
		array('checkup_doc', 'require', '送检材料 必须填写'),
		array('remark', 'require', '委托鉴定单位的鉴定要求和诚信声明 必须填写'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('train', 'getTrain', 1, 'callback'),
		array('code', 'getCode', 1, 'callback'),
		array('is_first', 1),
		array('is_finish', 0),
		array('is_submit', 0),
		array('status', 0),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),

	);

	/**
	 * 获取当前部门排序值
	 */
	protected function getTrain() {
		$from_department_id = I('post.from_department_id', 0, 'int');
		$map = array();
		$year = date('Y', time());
		$yearNext = date('Y', time()) + 1;
		$map['from_department_id'] = $from_department_id;
		$map['create_time'] = array('egt', strtotime($year . "-01-01"));
		$map['create_time'] = array('lt', strtotime($yearNext . "-01-01"));
		$map['is_del'] = 0;
		$train = M('CaseCheckupEntrust')->where($map)->max('train');
		return intval($train) + 1;
	}

	protected function getCode() {
		$from_department_id = I('post.from_department_id', 0, 'int');
		$map = array();
		$year = date('Y', time());
		$yearNext = date('Y', time()) + 1;
		$map['from_department_id'] = $from_department_id;
		$map['create_time'] = array('egt', strtotime($year . "-01-01"));
		$map['create_time'] = array('lt', strtotime($yearNext . "-01-01"));
		$map['is_del'] = 0;
		$train = M('CaseCheckupEntrust')->where($map)->max('train');
		$train = intval($train) + 1;
		if ($train < 10000) {
			//委托书编号 样式：20160001（委托书数量小于10000）
			$code = $year * 10000 + $train;
		} else {
			//委托书编号 样式：201600001  （委托书数量大于10000）
			$code = $year * 100000 + $train;

		}

		return $code;
	}

}
?>