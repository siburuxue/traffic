<?php
namespace Admin\Model;

class CaseClientRelaterModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('name', 'require', '相关人姓名必须填写'),
		array('age', 'require', '相关人年龄必须填写'),
		array('age', 'number', '相关人年龄必须是数字', 0),
		array('idno', 'require', '相关人身份证号必须填写'),
		array('idno', 'is_id_card', '请正确输入身份证号码', 0, 'function'),
		array('idno', 'is_unique', '相关人身份证号已存在', 0, 'callback'),
		array('tel', 'require', '相关人联系方式必须填写'),
		array('relation', 'require', '相关人与当事人关系必须填写'),
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

	/**
	 * 判断身份证号是否重复
	 */
	protected function is_unique() {
		$relaterList = I('post.client_relaters');
		$i = I('post.i', '', 'int');
		$map = array();
		$map['case_client_id'] = I('post.id', '', 'int');
		$map['idno'] = $relaterList[$i]['idno'];
		$map['id'] = array('neq', $relaterList[$i]['id']);
		$rs = $this->where($map)->find();
		if (!empty($rs)) {
			return false;
		}
		return true;
	}
}
?>