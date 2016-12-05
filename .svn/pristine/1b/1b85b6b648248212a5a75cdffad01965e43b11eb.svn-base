<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 调节
 */
class CaseMediateHandleInfoController extends CaseDetailController {

    /**
     * 调解申请画面加载
     */
    public function index(){
        $map = array();
        $map['case_id'] = $this->case['id'];
        $map['traffic_type'] = array('neq',8);
        $clientList = M('CaseClient')->field('id,name,idno')->where($map)->select();
        $this->assign('clientList',$clientList);
        $this->assign('now',date('Y-m-d H:i'));
        $this->display();
    }

    /**
     * 调解申请列表
     */
    public function applyTable(){
        $map = array();
        $map['case_id'] = $this->case['id'];
        $model = D('CaseMediateApplyView');
//        $count = $model->count('CaseMediateApply.id');
//        $page = new Page($count, 15);
//        $list = $model->order('CaseMediateApply.case_mediate_accept_id,CaseMediateApply.id desc')->limit($page->firstrow . ',' . $page->rows)->select();
        $list = $model->where($map)->order('CaseMediateApply.case_mediate_accept_id,CaseMediateApply.id desc')->select();
        foreach($list as $key => $val){
            $photoList = parent::getPhotoList(53,$val['id']);
            $list[$key]['num'] = count($photoList);
            if($val['user_type'] == '1'){
                $relaterInfo = M('CaseClientRelater')->getById($val['case_client_id']);
                $list[$key]['name'] = $relaterInfo['name'];
            }
        }
        $this->assign('list', $list);
        // 分页信息
//        $pageInfo = array(
//            'totalrows' => $count,
//            'totalpage' => $page->totalpages,
//            'nowpage' => $page->nowpage,
//        );
//        $this->assign('page', $pageInfo);
        $this->display('CaseMediateHandleInfo/index/applyTable');
    }

    /**
     * 调解列表
     */
    public function mediateTable(){
        $map = array();
        $map['case_id'] = $this->case['id'];
        $status = array('未调解','已调解','已终结');
        $model = D('CaseMediateAcceptView');
//        $count = $model->count('CaseMediateAccept.id');
//        $page = new Page($count, 15);
//        $list = $model->order('CaseMediateAccept.id desc')->limit($page->firstrow . ',' . $page->rows)->select();
        $list = $model->where($map)->order('CaseMediateAccept.id desc')->select();
        foreach($list as $key => $val){
            $map = array();
            $map['case_mediate_accept_id'] = $val['id'];
            $CaseMediateAcceptRs = M('CaseMediateApply')->where($map)->select();
            $array = array();
            foreach($CaseMediateAcceptRs as $k => $v){
                if($v['user_type'] == '0'){
                    $clientRs = M('CaseClient')->getById($v['case_client_id']);
                }else{
                    $clientRs = M('CaseClientRelater')->getById($v['case_client_id']);
                }
                array_push($array,$clientRs['name']);
            }
            $list[$key]['names'] = implode(',',$array);
            $list[$key]['statusName'] = $status[(int)$val['status']];
            $map = array();
            $map['case_id'] = $this->case['id'];
            $map['case_mediate_accept_id'] = $val['id'];
            $noticeList = M('CaseMediateNotice')->where($map)->select();
            $list[$key]['notice'] = count($noticeList);
        }
        $this->assign('list', $list);
        // 分页信息
//        $pageInfo = array(
//            'totalrows' => $count,
//            'totalpage' => $page->totalpages,
//            'nowpage' => $page->nowpage,
//        );
//        $this->assign('page1', $pageInfo);
        $this->display('CaseMediateHandleInfo/index/mediateTable');
    }

    /**
     * 调解申请书加载
     */
    public function photoTable(){
        $this->assign('id',I('get.id'));
        $this->display();
    }

    /**
     * 获取图片列表
     */
    public function photoList(){
        $cate = I('post.cate','','int');
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('CaseMediateHandleInfo/index/photoTable');
    }

    /**
     * 不调节画面加载
     */
    public function unMediateIndex(){
        //调解申请主键
        $caseMediateApplyId = I('get.id','','int');
        $caseId = $this->case['id'];
        //交通方式
        $traffic_type = get_custom_config('traffic_type');
        //伤害程度
        $hurt_type = get_custom_config('hurt_type');
        //调解申请信息
        $applyInfo = M('CaseMediateApply')->getById($caseMediateApplyId);
        //获取当事人信息
        $clientInfo = array();
        $client = M('CaseClient')->getbyId($applyInfo['case_client_id']);
        $clientInfo['name'] = $client['name'];
        $clientInfo['traffic_type'] = $traffic_type[$client['traffic_type']];
        $clientInfo['hurt_type'] = $hurt_type[$client['hurt_type']];
        //获取当事人列表
        $map = array();
        $map['case_id'] = $this->case['id'];
        $map['traffic_type'] = array('neq',8);
        $clientList = M('CaseClient')->where($map)->select();
        //获取当事人相关人信息
        $map = array();
        $map['case_client_id'] = $applyInfo['case_client_id'];
        $relaterList = M('CaseClientRelater')->where($map)->select();
        //获取不调解信息
        $map = array();
        $map['case_mediate_apply_id'] = $caseMediateApplyId;
        $map['case_id'] = $caseId;
        $info = M('CaseMediateRefuse')->where($map)->find();
        //获取当前大队办案人信息
        $departmentId = $this->my['department_id'];
        $map = array();
        $map['department_id'] = $departmentId;
        $userList = M('User')->where($map)->select();

        $this->assign('info',$info);
        $this->assign('userList',$userList);
        $this->assign('userId',$this->my['id']);
        $this->assign('clientInfo',$clientInfo);
        $this->assign('clientList',$clientList);
        $this->assign('relaterList',$relaterList);
        $this->assign('caseMediateApplyId',$caseMediateApplyId);
        $this->assign('clientId',$applyInfo['case_client_id']);
        $this->display();
    }

