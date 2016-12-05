<?php
namespace Admin\Model;

class CaseClientLawModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('law_pid', 'require', '违法行为及条款（大类）必须填写'),
		array('law_id', 'require', '违法行为及条款（小类）必须填写'),

	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('train', 'getTrain', 1, 'callback'),
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
	);

	protected function getTrain() {
		$client_id = I('post.client_id');
		//error_log($client_id,3,"a.log");
		$map = array();
		$map['case_client_id'] = $client_id;
		$rs = $this->where($map)->max('train');
		return intval($rs) + 1;
	}
}
?>