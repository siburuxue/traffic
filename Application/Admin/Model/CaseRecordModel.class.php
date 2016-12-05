<?php
namespace Admin\Model;

class CaseRecordModel extends CommonModel {
   /**
     * 自动验证
     */
    protected $_validate = array(
        array('name', 'is_require', "被[name]人姓名必须填写", 0, 'callback'),
        array('record_count', 'require', '[name]次数必须填写'),
        array('record_count', 'number', '[name]次数必须是数字',2),
        array('idno', 'check_type', '身份证件号码必须为18位',0,'callback'),
        array('start_time', 'require', '开始时间必须填写'),
        array('end_time', 'require', '结束时间必须填写'),
        array('place', 'require', "[name]地点必须填写"),
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

    /**
     * 判断被询问人姓名是否为空
     */
    protected function is_require(){
        if(strlen(I('post.name')) == 0 && strlen(I('post.witness_name','','trim')) == 0){
            return false;
        }
        return true;
    }

    /**
     * 判断身份证号是否是18位
     */
    protected function check_type(){
        if(I('post.id_type') == "1"){
            if(strlen(I('post.idno','','trim')) != 18){
                return false;
            }
            return true;
        }
        return true;
    }
}
?>