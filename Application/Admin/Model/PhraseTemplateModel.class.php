<?php
namespace Admin\Model;

class PhraseTemplateModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('cate', 'require', '模板分类必须填写'),
		array('content', 'require', '模板内容必须填写'),
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('create_time', 'time', 1, 'function'),
		array('train', 'getTrain', 1, 'callback'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);
	/**
	 * 获取当前模板排序值
	 */
	protected function getTrain() {
		$pid = I('post.pid', 0, 'int');
		$map = array();
		$map['pid'] = $pid;
		$map['is_del'] = 0;
		$train = M('PhraseTemplate')->where($map)->max('train');
		return intval($train) + 1;
	}
}
?>