    /**
     * 调解通知画面加载
     */
    public function mediateIndex(){
        //调解表主键
        $caseMediateAcceptId = I('get.id','','int');
        //交通方式
        $traffic_type = get_custom_config('traffic_type');
        //伤害程度
        $hurt_type = get_custom_config('hurt_type');
        //当事人列表
        $map = array();
        $map['case_mediate_accept_id'] = $caseMediateAcceptId;
        $applyList = M('CaseMediateApply')->where($map)->select();
        $mediateClients = array();
        foreach($applyList as $key => $val){
            $clientInfo = M('CaseClient')->field('id,name,traffic_type,hurt_type,car_no')->getById($val['case_client_id']);
            $clientInfo['traffic_type'] = $traffic_type[$clientInfo['traffic_type']];
            $clientInfo['hurt_type'] = $hurt_type[$clientInfo['hurt_type']];
            array_push($mediateClients,$clientInfo);
        }
        //获取当前大队办案人信息
        $departmentId = $this->my['department_id'];
        $map = array();
        $map['department_id'] = $departmentId;
        $userList = M('User')->where($map)->select();
        $this->assign('userList',$userList);
        $this->assign('userId',$this->my['id']);
        $this->assign('mediateClients',$mediateClients);
        $this->assign('caseMediateAcceptId',$caseMediateAcceptId);
        $this->display();
    }

    /**
     * 获取当事人调解通知信息
     */
    public function getClientInfo(){
        $clientId = I('post.client_id');
        $caseId = $this->case['id'];
        $CaseMediateAcceptId = I('post.case_mediate_accept_id');
        $map = array();
        $map['case_client_id'] = $clientId;
        $map['case_mediate_accept_id'] = $CaseMediateAcceptId;
        $map['case_id'] = $caseId;
        $caseMediateNoticeInfo = M('CaseMediateNotice')->where($map)->find();
        $map = array();
        $map['case_client_id'] = $clientId;
        $relaterList = M('CaseClientRelater')->where($map)->select();
        $clientInfo = M('CaseClient')->getById($clientId);
        $this->success(array('info'=>$caseMediateNoticeInfo,'relaterList'=>$relaterList));
    }

    /**
     * 调解记录列表
     */
    public function mediateRecord(){
        //调解表主键
        $caseMediateAcceptId = I('get.id','','int');
        //当事人列表
        $map = array();
        $map['case_mediate_accept_id'] = $caseMediateAcceptId;
        $applyList = M('CaseMediateApply')->where($map)->select();
        $clientList = array();

        foreach($applyList as $key => $val){
            $clientInfo = M('CaseClient')->getById($val['case_client_id']);
            array_push($clientList,$clientInfo);
        }
        //获取当前大队办案人信息
        $departmentId = $this->my['department_id'];
        $map = array();
        $map['department_id'] = $departmentId;
        $userList = M('User')->where($map)->select();
        //画面详细信息
        $info = M('CaseMediateAccept')->getById($caseMediateAcceptId);
        $this->assign('info',$info);
        $this->assign('userList',$userList);
        $this->assign('userId',$this->my['id']);
        $this->assign('clientList',$clientList);
        $this->display();
    }

    //道路交通事故损害赔偿调解书加载
    public function mediateBook(){
        //调解表主键
        $caseMediateAcceptId = I('get.id','','int');
        //案件信息
        $caseInfo = M('Case')->getById($this->case['id']);
        //当事人列表
        $map = array();
        $map['case_mediate_accept_id'] = $caseMediateAcceptId;
        $applyList = M('CaseMediateApply')->where($map)->select();
        $clientList = array();

        foreach($applyList as $key => $val){
            $clientInfo = M('CaseClient')->getById($val['case_client_id']);
            array_push($clientList,$clientInfo);
        }
        //画面详细信息
        $info = M('CaseMediateAccept')->getById($caseMediateAcceptId);
        $this->assign('info',$info);
        $this->assign('caseInfo',$caseInfo);
        $this->assign('clientList',$clientList);
        $this->display();
    }

    //道路交通事故损害赔偿调解终结书记载
    public function mediateFinishBook(){
        //调解表主键
        $caseMediateAcceptId = I('get.id','','int');
        //案件信息
        $caseInfo = M('Case')->getById($this->case['id']);
        //当事人列表
        $map = array();
        $map['case_mediate_accept_id'] = $caseMediateAcceptId;
        $applyList = M('CaseMediateApply')->where($map)->select();
        $clientList = array();
        foreach($applyList as $key => $val){
            $clientInfo = M('CaseClient')->getById($val['case_client_id']);
            array_push($clientList,$clientInfo['name']);
        }
        //画面详细信息
        $info = M('CaseMediateAccept')->getById($caseMediateAcceptId);
        $this->assign('info',$info);
        $this->assign('caseInfo',$caseInfo);
        $this->assign('clientNames',implode(',',$clientList));
        $this->display();
    }
}
