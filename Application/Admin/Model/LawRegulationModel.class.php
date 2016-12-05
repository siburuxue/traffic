<?php
namespace Admin\Model;

class LawRegulationModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('title', 'require', '文件夹名须填写'),
        array('title', 'checkFolder', '该文件夹下文件夹名已存在', 0, 'callback'),
    );

    /**
     * 自动完成
     */
    protected $_auto = array(
        array('level', 'getLevel', 1, 'callback'),
        array('create_time', 'time', 1, 'function'),
        array('create_user_id', 'getMyUserId', 1, 'callback'),
        array('update_time', 'time', 3, 'function'),
        array('update_user_id', 'getMyUserId', 3, 'callback'),
        array('is_del', 0),
    );

    /**
     * 验证文件夹名
     */
    protected function checkFolder() {
        $pid = I('post.pid',0,'int');
        $title = I('post.title');
        $map = array();
        $map['pid'] = $pid;
        $map['title'] = $title;
        $result = $this->where($map)->select();
        return count($result) === 0;
    }

    protected function getLevel(){
        $info = $this->getById(I('post.pid'));
        if(empty($info)){
            return 0;
        }else{
            return (int)$info['level'] + 1;
        }
    }
}
?>