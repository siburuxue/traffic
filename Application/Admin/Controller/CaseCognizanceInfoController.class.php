<?php
namespace Admin\Controller;
use Admin\Model\CaseCognizanceNormalModel;
use \Lib\Page;

/**
 * 事故认定
 */
class CaseCognizanceInfoController extends CaseDetailController {

    public function __construct(){
        parent::__construct();
        $map = array();
        $map['case_id'] = $this->case['id'];
        $baseInfo = M('CaseExtBase')->field('weather')->where($map)->find();
        $this->assign('weather',$baseInfo['weather']);
    }

    /**
     * 简易事故认定画面初始化
     */
    public function simpleIndex(){
        $id = I('get.id');
        $case_id = $this->case['id'];
        $traffic_type = get_custom_config('traffic_type');
        $grade_type = get_custom_config('grade_type');
        //案件信息
        $map = array();
        $map['id'] = $case_id;
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();
        //当事人信息
        $map1 = array();
        $map1['case_id'] = $case_id;
        $map1['traffic_type'] = array('neq',8);
        $map1['is_del'] = 0;
        $caseClientList = M('CaseClient')->where($map1)->select();
        foreach($caseClientList as $key => $val){
            $caseClientList[$key]['traffic_type'] = $traffic_type[$val['traffic_type']];
            $caseClientList[$key]['grade_type'] = $grade_type[$val['grade_type']];
        }
        //简易事故认定信息
        $map2 = array();
        $map2['CaseCognizance.case_id'] = $case_id;
        $map2['CaseCognizance.id'] = $id;
        $simpleInfo = D('CaseCognizanceSimpleView')->where($map2)->find();
        //简易事故认定被拒绝列表信息
        $map3 = array();
        $map3['CaseCognizance.case_id'] = $case_id;
        $map3['CaseCognizance.id'] = $id;
        $map3['is_back'] = 1;
        $rejectList = D('CaseCognizanceRejectView')->where($map3)->select();

        //是否可以退回
        //没有事故认定不能退回
        $map = array();
        $map['case_id'] = $case_id;
        $map['is_back'] = 0;
        $cognizanceInfo = M('CaseCognizance')->where($map)->select();
        if(count($cognizanceInfo) == 0){
            $backMsg = '没有事故认定，不能退回';
        }
        //未制作不能退回
        $map = array();
        $map['case_id'] = $case_id;
        $makeCognizanceInfo = M('CaseCognizance')->where($map)->select();
        if($makeCognizanceInfo[0]['cognizance_type'] == '1'){
            if($makeCognizanceInfo[0]['is_make'] == '0'){
                $backMsg = '事故认定未制作，不能退回';
            }
        }
        //有复核申请，已终止时可以退回
        $map = array();
        $map['case_id'] = $case_id;
        $map['is_over'] = 0;
        $reviewInfo = M('CaseReview')->where($map)->select();
        if(count($reviewInfo) > 0){
            $backMsg = '该事故认定已申请复核，不能退回';
        }
        $map = array();
        $map['case_id'] = $case_id;
        $cognizanceList = M('CaseCognizance')->where($map)->order('id desc')->limit(1)->select();
        if($cognizanceList[0]['is_back'] == 1){
            $isBack = 1;
        }else{
            $isBack = 0;
        }

        $this->assign('id',$id);
        $this->assign('backMsg',$backMsg);
        $this->assign('isBack',$isBack);
        $this->assign('caseInfo',$caseInfo);
        $this->assign('simpleInfo',$simpleInfo);
        $this->assign('rejectList',$rejectList);
        $this->assign('caseClientList',$caseClientList);
        $this->display();
    }

