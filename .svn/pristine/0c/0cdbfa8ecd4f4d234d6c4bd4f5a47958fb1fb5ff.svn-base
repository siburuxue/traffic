<?php
namespace Admin\Model;

class CaseClientDetainModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('code', 'require', '物品编号必须填写'),
        array('name_id',"check_require",'物品名称必须填写',0,'callback'),
        array('format', 'require', '规格必须填写'),
        array('num', 'require', '数量必须填写'),
        array('num', 'number', '数量必须是数字',0),
        array('owner', 'require', '所有人必须填写'),
        array('contractor', 'require', '承办人必须填写'),
        array('detain_time', 'require', '扣押日期必须填写'),
    );

    /**
     * 自动完成
     */
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('create_user_id', 'getMyUserId', 1, 'callback'),
        array('update_time', 'time', 3, 'function'),
        array('update_user_id', 'getMyUserId', 3, 'callback'),
        array('status',0),
    );

    //物品名称必须入力
    protected function check_require(){
        if(I('post.name_id','','trim') == ""){
            return false;
        }else if(I('post.name_id','','trim') == 3 && I('post.name','','trim') == ""){
            return false;
        }
        return true;
    }
}
?>