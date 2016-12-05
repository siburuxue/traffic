<?php
namespace Admin\Model;

class CaseClientModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('name', 'require', '姓名必须填写'),
        array('traffic_type', 'require', '请选择交通方式'),
        array('idno','checkCid','身份证件号格式不正确',2,'callback'),
        array('punish_money', 'is_money', '处罚金额必须数字',2,'function'),
        array('punish_score', 'is_money', '扣分必须是数字',2,'function'),
        array('punish_seize_time', 'number', '暂扣时长必须是数字',2),
        array('punish_detain_time', 'number', '拘留天数必须是数字',2),
        array('breath_val', 'number', '呼吸气检数值必须是数字',2),
    );

    /**
     * 验证身份证是否合法
     */
    protected function checkCid(){
        if(I('post.id_type') == '1'){
            return is_id_card(I('post.idno'));
        }
        return true;
    }

    /**
     * 自动完成
     */
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
        array('create_user_id', 'getMyUserId', 1, 'callback'),
        array('update_time', 'time', 3, 'function'),
        array('update_user_id', 'getMyUserId', 3, 'callback'),
    );
}
?>