    /**
     * 一般事故认定画面初始化
     */
    public function normalIndex(){
        $action = I('get.action');
        //画面主键
        $id = I('get.id','','int');
        $case_id = $this->case['id'];
        //案件信息
        $map = array();
        $map['id'] = $case_id;
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();
        if($action == "investigation_report"){  //调查报告
            //一般事故认定-调查报告信息
            $map2 = array();
            $map2['id'] = $id;
            $info = M('CaseCognizanceReport')->where($map2)->find();
            //一般事故认定主表信息
            $map1 = array();
            $map1['id'] =  $info['case_cognizance_id'];
            $normalCognizanceInfo = M('CaseCognizance')->where($map1)->find();
            //事故认定ID
            $map5 = array();
            $map5['case_cognizance_report_id'] = $id;
            $accidentIdentificationInfo = M('CaseCognizanceNormal')->where($map5)->find();
            //历史调查报告-管理员退回
            $map3 = array();
            $map3['is_back'] = 1;
            $map3['CaseCognizance.id'] = $info['case_cognizance_id'];
            $backList = D('CaseCognizanceRejectView')->where($map3)->select();
            //历史调查报告
            $map = array();
            $map['check_status'] = array('neq',0);
            $map['case_cognizance_id'] = $info['case_cognizance_id'];
            $historyList = M('CaseCognizanceReport')->field('id,case_cognizance_id,check_status')->where($map)->order('id desc')->select();
            foreach ($historyList as $key => $val){
                if($val['check_status'] == '1'){
                    $historyList[$key]['status'] = '审核通过';
                }else if($val['check_status'] == '2'){
                    $historyList[$key]['status'] = '审核未通过';
                }else if($val['check_status'] == '3'){
                    $historyList[$key]['status'] = '复核科退回';
                }
                //提交时间
                $map = array();
                $map['case_cognizance_report_id'] = $val['id'];
                $map['case_id'] = $this->case['id'];
                $map['case_cognizance_id'] = $info['case_cognizance_id'];
                $proveInfo = M('CaseCognizanceProve')->where($map)->find();
                $map = array();
                $map['case_id'] = $this->case['id'];
                if($val['check_status'] == '3'){
                    $map['cate'] = 20;
                }else{
                    $map['cate'] = 2;
                    $map['item_id'] = $proveInfo['id'];
                }
                $map['pid'] = 0;
                $checkInfo = M('CaseCheck')->where($map)->find();
                $historyList[$key]['create_time'] = $checkInfo['create_time'];

            }
            //事故中止ID
            $map6 = array();
            $map6['case_cognizance_id'] = $normalCognizanceInfo['id'];
            $stopInfo = M('CaseCognizanceStop')->where($map6)->find();

            $this->assign('info',$info);
            $this->assign('backList',$backList);
            $this->assign('stopId',$stopInfo['id']);
            $this->assign('historyList',$historyList);
            $this->assign('normalCognizanceInfo',$normalCognizanceInfo);
            $this->assign('accidentIdentificationId',$accidentIdentificationInfo['id']);
        }else if($action == "accident_termination"){    //呈请中止
            //调查报告ID
            $reportId = I('get.report_id','',int);
            //调查报告信息
            $reportInfo = M('CaseCognizanceReport')->getById($reportId);
            //一般调查报告主表ID
            $cognizanceId = $reportInfo['case_cognizance_id'];
            //一般事故认定主表信息
            $map1 = array();
            $map1['id'] =  $cognizanceId;
            $cognizanceInfo = M('CaseCognizance')->where($map1)->find();
            //呈请事故中止信息
            $map9 = array();
            $map9['case_cognizance_id'] = $cognizanceId;
            $stopInfo = M('CaseCognizanceStop')->where($map9)->find();
            //审批人
            $list = $this->getCheckUserList("case_cognizance_stop_1");
            //审批列表
            $map10 = array();
            $map10['item_id'] = $id;
            $map10['cate'] = 3;
            $map10['case_id'] = $this->case['id'];
            $map10['pid'] = 0;
            $model = D('CaseCheckView');
            $checkList = $model->where($map10)->order('CaseCheck.create_time desc')->select();
            $array = array('待审核', '通过', '拒绝');
            foreach ($checkList as $key => $val) {
                $checkList[$key]['status'] = $array[$val['status']];
                $map = array();
                $map['pid'] = $val['id'];
                $map['cate'] = 3;
                $map['item_id'] = $id;
                $map['case_id'] = $this->case['id'];
                $checkList1 = $model->where($map)->select();
                if(count($checkList1) > 0){
                    $checkList[$key]['status'] = $array[$checkList1[0]['status']];
                    $map = array();
                    $map['pid'] = $checkList1[0]['id'];
                    $map['cate'] = 3;
                    $map['item_id'] = $id;
                    $map['case_id'] = $this->case['id'];
                    $checkList2 = $model->where($map)->select();
                    if(count($checkList2) > 0){
                        $checkList[$key]['status'] = $array[$checkList2[0]['status']];
                    }
                }
            }
            //事故认定ID
            $map11 = array();
            $map11['case_cognizance_report_id'] = $reportId;
            $identificationInfo = M('CaseCognizanceNormal')->where($map11)->find();
            $this->assign('list',$list);
            $this->assign('stopInfo',$stopInfo);
            $this->assign('reportId',$reportId);
            $this->assign('checkList', $checkList);
            $this->assign('cognizanceId',$cognizanceId);
            $this->assign('cognizanceInfo',$cognizanceInfo);
            $this->assign('identificationInfo',$identificationInfo);
        }else if($action == "accident_identification"){ //事故认定
            //调查报告ID
            $reportId = I('get.report_id','',int);
            //调查报告信息
            $reportInfo = M('CaseCognizanceReport')->getById($reportId);
            //一般调查报告主表ID
            $cognizanceId = $reportInfo['case_cognizance_id'];
            $cognizanceResult = M('CaseCognizance')->getById($cognizanceId);
            //事故认定信息
            $info = M('caseCognizanceNormal')->getById($id);
            //如果事故认定信息不存在，取调查报告信息作为默认值
            if($id == ""){
                $info = array(
                    'base_info' => $reportInfo['base_info_client'].$reportInfo['base_info_car'].$reportInfo['base_info_road'],
                    'process' => $reportInfo['fact'],
                    'research' => $reportInfo['reason'],
                    'reason' => $reportInfo['cognizance']
                );
            }
            //呈请事故中止
            $map7= array();
            $map7['case_cognizance_id'] = $cognizanceId;
            $stopInfo = M('CaseCognizanceStop')->where($map7)->find();
            //审批人
            $list = $this->getCheckUserList("case_cognizance_accident_identification_1");
            //制作按钮是否可用
            $map8 = array();
            $map8['item_id'] = $id;
            $map8['cate'] = 2;
            $map8['case_id'] = $case_id;
            $checkInfo = M('CaseCheck')->where($map8)->select();
            $this->assign('make',count($checkInfo));
            //是否可以退回
            //没有事故认定不能退回
            $map = array();
            $map['case_id'] = $case_id;
            $map['is_back'] = 0;
            $cognizanceInfo = M('CaseCognizance')->where($map)->select();
            if(count($cognizanceInfo) == 0){
                $backMsg = '没有事故认定，不能退回';
            }
            //未制作不能退回
            $map = array();
            $map['case_id'] = $case_id;
            $map['is_back'] = 0;
            $makeCognizanceInfo = M('CaseCognizance')->where($map)->select();
            if($makeCognizanceInfo[0]['cognizance_type'] == '1'){
                if($makeCognizanceInfo[0]['is_make'] == '0'){
                    $backMsg = '事故认定未制作，不能退回';
                }
            }
            //有复核申请，已终止时可以退回
            $map = array();
            $map['case_id'] = $case_id;
            $map['is_back'] = 0;
            $map['is_over'] = 0;
            $reviewInfo = M('CaseReview')->where($map)->select();
            if(count($reviewInfo) > 0){
                $backMsg = '该事故认定已申请复核，不能退回';
            }
            $map = array();
            $map['case_id'] = $case_id;
            $cognizanceList = M('CaseCognizance')->where($map)->order('id desc')->limit(1)->select();
            if($cognizanceList[0]['is_back'] == 1){
                $isBack = 1;
            }else{
                $isBack = 0;
            }
            $this->assign('backMsg',$backMsg);
            $this->assign('isBack',$isBack);
            $this->assign('stopId',$stopInfo['id']);
            $this->assign('info',$info);
            $this->assign('list',$list);
            $this->assign('reportId',$reportInfo['id']);
            $this->assign('reportInfo',$reportInfo);
            $this->assign('cognizanceResult',$cognizanceResult);
        }else{
            $this->error('非法访问');
        }
        $this->assign('id',$id);
        $this->assign('action',$action);
        $this->assign('caseInfo',$caseInfo);
        $this->display();
    }

