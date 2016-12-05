<?php
namespace Admin\Model;

class CheckupOrgModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('name', 'require', '机构名称必须填写'),
        array('name', 'checkName', '机构名称已存在',0,'callback'),
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
        array('status', 1),
    );

    /**
     * 验证名字是否重复
     */
    protected function checkName(){
        $name = I('post.name','','trim,htmlspecialchars');
        $id = I('post.id');
        $map['name'] = $name;
        $map['is_del'] = 0;
        if(!empty($id)){
            $map['id'] = array('neq',$id);
        }
        $unique = $this->where($map)->find();
        if(!empty($unique)){
            return false;
        }
        return true;
    }
}
?>