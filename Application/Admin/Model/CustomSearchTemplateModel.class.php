<?php
namespace Admin\Model;

class CustomSearchTemplateModel extends CommonModel {
	/**
	 * 自动验证
	 */
	protected $_validate = array(
		array('name', 'require', '模板名称必须填写'),
        array('content', 'require', '模板内容必须填写'),
        array('content','checkAccess','查询中不能包含可以改变数据库结构、数据状态的语句',0,'callback')
	);

	/**
	 * 自动完成
	 */
	protected $_auto = array(
		array('create_time', 'time', 1, 'function'),
		array('create_user_id', 'getMyUserId', 1, 'callback'),
		array('update_time', 'time', 3, 'function'),
		array('update_user_id', 'getMyUserId', 3, 'callback'),
		array('is_del', 0),
	);

    protected function checkAccess(){
        $content = I('post.content');
        $content = strtolower($content);
        if(strstr($content,'insert') || strstr($content,'update') || strstr($content,'delete') || strstr($content,'modify')
            || strstr($content,'alter') || strstr($content,'create') || strstr($content,'drop') || strstr($content,'truncate')){
            return false;
        }
        return true;
    }
}
?>