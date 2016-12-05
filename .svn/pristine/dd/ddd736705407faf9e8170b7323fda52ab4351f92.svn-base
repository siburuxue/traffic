<?php
namespace Admin\Model;

class CheckupOrgItemModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('name', 'require', '名称必须填写'),
        array('name', 'checkName', '名称已存在',0,'callback'),
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
        array('is_del', 0),
    );

    /**
     * 验证名字是否重复
     */
    protected function checkName(){
        $pid = I('post.pid', '', 'int');
        $name = I('post.name','','trim,htmlspecialchars');
        $map['pid'] = $pid;
        $map['name'] = $name;
        $map['is_del'] = 0;
        $unique = $this->where($map)->find();
        if(!empty($unique)){
            return false;
        }
        return true;
    }
    /**
     * 获取当前部门排序值
     */
    protected function getTrain() {
        $pid = I('post.pid', 0, 'int');
        $map = array();
        $map['pid'] = $pid;
        $map['is_del'] = 0;
        $train = M('CheckupOrgItem')->where($map)->max('train');
        return intval($train) + 1;
    }
}
?>