<?php
namespace Admin\Model;

class CaseCognizanceSimpleModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('info', 'require', '交通事故事实及责任必须输入'),
        array('compensate', 'require', '损害赔偿必须输入'),
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
}
?>