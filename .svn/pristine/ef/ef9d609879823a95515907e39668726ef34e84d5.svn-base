<?php
namespace Admin\Model;

class CaseSurveyModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('start_time', 'require', "现场勘查开始时间必须填写"),
        array('end_time', 'require', "现场勘查结束时间必须填写"),
        array('first_user_id', 'require', "请选择勘查人1"),
        array('second_user_id', 'require', "请选择勘查人2"),
        array('first_user_id', 'check_user', '第一勘查人与第二勘查人不能重复',0,'callback'),
        array('scene_state', 'require', "请输入现场形态"),
    );

    /**
     * 勘察人不能重复
     */
    protected function check_user(){
        return I('post.first_user_id','','int') != I('post.second_user_id','','int');
    }
}
?>