    /**
     * 一般事故认定-调查报告-管理员退回查看
     */
    public function normalReportManagerView(){
        //调查报告主键
        $id = I('get.id','','int');
        //案件信息
        $map = array();
        $map['id'] = $this->case_id['id'];
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();
        //一般事故认定-调查报告信息
        $map2 = array();
        $map2['id'] = $id;
        $info = M('CaseCognizanceReport')->where($map2)->find();
        //历史调查报告-管理员退回
        $map3 = array();
        $map3['is_back'] = 1;
        $map3['CaseCognizance.id'] = $info['case_cognizance_id'];
        $backList = D('CaseCognizanceRejectView')->where($map3)->select();
        $this->assign('info',$info);
        $this->assign('caseInfo',$caseInfo);
        $this->assign('backList',$backList);
        $this->display('CaseCognizanceInfo/normal/investigation_report/normalReportManagerView');
    }

    /**
     * 下发画面加载
     */
    public function issueIndex(){
        $type = I('get.type');
        $case_id =$this->case['id'];
        $cognizanceId = I('get.cognizance_id','','int');
        $traffic_type = get_custom_config('traffic_type');
        $hurt_type = get_custom_config('hurt_type');

        //送达回执详细信息
        $map = array();
        $map['case_cognizance_id'] = $cognizanceId;
        $info = M('CaseCognizanceNotice')->where($map)->find();

        //当事人列表
        $map1 = array();
        $map1['case_id'] = $case_id;
        $map1['traffic_type'] = array('neq',8);
        $clientList = M('CaseClient')->field('id,name,car_no,traffic_type,hurt_type')->where($map1)->select();
        foreach($clientList as $key => $val){
            $clientList[$key]['traffic_type'] = $traffic_type[$val['traffic_type']];
            $clientList[$key]['hurt_type'] = $hurt_type[$val['hurt_type']];
        }

        $this->assign('info',$info);
        $this->assign('exist',empty($info) ? 0 : 1);
        $this->assign('clientList',$clientList);
        $this->assign('cognizanceId',$cognizanceId);
        if($type == 'normal'){
            $this->display('CaseCognizanceInfo/normal/accident_identification/issue');
        }else if($type == 'unCognizance'){
            $this->display('CaseCognizanceInfo/unCognizance/road_traffic_accident_certificate/issue');
        }
    }

