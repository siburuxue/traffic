<?php
namespace Admin\Model;

class CaseMediateApplyModel extends CommonModel {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('case_client_id', 'require', '请选择调解申请人'),
        array('case_client_id', 'checkUnique', '申请人已存在不能重复申请',0,'callback'),
        array('case_client_id', 'checkEnable', '申请人没有调解结果不能重复申请',0,'callback'),
        array('apply_time', 'require', '请选择申请时间'),
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

    protected function checkUnique(){
        $map = array();
        $map['case_id'] = I('post.case_id');
        $map['case_client_id'] = I('post.case_client_id');
        $map['apply_status'] = 0;
        $info = $this->where($map)->find();
        return empty($info);
    }

    protected function checkEnable(){
        $case_id = I('post.case_id');
        $case_client_id = I('post.case_client_id');
        $map = array();
        $map['case_id'] = $case_id;
        $map['case_client_id'] = $case_client_id;
        $map['apply_status'] = 0;
        $existsRs = M('CaseMediateApply')->where($map)->select();
        if(count($existsRs) > 0){
            return false;
        }
        $map = array();
        $map['case_id'] = $case_id;
        $map['case_client_id'] = $case_client_id;
        $map['apply_status'] = 2;
        $applyRs = M('CaseMediateApply')->where($map)->select();
        foreach ($applyRs as $key => $val){
            //获取已调解或已终结数据
            $map = array();
            $map['id'] = $val['case_mediate_accept_id'];
            $map['status'] = array('neq',0);
            $acceptRs = M('CaseMediateAccept')->where($map)->select();
            //获取调解通知数据
            $map = array();
            $map['case_mediate_accept_id'] = $val['case_mediate_accept_id'];
            $map['case_id'] = $case_id;
            $noticeRs = M('CaseMediateNotice')->where($map)->select();
            //如果（已经调解或者已经终结）或者（已经有调解通知记录）则可以新增相同的数据否则不能新增
            if(count($acceptRs) == 0 && count($noticeRs) == 0){
                return false;
            }
        }
        return true;
    }
}
?>