    /**
     * 下发-短信通知画面加载
     */
    public function issueSmsIndex(){
        $type = I('get.type');
        $cognizanceId = I('get.cognizance_id','','int');
        //获取当事人-相关人列表
        $map2 = array();
        $map2['case_id'] = $this->case['id'];
        $map2['traffic_type'] = array('neq',8);
        $clientList = M('CaseClient')->field('id,tel,name')->where($map2)->select();
        foreach ($clientList as $key => $val){
            $map3 = array();
            $map3['case_client_id'] = $val['id'];
            $relaterList = M('CaseClientRelater')->field('id,tel,name')->where($map3)->select();
            $clientList[$key]['relater'] = $relaterList;
        }
        $this->assign('list',$clientList);
        $this->assign('cognizanceId',$cognizanceId);
        if($type == 'normal'){
            $this->display('CaseCognizanceInfo/normal/accident_identification/normalIssueSms');
        }else if($type == 'unCognizance'){
            $this->display('CaseCognizanceInfo/unCognizance/road_traffic_accident_certificate/unCognizanceIssueSms');
        }
    }

    /**
     * 一般事故认定-呈请中止-提审查看
     */
    public function CognizanceStopCheckView(){
        $type = I('get.type');
        $stopId = I('get.stop_id','','int');
        $id = I('get.id','','int');
        $stopInfo = M('CaseCognizanceStop')->getById($stopId);
        $map = array();
        $map['id'] = $id;
        $model = D('CaseCheckView');
        $checkList = $model->where($map)->select();
        $array = array('待审核', '通过', '拒绝');
        foreach ($checkList as $key => $val) {
            $checkList[$key]['status'] = $array[$val['status']];
        }
		usort($list,function($a,$b){
			return $a['create_time'] <= $b['create_time'];
		});
        $this->assign('stopInfo',$stopInfo);
        $this->assign('checkList',$checkList);
        if($type == 'normal'){
            $this->display('CaseCognizanceInfo/normal/accident_termination/checkView');
        }else if($type == "unCognizance"){
            $this->display('CaseCognizanceInfo/unCognizance/accident_termination/checkView');
        }
    }

    /**
     * 无法认定画面初始化
     */
    public function unCognizanceIndex(){
        $action = I('get.action');
        //画面主键
        $id = I('get.id','','int');
        $case_id = $this->case['id'];
        //案件信息
        $map = array();
        $map['id'] = $case_id;
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();
        if($action == "investigation_report"){  //调查报告
            //调查报告信息
            $map2 = array();
            $map2['id'] = $id;
            $info = M('CaseCognizanceReport')->where($map2)->find();
            //事故认定主表信息
            $map1 = array();
            $map1['id'] =  $info['case_cognizance_id'];
            $cognizanceInfo = M('CaseCognizance')->where($map1)->find();
            //道路交通事故证明ID
            $map5 = array();
            $map5['case_cognizance_report_id'] = $id;
            $caseCognizanceProveInfo = M('CaseCognizanceProve')->where($map5)->find();
            //历史调查报告-管理员退回
            $map3 = array();
            $map3['is_back'] = 1;
            $map3['CaseCognizance.id'] = $info['case_cognizance_id'];
            $backList = D('CaseCognizanceRejectView')->where($map3)->select();
            //历史调查报告
            $map = array();
            $map['check_status'] = array('neq',0);
            $map['case_cognizance_id'] = $info['case_cognizance_id'];
            $historyList = M('CaseCognizanceReport')->field('id,case_cognizance_id,check_status')->where($map)->order('id desc')->select();
            foreach ($historyList as $key => $val){
                if($val['check_status'] == '1'){
                    $historyList[$key]['status'] = '审核通过';
                }else if($val['check_status'] == '2'){
                    $historyList[$key]['status'] = '审核未通过';
                }else if($val['check_status'] == '3'){
                    $historyList[$key]['status'] = '复核科退回';
                }
                //提交时间
                $map = array();
                $map['case_cognizance_report_id'] = $val['id'];
                $map['case_id'] = $this->case['id'];
                $map['case_cognizance_id'] = $info['case_cognizance_id'];
                $proveInfo = M('CaseCognizanceProve')->where($map)->find();
                $map = array();
                $map['case_id'] = $this->case['id'];
                $map['cate'] = 4;
                $map['pid'] = 0;
                $map['item_id'] = $proveInfo['id'];
                $checkInfo = M('CaseCheck')->where($map)->find();
                $historyList[$key]['create_time'] = $checkInfo['create_time'];

            }
            //事故中止ID
            $map6 = array();
            $map6['case_cognizance_id'] = $cognizanceInfo['id'];
            $stopInfo = M('CaseCognizanceStop')->where($map6)->find();
            $this->assign('info',$info);
            $this->assign('backList',$backList);
            $this->assign('historyList',$historyList);
            $this->assign('stopId',$stopInfo['id']);
            $this->assign('cognizanceInfo',$cognizanceInfo);
            $this->assign('caseCognizanceProveId',$caseCognizanceProveInfo['id']);
        }else if($action == "accident_termination"){    //呈请中止
            //调查报告ID
            $reportId = I('get.report_id','',int);
            //调查报告信息
            $reportInfo = M('CaseCognizanceReport')->getById($reportId);
            //一般调查报告主表ID
            $cognizanceId = $reportInfo['case_cognizance_id'];
            //一般事故认定主表信息
            $map1 = array();
            $map1['id'] =  $cognizanceId;
            $cognizanceInfo = M('CaseCognizance')->where($map1)->find();
            //呈请事故中止信息
            $map9 = array();
            $map9['case_cognizance_id'] = $cognizanceId;
            $stopInfo = M('CaseCognizanceStop')->where($map9)->find();
            //审批人
            $list = $this->getCheckUserList("case_cognizance_stop_1");
            //审批列表
            $map10 = array();
            $map10['item_id'] = $id;
            $map10['cate'] = 3;
            $map10['case_id'] = $this->case['id'];
            $map10['pid'] = 0;
            $model = D('CaseCheckView');
            $checkList = $model->where($map10)->order('CaseCheck.create_time desc')->select();
            $array = array('待审核', '通过', '拒绝');
            foreach ($checkList as $key => $val) {
                $checkList[$key]['status'] = $array[$val['status']];
                $map = array();
                $map['pid'] = $val['id'];
                $map['cate'] = 3;
                $map['item_id'] = $id;
                $map['case_id'] = $this->case['id'];
                $checkList1 = $model->where($map)->select();
                if(count($checkList1) > 0){
                    $checkList[$key]['status'] = $array[$checkList1[0]['status']];
                    $map = array();
                    $map['pid'] = $checkList1[0]['id'];
                    $map['cate'] = 3;
                    $map['item_id'] = $id;
                    $map['case_id'] = $this->case['id'];
                    $checkList2 = $model->where($map)->select();
                    if(count($checkList2) > 0){
                        $checkList[$key]['status'] = $array[$checkList2[0]['status']];
                    }
                }
            }
            //道路交通事故证明ID
            $map5 = array();
            $map5['case_cognizance_report_id'] = $reportId;
            $CaseCognizanceProveInfo = M('CaseCognizanceProve')->where($map5)->find();
            $this->assign('list',$list);
            $this->assign('stopInfo',$stopInfo);
            $this->assign('reportId',$reportId);
            $this->assign('checkList', $checkList);
            $this->assign('cognizanceId',$cognizanceId);
            $this->assign('cognizanceInfo',$cognizanceInfo);
            $this->assign('CaseCognizanceProveInfoId',$CaseCognizanceProveInfo['id']);
        }else if($action == "road_traffic_accident_certificate"){ //道路交通事故证明
            //调查报告ID
            $reportId = I('get.report_id','',int);
            //调查报告信息
            $reportInfo = M('CaseCognizanceReport')->getById($reportId);
            //一般调查报告主表ID
            $cognizanceId = $reportInfo['case_cognizance_id'];
            $cognizanceInfo = M('CaseCognizance')->getById($cognizanceId);
            //道路交通事故证明信息
            $info = M('CaseCognizanceProve')->getById($id);
            //如果道路交通事故证明信息不存在，取调查报告信息作为默认值
            if($id == ""){
                $info = array(
                    'base_info' => $reportInfo['base_info_client'].$reportInfo['base_info_car'].$reportInfo['base_info_road'],
                    'fact' => $reportInfo['fact'],
                );
            }
            //呈请事故中止
            $map7= array();
            $map7['case_cognizance_id'] = $cognizanceId;
            $stopInfo = M('CaseCognizanceStop')->where($map7)->find();
            //审批人
            $list = $this->getCheckUserList("case_cognizance_road_traffic_accident_certificate_1");
            //制作按钮是否可用
            $map8 = array();
            $map8['item_id'] = $id;
            $map8['cate'] = 4;
            $map8['case_id'] = $case_id;
            $checkInfo = M('CaseCheck')->where($map8)->select();
            //是否可以退回
            //没有事故认定不能退回
            $map = array();
            $map['case_id'] = $case_id;
            $map['is_back'] = 0;
            $cognizanceInfo = M('CaseCognizance')->where($map)->select();
            if(count($cognizanceInfo) == 0){
                $backMsg = '没有事故认定，不能退回';
            }
            //未制作不能退回
            $map = array();
            $map['case_id'] = $case_id;
            $map['is_back'] = 0;
            $makeCognizanceInfo = M('CaseCognizance')->where($map)->select();
            if($makeCognizanceInfo[0]['cognizance_type'] == '1'){
                if($makeCognizanceInfo[0]['is_make'] == '0'){
                    $backMsg = '事故认定未制作，不能退回';
                }
            }
            //有复核申请，已终止时可以退回
            $map = array();
            $map['case_id'] = $case_id;
            $map['is_over'] = 0;
            $reviewInfo = M('CaseReview')->where($map)->select();
            if(count($reviewInfo) > 0){
                $backMsg = '该事故认定已申请复核，不能退回';
            }
            $map = array();
            $map['case_id'] = $case_id;
            $cognizanceList = M('CaseCognizance')->where($map)->order('id desc')->limit(1)->select();
            if($cognizanceList[0]['is_back'] == 1){
                $isBack = 1;
            }else{
                $isBack = 0;
            }
            $this->assign('backMsg',$backMsg);
            $this->assign('isBack',$isBack);
            $this->assign('make',count($checkInfo));
            $this->assign('stopId',$stopInfo['id']);
            $this->assign('info',$info);
            $this->assign('list',$list);
            $this->assign('reportId',$reportInfo['id']);
            $this->assign('reportInfo',$reportInfo);
            $this->assign('cognizanceInfo',$cognizanceInfo);
        }else{
            $this->error('非法访问');
        }
        $this->assign('id',$id);
        $this->assign('action',$action);
        $this->assign('caseInfo',$caseInfo);
        $this->display();
    }

    /**
     * 逃逸事故认定申请画面初始化
     */
    public function escapeIndex(){
        //当事人信息
        $case_id = $this->case['id'];
        $map = array();
        $map['case_id'] = $case_id;
        $map['traffic_type'] = array('neq',8);
        $clientList = M('CaseClient')->field('id,name')->where($map)->select();
        $this->assign('clientList',$clientList);
        $this->display();
    }

    /**
     * 逃逸事故认定-书面材料画面加载
     */
    public function escapePhotoList(){
        $id = I('get.id');
        $this->assign('id',$id);
        $this->display('CaseCognizanceInfo/escape/photoTable');
    }

    /**
     * 逃逸事故认定-书面材料列表
     */
    public function escapeList(){
        $case_id = $this->case['id'];
        $map1 = array();
        $map1['CaseCoescape.case_id'] = $case_id;
        $list = D('CaseCognizanceEscapeView')->where($map1)->order("id desc")->select();
        foreach($list as $key => $val){
            $photoList = parent::getPhotoList(16,$val['id']);
            $list[$key]['count'] = count($photoList);
        }
        $this->assign('list',$list);
        $this->display('CaseCognizanceInfo/escape/list');
    }

    /**
     * 逃逸事故认定详细画面初始化
     */
    public function escapeInfo(){
        $case_id = $this->case['id'];
        $id = I('get.id');
        //案件信息
        $map = array();
        $map['id'] = $case_id;
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();
        $info = M('CaseCoescape')->getById($id);
        $this->assign('id',$id);
        $this->assign('info',$info);
        $this->assign('caseInfo',$caseInfo);
        $this->display('CaseCognizanceInfo/escape/escapeInfo');
    }

    /**
     * 逃逸事故认定下发画面加载
     */
    public function escapeIssueIndex(){
        //逃逸事故主表ID
        $id = I('get.id','','int');
        $case_id = $this->case['id'];

        $traffic_type = get_custom_config('traffic_type');
        $hurt_type = get_custom_config('hurt_type');

        //获取当事人信息
        $client_rs = M('CaseCoescape')->getById($id);
        $map2 = array();
        $map2['case_id'] = $case_id;
        $map2['id'] = $client_rs['case_client_id'];
        $clientInfo = M('CaseClient')->where($map2)->find();
        $clientInfo['traffic_type'] = $traffic_type[(int)$clientInfo['traffic_type']];
        $clientInfo['hurt_type'] = $hurt_type[(int)$clientInfo['hurt_type']];

        //送达回执详细信息
        $map = array();
        $map['ts_case_coescape_id'] = $id;
        $map['case_id'] = $this->case['id'];
        $map['target_user_id'] = $client_rs['case_client_id'];
        $info = M('CaseCoescapeNotice')->where($map)->find();

        //获取当事人-相关人列表
        $map1 = array();
        $map1['id'] = $client_rs['case_client_id'];
        $clientList = M('CaseClient')->field('id,name')->where($map1)->select();
        $array = array();
        foreach ($clientList as $key => $val){
            $map3 = array();
            $map3['case_client_id'] = $val['id'];
            $relaterList = M('CaseClientRelater')->field('id,name')->where($map3)->select();
            if(count($relaterList) > 0){
                $array = array_merge($array,$relaterList);
            }
        }
        if(count($array) > 0){
            $allArray = array_merge($clientList,$array);
        }else{
            $allArray = $clientList;
        }
        $this->assign('id',$id);
        $this->assign('info',$info);
        $this->assign('allArray',$allArray);
        $this->assign('clientInfo',$clientInfo);
        $this->display('CaseCognizanceInfo/escape/escapeIssue');
    }

    /**
     * 逃逸事故下发-短信通知画面加载
     */
    public function escapeSmsIndex(){
        $case_id = $this->case['id'];
        //逃逸事故认定主键
        $ts_case_coescape_id = I('get.ts_case_coescape_id');
        //获取当事人信息
        $client_rs = M('CaseCoescape')->getById($ts_case_coescape_id);
        $map = array();
        $map['id'] = $client_rs['case_client_id'];
        $map['case_id'] = $case_id;
        $map['traffic_type'] = array('neq',8);
        $clientInfo = M('CaseClient')->field('tel,name')->where($map)->find();
        $map1 = array();
        $map1['case_client_id'] = $client_rs['case_client_id'];
        $list = M('CaseClientRelater')->field('tel,name')->where($map1)->select();
        array_unshift($list,$clientInfo);
        $this->assign('list',$list);
        $this->assign('ts_case_coescape_id',$ts_case_coescape_id);
        $this->display('CaseCognizanceInfo/escape/escapeIssueSms');
    }

    /**
     * 加载图片
     */
    public function photoList(){
        $cate = I('post.cate','','int');
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('CaseCognizanceInfo/simple/photoTable');
    }

    /**
     * 下发画面取每个当事人数据
     */
    public function getClientIssueInfo(){
        $clientId = I('post.client_id');
        $caseId = $this->case['id'];
        $cognizanceId = I('post.case_cognizance_id');
        $cate = I('post.cate');
        $map = array();
        $map['case_id'] = $caseId;
        $map['case_cognizance_id'] = $cognizanceId;
        $map['cate'] = $cate;
        $map['target_user_id'] = $clientId;
        $info = M('CaseCognizanceNotice')->where($map)->find();
        //获取当事人-相关人数据
        $clientInfo = M('CaseClient')->field('id,name')->getById($clientId);
        $map = array();
        $map['case_client_id'] = $clientId;
        $clientRelaterList = M('CaseClientRelater')->field('id,name')->where($map)->select();
        array_unshift($clientRelaterList,$clientInfo);
        if($info['post_time'] != ''){
            $info['post_time'] = date('Y-m-d H:i',$info['post_time']);
        }
        $info['relaterList'] = $clientRelaterList;
        $this->success($info);
    }

    /**
     * 审批列表查看画面
     */
    public function reportCheckView(){
        $type = I('get.type');
        $id = I('get.id','','int');
        $map = array();
        $map['case_cognizance_report_id'] = $id;
        if($type == 'normal'){
            $info = M('CaseCognizanceNormal')->where($map)->find();
        }else{
            $info = M('CaseCognizanceProve')->where($map)->find();
        }
        $itemId = $info['id'];
        $map = array();
        $map['item_id'] = $itemId;
        $map['case_id'] = $this->case['id'];
        if($type == 'normal'){
            $map['cate'] = 2;
        }else{
            $map['cate'] = 4;
        }
        $model = D('CaseCheckView');
        $checkList = $model->where($map)->select();
        $array = array('待审核', '审核通过', '审核未通过');
        foreach ($checkList as $key => $val) {
            $checkList[$key]['status'] = $array[$val['status']];
        }
        $this->assign('checkList',$checkList);
        //案件信息
        $map = array();
        $map['id'] = $this->case['id'];
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();
        $this->assign('caseInfo',$caseInfo);
        //调查报告信息
        $info = M('CaseCognizanceReport')->getById($id);
        $this->assign('info',$info);
        if($type == 'normal'){
            $this->display('CaseCognizance/normal/investigation_report/checkView');
        }else if($type == "unCognizance"){
            $this->display('CaseCognizanceInfo/unCognizance/investigation_report/checkView');
        }
    }

    /**
     * 管理员退回查看页面
     */
    public function reportBackView(){
        $type = I('get.type');
        $id = I('get.id','','int');
        $map = array();
        $map['id'] = $id;
        $model = D('CaseCognizanceBackView');
        $backList = $model->where($map)->select();
        $this->assign('backList',$backList);
        //案件信息
        $map = array();
        $map['id'] = $this->case['id'];
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();
        $this->assign('caseInfo',$caseInfo);
        $map = array();
        $map['case_cognizance_id'] = $id;
        $info = M('CaseCognizanceReport')->where($map)->find();
        $this->assign('info',$info);
        if($type == 'normal'){
            $this->display('CaseCognizance/normal/investigation_report/backView');
        }else if($type == "unCognizance"){
            $this->display('CaseCognizanceInfo/unCognizance/investigation_report/backView');
        }
    }

    /**
     * 复核查看页面
     */
    public function reportReviewView(){
        $type = I('get.type');
        $id = I('get.id','','int');
        //领导审批信息
        $map = array();
        $map['case_cognizance_report_id'] = $id;
        if($type == 'normal'){
            $info = M('CaseCognizanceNormal')->where($map)->find();
        }else{
            $info = M('CaseCognizanceProve')->where($map)->find();
        }
        $itemId = $info['id'];
        $map = array();
        $map['item_id'] = $itemId;
        $map['case_id'] = $this->case['id'];
        if($type == 'normal'){
            $map['cate'] = 2;
        }else{
            $map['cate'] = 4;
        }
        $model = D('CaseCheckView');
        $checkList = $model->where($map)->select();
        $this->assign('checkList',$checkList);
        //复核科退回审批信息
        $map = array();
        $map['case_id'] = $this->case['id'];
        $review = M('CaseReview')->where($map)->order('id desc')->limit(1)->select();
        $map = array();
        $map['case_id'] = $this->case['id'];
        $map['case_review_id'] = $review[0]['id'];
        $reviewCheck = M('CaseReviewCheck')->where($map)->order("id desc")->limit(1)->select();
        $map = array();
        $map['cate'] = 20;
        $map['item_id'] = $reviewCheck[0]['id'];
        $map['case_id'] = $this->case['id'];
        $reviewList = $model->where($map)->select();
        $array = array('待审核', '审核通过', '审核未通过');
        foreach ($reviewList as $key => $val) {
            $reviewList[$key]['status'] = $array[$val['status']];
        }
        $this->assign('reviewList', $reviewList);
        //案件信息
        $map = array();
        $map['id'] = $this->case['id'];
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('accident_time,accident_place')->where($map)->find();
        $this->assign('caseInfo',$caseInfo);
        $info = M('CaseCognizanceReport')->getById($id);
        $this->assign('info',$info);
        if($type == 'normal'){
            $this->display('CaseCognizanceInfo/normal/investigation_report/reviewView');
        }else if($type == "unCognizance"){
            $this->display('CaseCognizanceInfo/unCognizance/investigation_report/reviewView');
        }
    }
}
?>