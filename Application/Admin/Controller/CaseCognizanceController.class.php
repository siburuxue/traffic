<?php
namespace Admin\Controller;
use Admin\Model\CaseCognizanceNormalModel;
use \Lib\Page;

/**
 * 事故认定
 */
class CaseCognizanceController extends CaseCommonController {

    public function __construct(){
        parent::__construct();
        $map = array();
        $map['case_id'] = $this->case['id'];
        $baseInfo = M('CaseExtBase')->field('weather')->where($map)->find();
        $baseConfig = C('custom_case_ext.case_ext_base');
        $weatherConfig = $baseConfig['weather'];
        $this->assign('weather',$weatherConfig[$baseInfo['weather']]);
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
        $this->assign('id',$id);
        $this->assign('caseInfo',$caseInfo);
        $this->assign('simpleInfo',$simpleInfo);
        $this->assign('rejectList',$rejectList);
        $this->assign('caseClientList',$caseClientList);
        $this->display();
    }

    /**
     * 保存简易事故认定信息
     */
    public function saveSimpleInfo(){
        $map = array();
        $map['case_id'] = $this->case['id'];
        $map['cognizance_type'] = 0;
        $model = D('CaseCognizance');
        $cognizanceId = I('post.case_cognizance_id','','int');
        // 开启事务
        $model->startTrans();
        $data1 = $model->create($map);
        if (false === $data1) {
            $this->error($model->getError());
        }
        if( $cognizanceId == ''){
            $id = $model->add($data1);
            if (!$id) {
                $model->rollback();
                $this->error($model->getError());
            }
        }
        $Model = D('CaseCognizanceSimple');
        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $model->rollback();
            $this->error($Model->getError());
        }
        if($cognizanceId == ''){
            $data['case_cognizance_id'] = $id;
            $rs = $Model->add($data);
        }else{
            $rs = $Model->save($data);
        }
        //当事人数据
        $detainForceList = I('post.detain_force_list');
        $clientModel = D('CaseClient');
        foreach($detainForceList as $key => $val){
            $clientData = $clientModel->create($val);
            $clientRs = $clientModel->save($clientData);
            if($clientRs === false){
                $Model->rollback();
                $this->error('保存失败');
            }
        }
        // 数据保存失败
        if ($rs === false) {
            $model->rollback();
            $this->error('保存失败');
        }
        if($cognizanceId !== ''){
            $rs = $cognizanceId;
        }else{
            $rs = $id;
        }
        $map = array();
        $map['id'] = $this->case['id'];
        $map['cognizance_status'] = 1;
        $map['cognizance_type'] = 1;
        $map['is_over'] = 1;
        $CaseRs = M('Case')->save($map);
        if($CaseRs === false){
            $model->rollback();
            $this->error('保存失败');
        }
        //操作日志
        $content = "办案人".$this->my['true_name']."在".date('Y-m-d H:i')."制作了简易事故认定书";
        $this->saveCaseLog($this->case['id'],$content);
        // 成功
        $model->commit();
        $this->success('保存成功',U('simpleIndex?case_id='.$this->case['id'].'&id='.$rs));
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
                $normalInfo = M('CaseCognizanceNormal')->where($map)->find();
                $map = array();
                $map['case_id'] = $this->case['id'];
                if($val['check_status'] == '3'){
                    $map['cate'] = 20;
                }else{
                    $map['cate'] = 2;
                    $map['item_id'] = $normalInfo['id'];
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
            $cognizanceInfo = M('CaseCognizance')->getById($cognizanceId);
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
     * 保存一般事故认定-调查报告信息
     */
    public function saveReportInfo(){
        $id = I('post.id','','int');
        if($id == ''){
            $model = D('CaseCognizance');
            $model->startTrans();
            $data = $model->create();
            $data['case_id'] = $this->case['id'];
            $data['cognizance_type'] = 1;
            $cognizanceId = $model->add($data);
            if(!$cognizanceId){
                $model->rollback();
                $this->error('数据保存失败');
            }
            $model1 = D('CaseCognizanceReport');
            $data1 = $model1->create();
            if($data1 === false){
                $model->rollback();
                $this->error($model1->getError());
            }
            $data1['case_cognizance_id'] = $cognizanceId;
            $reportId = $model1->add($data1);
            if(!$reportId){
                $model->rollback();
                $this->error('数据保存失败');
            }
            $map = array();
            $map['id'] = $this->case['id'];
            $map['cognizance_status'] = 1;
            $map['is_over'] = 1;
            $map['cognizance_type'] = 2;
            $CaseRs = M('Case')->save($map);
            if($CaseRs === false){
                $model->rollback();
                $this->error('保存失败');
            }
            $model->commit();
            if(I('post.type') == "normal"){
                $this->success('数据保存成功',U('normalIndex?case_id='.$this->case['id'].'&action=investigation_report&id='.$reportId));
            }else if(I('post.type') == "unCognizance"){
                $this->success('数据保存成功',U('unCognizanceIndex?case_id='.$this->case['id'].'&action=investigation_report&id='.$reportId));
            }
        }else{
            //调查报告信息
            $reportInfo = M('CaseCognizanceReport')->getById($id);
            //事故认定主表信息
            $cognizanceInfo = M('CaseCognizance')->getById($reportInfo['case_cognizance_id']);
            $cognizanceId = $cognizanceInfo['id'];
            if($cognizanceInfo['check_status'] == '2' || $cognizanceInfo['check_status'] == '3'){
                //如果是审批未通过或者复核科退回 更新事故认定主表信息，新增事故认定-调查报告、事故认定-事故认定、事故认定-交通道路事故证明
                //更新事故认定主表状态
                $model = D('CaseCognizance');
                $model->startTrans();
                $data = array(
                    'id' => $cognizanceId,
                    'is_submit' => 0,
                    'submit_time' => time(),
                    'submit_user_id' => $this->my['id'],
                    'check_status' => 0,
                );
                $para = $model->create($data);
                $result = $model->save($para);
                if($result === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                //调查报告信息
                //更新调查报告状态
                $map = array();
                $map['id'] = $id;
                $map['is_last'] = 0;
                $result = D('CaseCognizanceReport')->save($map);
                if($result === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                //新增调查报告信息
                $reportModel = D('CaseCognizanceReport');
                $data1 = array(
                    'case_cognizance_id' => $cognizanceId,
                    'base_info_client' => I('post.base_info_client'),
                    'base_info_car' => I('post.base_info_car'),
                    'base_info_road' => I('post.base_info_road'),
                    'fact' => I('post.fact'),
                    'desc' => I('post.desc'),
                    'reason' => I('post.reason'),
                    'cognizance' => I('post.cognizance'),
                    'punish' => I('post.punish'),
                    'reform' => I('post.reform')
                );
                $para1 =  $reportModel->create($data1);
                if($para1 === false){
                    $this->error($reportModel->getError());
                }
                $reportId = $reportModel->add($para1);
                if($reportId === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                if(I('post.type') == "normal"){
                    //事故认定信息
                    $identificationModel = D('CaseCognizanceNormal');
                    $map = array();
                    $map['case_cognizance_report_id'] = $id;
                    $accidentIdentificationInfo = M('CaseCognizanceNormal')->where($map)->find();
                    $data2 = array(
                        'case_cognizance_id' => $cognizanceId,
                        'case_cognizance_report_id' => $reportId,
                        'case_id' => $this->case['id'],
                        'base_info' => $accidentIdentificationInfo['base_info'],
                        'process' => $accidentIdentificationInfo['process'],
                        'research' => $accidentIdentificationInfo['research'],
                        'reason' => $accidentIdentificationInfo['reason'],
                    );
                    $para2 = $identificationModel->create($data2);
                    $accidentIdentificationId = $identificationModel->add($para2);
                    if($accidentIdentificationId === false){
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                    $model->commit();
                    $this->success('数据保存成功',U('normalIndex?case_id='.$this->case['id'].'&action=investigation_report&id='.$reportId));
                }else if(I('post.type') == "unCognizance"){
                    //交通道路事故证明
                    $proveModel = D('CaseCognizanceProve');
                    $map = array();
                    $map['case_cognizance_report_id'] = $id;
                    $proveInfo = M('CaseCognizanceProve')->where($map)->find();
                    $data3 = array(
                        'case_cognizance_id' => $cognizanceId,
                        'case_cognizance_report_id' => $reportId,
                        'case_id' => $this->case['id'],
                        'base_info' => $proveInfo['base_info'],
                        'fact' => $proveInfo['fact'],
                    );
                    $para3 = $proveModel->create($data3);
                    $proveId = $proveModel->add($para3);
                    if($proveId === false){
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                    $model->commit();
                    $this->success('数据保存成功',U('unCognizanceIndex?case_id='.$this->case['id'].'&action=investigation_report&id='.$reportId));
                }
            }else{
                $model = D('CaseCognizanceReport');
                $data1 = $model->create();
                if($data1 === false){
                    $this->error($model->getError());
                }
                $rs = $model->save($data1);
                if($rs === false){
                    $this->error('数据保存失败');
                }
                if(I('post.type') == "normal"){
                    $this->success('数据保存成功',U('normalIndex?case_id='.$this->case['id'].'&action=investigation_report&id='.$id));
                }else if(I('post.type') == "unCognizance"){
                    $this->success('数据保存成功',U('unCognizanceIndex?case_id='.$this->case['id'].'&action=investigation_report&id='.$id));
                }
            }
        }
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
        $this->display('CaseCognizance/normal/investigation_report/normalReportManagerView');
    }

    /**
     * 保存一般事故认定-事故认定
     */
    public function saveAccidentIdentificationInfo(){
        //事故认定-事故认定主键
        $id = I('post.id','','int');
        $reportId = I('post.case_cognizance_report_id');
        if($id === ''){
            $Model = D('CaseCognizanceNormal');
            // 创建数据
            $data = $Model->create();
            if (false === $data) {
                $this->error($Model->getError());
            }
            // 开启事务
            $Model->startTrans();
            $id = $Model->add($data);
            // 数据保存失败
            if ($id === false) {
                $Model->rollback();
                $this->error('保存失败');
            }
            // 成功
            $Model->commit();
            $this->success('保存成功',U('normalIndex?case_id='.$this->case['id'].'&action=accident_identification&report_id='.$reportId.'&id='.$id));
        }else{
            //事故认定信息
            $accidentIdentificationInfo = M('CaseCognizanceNormal')->getById($id);
            //事故认定主表信息
            $cognizanceInfo = M('CaseCognizance')->getById($accidentIdentificationInfo['case_cognizance_id']);
            //事故认定主表ID
            $cognizanceId = $cognizanceInfo['id'];
            if($cognizanceInfo['check_status'] == '2' || $cognizanceInfo['check_status'] == '3'){
                //如果是审核未通过或者复核科退回 更新事故认定主表信息，新增事故认定-调查报告、事故认定-事故认定、事故认定-交通道路事故证明
                //事故认定主表信息
                $model = D('CaseCognizance');
                $model->startTrans();
                $data = array(
                    'id' => $cognizanceId,
                    'is_submit' => 0,
                    'submit_time' => time(),
                    'submit_user_id' => $this->my['id'],
                    'check_status' => 0,
                );
                $para = $model->create($data);
                $result = $model->save($para);
                if($result === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                //调查报告信息
                //更新调查报告状态
                $map = array();
                $map['id'] = $reportId;
                $map['is_last'] = 0;
                $result = D('CaseCognizanceReport')->save($map);
                if($result === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                //新增调查报告信息
                $reportInfo = M('CaseCognizanceReport')->getById($reportId);
                $reportModel = D('CaseCognizanceReport');
                $data1 = array(
                    'case_cognizance_id' => $cognizanceId,
                    'base_info_client' => $reportInfo['base_info_client'],
                    'base_info_car' => $reportInfo['base_info_car'],
                    'base_info_road' => $reportInfo['base_info_road'],
                    'fact' => $reportInfo['fact'],
                    'desc' => $reportInfo['desc'],
                    'reason' => $reportInfo['reason'],
                    'cognizance' => $reportInfo['cognizance'],
                    'punish' => $reportInfo['punish'],
                    'reform' => $reportInfo['reform']
                );
                $para1 =  $reportModel->create($data1);
                if($para1 === false){
                    $this->error($reportModel->getError());
                }
                $reportId = $reportModel->add($para1);
                if($reportId === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                //事故认定信息
                $identificationModel = D('CaseCognizanceNormal');
                $data2 = array(
                    'case_cognizance_id' => $cognizanceId,
                    'case_cognizance_report_id' => $reportId,
                    'case_id' => $this->case['id'],
                    'base_info' => I('post.base_info'),
                    'process' => I('post.process'),
                    'research' => I('post.research'),
                    'reason' => I('post.reason'),
                );
                $para2 = $identificationModel->create($data2);
                $accidentIdentificationId = $identificationModel->add($para2);
                if($accidentIdentificationId === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                // 成功
                $model->commit();
                $this->success('保存成功',U('normalIndex?case_id='.$this->case['id'].'&action=accident_identification&report_id='.$reportId.'&id='.$accidentIdentificationId));
            }else{
                $model = D('CaseCognizanceNormal');
                $data1 = $model->create();
                if($data1 === false){
                    $this->error($model->getError());
                }
                $rs = $model->save($data1);
                if($rs === false){
                    $this->error('数据保存失败');
                }
                // 成功
                $this->success('保存成功',U('normalIndex?case_id='.$this->case['id'].'&action=accident_identification&report_id='.$reportId.'&id='.$id));
            }
        }
    }

    /**
     * 下发画面加载
     */
    public function issueIndex(){
        $type = I('get.type');
        $case_id =$this->case['id'];
        $cognizanceId = I('get.cognizance_id','','int');
        //事故认定-事故认定ID(事故认定-道路交通事故证明ID)
        $traffic_type = get_custom_config('traffic_type');
        $hurt_type = get_custom_config('hurt_type');

        //当事人列表
        $map1 = array();
        $map1['case_id'] = $case_id;
        $map1['traffic_type'] = array('neq',8);
        $clientList = M('CaseClient')->field('id,name,car_no,traffic_type,hurt_type')->where($map1)->select();
        foreach($clientList as $key => $val){
            $clientList[$key]['traffic_type'] = $traffic_type[$val['traffic_type']];
            $clientList[$key]['hurt_type'] = $hurt_type[$val['hurt_type']];
        }

        $this->assign('clientList',$clientList);
        $this->assign('cognizanceId',$cognizanceId);
        if($type == 'normal'){
            $this->display('CaseCognizance/normal/accident_identification/issue');
        }else if($type == 'unCognizance'){
            $this->display('CaseCognizance/unCognizance/road_traffic_accident_certificate/issue');
        }
    }

    /**
     * 下发数据保存
     */
    public function saveIssueInfo(){
        $Model = D('CaseCognizanceNotice');
        // 创建数据
        $data = $Model->create();
        // 开启事务
        $Model->startTrans();
        if (false === $data) {
            $this->error($Model->getError());
        }
        $data['post_time'] = strtotime($data['post_time']);
        if(I('post.id','','int') == ''){
            $id = $Model->add($data);
            // 数据保存失败
            if ($id === false) {
                $Model->rollback();
                $this->error('保存失败');
            }
        }else{
            $rs = $Model->save($data);
            if ($rs === false) {
                $Model->rollback();
                $this->error('保存失败');
            }
        }
        //当事人信息
        $map = array();
        $map['traffic_type'] = array('neq',8);
        $map['case_id'] = $this->case['id'];
        $clientList = M('CaseClient')->where($map)->select();
        //事故认定下发信息
        $map = array();
        $map['case_id'] = $this->case['id'];
        $map['case_cognizance_id'] = $data['case_cognizance_id'];
        $noticeList = M('CaseCognizanceNotice')->where($map)->select();
        $map = array();
        $map['id'] = $this->case['id'];
        if(count($clientList) == count($noticeList)){
            $map['cognizance_send_status'] = 1;
        }else{
            $map['cognizance_send_status'] = 0;
        }
        $map['update_time'] = time();
        $map['update_user_id'] = $this->my['id'];
        $rs = M('Case')->save($map);
        if($rs === false){
            $Model->rollback();
            $this->error('保存失败');
        }
        //操作日志
        $content = date('Y-m-d H:i')."将道路交通事故事故认定书送达".I('post.target_user_name');
        $this->saveCaseLog($this->case['id'],$content);
        // 成功
        $Model->commit();
        $this->success('保存成功');
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
            $this->display('CaseCognizance/normal/accident_identification/normalIssueSms');
        }else if($type == 'unCognizance'){
            $this->display('CaseCognizance/unCognizance/road_traffic_accident_certificate/unCognizanceIssueSms');
        }
    }

    /**
     * 保存一般事故认定-事故认定-下发-短信通知信息
     */
    public function saveIssueSmsInfo(){
        $list = I('post.list');
        $case_id = $this->case['id'];
        $case_cognizance_id = I('post.case_cognizance_id','','int');
        $msg_content = I('post.msg_content','','trim');
        $msg_type = I('post.msg_type','','int');
        $cate = I('post.cate','','int');
        if(is_array($list) && count($list) > 0){
            $model = D('CaseCognizanceSms');
            $model->startTrans();
            //删除历史数据
            $map = array();
            $map['case_cognizance_id'] = $case_cognizance_id;
            $rs = $model->where($map)->delete();
            if($rs === false){
                $model->rollback();
                $this->error('数据保存失败');
            }
            foreach($list as $key => $val){
                $val['cate'] = $cate;
                $val['case_id'] = $case_id;
                $val['ts_case_cognizance_id'] = $case_cognizance_id;
                $val['msg_content'] = $msg_content;
                $val['msg_type'] = $msg_type;
                $data = $model->create($val);
                if ($data === false) {
                    $model->rollback();
                    $this->error($model->getError());
                }
                $rs = $model->add($data);
                if($rs === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
            }
            $model->commit();
            if($cate == 0){
                $this->success('数据保存成功',U('issueSmsIndex?case_id='.$case_id.'&cognizance_id='.$case_cognizance_id.'&type=normal'));
            }else{
                $this->success('数据保存成功',U('issueSmsIndex?case_id='.$case_id.'&cognizance_id='.$case_cognizance_id.'&type=unCognizance'));
            }
        }else{
            $this->error('请选择当事人');
        }
    }

    /**
     * 一般事故认定-事故认定-确定并提审
     */
    public function caseCognizanceNormalAccidentIdentificationCheck() {
        $reportId = I('post.case_cognizance_report_id','','int');
        $check_user_id = I('post.check_user_id', '', 'int');
        $id = I('post.id', '', 'int');
        $cate = I('post.cate','','int');
        $case_cognizance_id = I('post.case_cognizance_id','','int');
        $model = M('CaseCognizance');
        $model->startTrans();
        //保存数据
        if($cate == 2){
            if($id === ''){
                $Model = D('CaseCognizanceNormal');
                // 创建数据
                $data = $Model->create();
                if (false === $data) {
                    $model->rollback();
                    $this->error($Model->getError());
                }
                $data['case_cognizance_report_id'] = $reportId;
                // 开启事务
                $Model->startTrans();
                $id = $Model->add($data);
                // 数据保存失败
                if ($id === false) {
                    $model->rollback();
                    $this->error('保存失败');
                }
            }else {
                //事故认定信息
                $accidentIdentificationInfo = M('CaseCognizanceNormal')->getById($id);
                //事故认定主表信息
                $cognizanceInfo = M('CaseCognizance')->getById($accidentIdentificationInfo['case_cognizance_id']);
                //事故认定主表ID
                $cognizanceId = $cognizanceInfo['id'];
                if ($cognizanceInfo['check_status'] == '2' || $cognizanceInfo['check_status'] == '3') {
                    //如果是审核未通过或者复核科退回 更新事故认定主表信息，新增事故认定-调查报告、事故认定-事故认定、事故认定-交通道路事故证明
                    //事故认定主表信息
                    $Model = D('CaseCognizance');
                    $data = array(
                        'id' => $cognizanceId,
                        'is_submit' => 0,
                        'submit_time' => time(),
                        'submit_user_id' => $this->my['id'],
                        'check_status' => 0,
                    );
                    $para = $Model->create($data);
                    $result = $Model->save($para);
                    if ($result === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                    //调查报告信息
                    //更新调查报告状态
                    $map = array();
                    $map['id'] = $reportId;
                    $map['is_last'] = 0;
                    $result = D('CaseCognizanceReport')->save($map);
                    if ($result === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                    //新增调查报告信息
                    $reportInfo = M('CaseCognizanceReport')->getById($reportId);
                    $reportModel = D('CaseCognizanceReport');
                    $data1 = array(
                        'case_cognizance_id' => $cognizanceId,
                        'base_info_client' => $reportInfo['base_info_client'],
                        'base_info_car' => $reportInfo['base_info_car'],
                        'base_info_road' => $reportInfo['base_info_road'],
                        'fact' => $reportInfo['fact'],
                        'desc' => $reportInfo['desc'],
                        'reason' => $reportInfo['reason'],
                        'cognizance' => $reportInfo['cognizance'],
                        'punish' => $reportInfo['punish'],
                        'reform' => $reportInfo['reform']
                    );
                    $para1 = $reportModel->create($data1);
                    if ($para1 === false) {
                        $model->rollback();
                        $this->error($reportModel->getError());
                    }
                    $reportId = $reportModel->add($para1);
                    if ($reportId === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                    //事故认定信息
                    $identificationModel = D('CaseCognizanceNormal');
                    $data2 = array(
                        'case_cognizance_id' => $cognizanceId,
                        'case_cognizance_report_id' => $reportId,
                        'case_id' => $this->case['id'],
                        'base_info' => I('post.base_info'),
                        'process' => I('post.process'),
                        'research' => I('post.research'),
                        'reason' => I('post.reason'),
                    );
                    $para2 = $identificationModel->create($data2);
                    $id = $identificationModel->add($para2);
                    if ($id === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                } else {
                    $Model = D('CaseCognizanceNormal');
                    $data1 = $Model->create();
                    if ($data1 === false) {
                        $model->rollback();
                        $this->error($model->getError());
                    }
                    $rs = $Model->save($data1);
                    if ($rs === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                }
            }
        }else{
            $Model = D('CaseCognizanceProve');
            // 开启事务
            $Model->startTrans();
            $data = $Model->create();
            if (false === $data) {
                $model->rollback();
                $this->error($model->getError());
            }
            $data['case_cognizance_report_id'] = $reportId;
            if($id == ''){
                $id = $Model->add($data);
                if($id === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
            }else {
                //事故交通正路证明信息
                $map = array();
                $map['case_cognizance_report_id'] = $reportId;
                $proveInfo = M('CaseCognizanceProve')->where($map)->find();
                //事故认定主表信息
                $cognizanceInfo = M('CaseCognizance')->getById($proveInfo['case_cognizance_id']);
                //事故认定主表信息
                $cognizanceId = $cognizanceInfo['id'];
                if ($cognizanceInfo['check_status'] == '2' || $cognizanceInfo['is_back'] == '1') {
                    //如果退回 更新事故认定主表信息，新增事故认定-调查报告、事故认定-事故认定、事故认定-交通道路事故证明
                    //事故认定主表信息
                    $Model = D('CaseCognizance');
                    $data = array(
                        'id' => $cognizanceId,
                        'is_submit' => 0,
                        'submit_time' => time(),
                        'submit_user_id' => $this->my['id'],
                        'check_status' => 0,
                    );
                    $para = $Model->create($data);
                    $result = $Model->save($para);
                    if ($result === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                    //调查报告信息
                    //更新调查报告状态
                    $map = array();
                    $map['id'] = $reportId;
                    $map['is_last'] = 0;
                    $result = D('CaseCognizanceReport')->save($map);
                    if ($result === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                    //新增调查报告信息
                    $reportInfo = M('CaseCognizanceReport')->getById($reportId);
                    $reportModel = D('CaseCognizanceReport');
                    $data1 = array(
                        'case_cognizance_id' => $cognizanceId,
                        'base_info_client' => $reportInfo['base_info_client'],
                        'base_info_car' => $reportInfo['base_info_car'],
                        'base_info_road' => $reportInfo['base_info_road'],
                        'fact' => $reportInfo['fact'],
                        'desc' => $reportInfo['desc'],
                        'reason' => $reportInfo['reason'],
                        'cognizance' => $reportInfo['cognizance'],
                        'punish' => $reportInfo['punish'],
                        'reform' => $reportInfo['reform']
                    );
                    $para1 = $reportModel->create($data1);
                    if ($para1 === false) {
                        $model->rollback();
                        $this->error($reportModel->getError());
                    }
                    $reportId = $reportModel->add($para1);
                    if ($reportId === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                    //交通道路事故证明
                    $proveModel = D('CaseCognizanceProve');
                    $data3 = array(
                        'case_cognizance_id' => $cognizanceId,
                        'case_cognizance_report_id' => $reportId,
                        'case_id' => $this->case['id'],
                        'base_info' => I('post.base_info'),
                        'fact' => I('post.fact'),
                    );
                    $para3 = $proveModel->create($data3);
                    $id = $proveModel->add($para3);
                    if ($id === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                } else {
                    $Model = D('CaseCognizanceProve');
                    $rs = $Model->save($data);
                    if ($rs === false) {
                        $model->rollback();
                        $this->error('数据保存失败');
                    }
                }
            }
        }

        $map = array();
        $map['id'] = $case_cognizance_id;
        $result = $model->where($map)->save(array('is_submit'=>1));
        if($result === false){
            $model->rollback();
            $this->error('提请审批失败');
        }
        $rs = $this->submitCheck($check_user_id, $cate, $id);
        if ($rs === false) {
            $model->rollback();
            $this->error('提请审批失败');
        } else {
            $map = array();
            $map['id'] = $this->case['id'];
            $map['cognizance_check_status'] = 1;
            $map['update_time'] = time();
            $map['update_user_id'] = $this->my['id'];
            $rs = M('Case')->save($map);
            if($rs === false){
                $model->rollback();
                $this->error('提请审批失败');
            }
            //操作日志
            $content = "办案人".$this->my['true_name']."在".date('Y-m-d H:i')."制作道路交通事故调查报告提交审批";
            $this->saveCaseLog($this->case['id'],$content);
            $model->commit();
            if($cate == 2){
                $this->success('提请审批成功', U('normalIndex?case_id='.$this->case['id'].'&action=accident_identification&report_id='.$reportId.'&id='.$id));
            }else{
                $this->success('提请审批成功', U('unCognizanceIndex?case_id='.$this->case['id'].'&action=road_traffic_accident_certificate&report_id='.$reportId.'&id='.$id));
            }
        }
    }

    /**
     * 保存一般事故认定-呈请事故中止
     */
    public function saveStopInfo(){
        $reportId = I('post.report_id','','int');
        $id = I('post.id','','int');
        $Model = D('CaseCognizanceStop');
        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $this->error($Model->getError());
        }
        // 开启事务
        $Model->startTrans();
        if($id  == ""){
            $rs = $Model->add($data);
        }else{
            $rs = $Model->save($data);
        }
        // 数据保存失败
        if ($rs === false) {
            $Model->rollback();
            $this->error('保存失败');
        }
        //更新时 $rs 为画面主键
        if($id !== ""){
            $rs = $id ;
        }
        //操作日志
        $content = date('Y-m-d H:i').'事故认定中止';
        $this->saveCaseLog($this->case['id'],$content);
        // 成功
        $Model->commit();
        if(I('post.type') == "normal"){
            $this->success('保存成功',U('normalIndex?case_id='.$this->case['id'].'&action=accident_termination&report_id='.$reportId.'&id='.$rs));
        }else if(I('post.type') == "unCognizance"){
            $this->success('保存成功',U('unCognizanceIndex?case_id='.$this->case['id'].'&action=accident_termination&report_id='.$reportId.'&id='.$rs));
        }
    }

    /**
     * 一般事故认定-呈请事故中止-提审
     */
    public function saveNormalCognizanceStopCheck(){
        $check_user_id = I('post.check_user_id', '', 'int');
        $id = I('post.id', '', 'int');
        $cate = I('post.cate');
        $model = D('CaseCognizanceStop');
        $map = array();
        $map['id'] = $id;
        $map['is_submit'] = 1;
        $map['content'] = I('post.content');
        $map['update_user_id'] = $this->my['id'];
        $map['update_time'] = time();
        $result = $model->save($map);
        if($result === false){
            $model->rollback();
            $this->error('提请审批失败');
        }
        $rs = $this->submitCheck($check_user_id, $cate, $id);
        if ($rs === false) {
            $model->rollback();
            $this->error('提请审批失败');
        } else {
            //操作日志
            $content = date('Y-m-d H:i').'事故认定中止';
            $this->saveCaseLog($this->case['id'],$content);
            $model->commit();
            $this->success('提请审批成功');
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
        $map = array();
        $map['pid'] = $id;
        $model = D('CaseCheckView');
        $checkList1 = $model->where($map)->select();
        $list = array();
        if(count($checkList1) > 0){
            $map = array();
            $map['pid'] = $checkList1[0]['id'];
            $model = D('CaseCheckView');
            $checkList2 = $model->where($map)->select();
            $list = array_merge($checkList,$checkList1);
            if(count($checkList2) > 0){
                $list = array_merge($list,$checkList2);
            }
        }else{
            $list = $checkList;
        }
        $array = array('待审核', '通过', '拒绝');
        foreach ($list as $key => $val) {
            $list[$key]['status'] = $array[$val['status']];
        }
		usort($list,function($a,$b){
			return $a['create_time'] <= $b['create_time'];
		});
        $this->assign('stopInfo',$stopInfo);
        $this->assign('checkList',$list);
        if($type == 'normal'){
            $this->display('CaseCognizance/normal/accident_termination/checkView');
        }else if($type == "unCognizance"){
            $this->display('CaseCognizance/unCognizance/accident_termination/checkView');
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
            $this->assign('stopId',$stopInfo['id']);
            $this->assign('historyList',$historyList);
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
     * 保存交通道路证明信息
     */
    public function saveCognizanceProve(){
        $id = I('post.id','','int');
        $reportId = I('post.case_cognizance_report_id','','int');
        $model = D('CaseCognizanceProve');
        // 开启事务
        $model->startTrans();
        $data = $model->create();
        if (false === $data) {
            $this->error($model->getError());
        }
        if($id == ''){
            $rs = $model->add($data);
            if($rs === false){
                $model->rollback();
                $this->error('数据保存失败');
            }
            $model->commit();
            $this->success('数据保存成功',U('unCognizanceIndex?case_id='.$this->case['id'].'&action=road_traffic_accident_certificate&report_id='.$reportId.'&id='.$rs));
        }else{
            //事故交通正路证明信息
            $map = array();
            $map['case_cognizance_report_id'] = $reportId;
            $proveInfo = M('CaseCognizanceProve')->where($map)->find();
            //事故认定主表信息
            $cognizanceInfo = M('CaseCognizance')->getById($proveInfo['case_cognizance_id']);
            //事故认定主表信息
            $cognizanceId = $cognizanceInfo['id'];
            if($cognizanceInfo['check_status'] == '2' || $cognizanceInfo['is_back'] == '1'){
                //如果退回 更新事故认定主表信息，新增事故认定-调查报告、事故认定-事故认定、事故认定-交通道路事故证明
                //事故认定主表信息
                $model = D('CaseCognizance');
                $model->startTrans();
                $data = array(
                    'id' => $cognizanceId,
                    'is_submit' => 0,
                    'submit_time' => time(),
                    'submit_user_id' => $this->my['id'],
                    'check_status' => 0,
                );
                $para = $model->create($data);
                $result = $model->save($para);
                if($result === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                //调查报告信息
                //更新调查报告状态
                $map = array();
                $map['id'] = $reportId;
                $map['is_last'] = 0;
                $result = D('CaseCognizanceReport')->save($map);
                if($result === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                //新增调查报告信息
                $reportInfo = M('CaseCognizanceReport')->getById($reportId);
                $reportModel = D('CaseCognizanceReport');
                $data1 = array(
                    'case_cognizance_id' => $cognizanceId,
                    'base_info_client' => $reportInfo['base_info_client'],
                    'base_info_car' => $reportInfo['base_info_car'],
                    'base_info_road' => $reportInfo['base_info_road'],
                    'fact' => $reportInfo['fact'],
                    'desc' => $reportInfo['desc'],
                    'reason' => $reportInfo['reason'],
                    'cognizance' => $reportInfo['cognizance'],
                    'punish' => $reportInfo['punish'],
                    'reform' => $reportInfo['reform']
                );
                $para1 =  $reportModel->create($data1);
                if($para1 === false){
                    $this->error($reportModel->getError());
                }
                $reportId = $reportModel->add($para1);
                if($reportId === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                //交通道路事故证明
                $proveModel = D('CaseCognizanceProve');
                $proveInfo = M('CaseCognizanceProve')->getById($id);
                $data3 = array(
                    'case_cognizance_id' => $cognizanceId,
                    'case_cognizance_report_id' => $reportId,
                    'case_id' => $this->case['id'],
                    'base_info' => I('post.base_info'),
                    'fact' => I('post.fact'),
                );
                $para3 = $proveModel->create($data3);
                $proveId = $proveModel->add($para3);
                if($proveId === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                $model->commit();
                $this->success('数据保存成功',U('unCognizanceIndex?case_id='.$this->case['id'].'&action=road_traffic_accident_certificate&report_id='.$reportId.'&id='.$proveId));
            }else{
                $rs = $model->save($data);
                if($rs === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
                $model->commit();
                $this->success('数据保存成功',U('unCognizanceIndex?case_id='.$this->case['id'].'&action=road_traffic_accident_certificate&report_id='.$reportId.'&id='.$id));
            }
        }
    }

    /**
     * 制作
     */
    public function make(){
        $cognizanceId = I('post.cognizanceId','','int');
        $map = array();
        $map['id'] = $cognizanceId;
        $map['is_make'] = 1;
        $cognizanceModel = M('CaseCognizance');
        $cognizanceModel->startTrans();
        $rs = M('CaseCognizance')->save($map);
        if($rs === false){
            $cognizanceModel->rollback();
            $this->error('制作失败');
        }
        $map = array();
        $map['is_over'] = 1;
        $map['id'] = $this->case['id'];
        $rs = D('Case')->save($map);
        if($rs === false){
            $cognizanceModel->rollback();
            $this->error('制作失败');
        }
        $map = array();
        $map['id'] = $this->case['id'];
        $map['cognizance_check_status'] = 6;
        $map['update_time'] = time();
        $map['update_user_id'] = $this->my['id'];
        $rs = M('Case')->save($map);
        if($rs === false){
            $cognizanceModel->rollback();
            $this->error('提请审批失败');
        }
        //操作日志
        $content = date('Y-m-d H:i')."制作".'0001'.'道路交通事故事故认定书';
        $this->saveCaseLog($this->case['id'],$content);
        $cognizanceModel->commit();
        $this->success('制作成功');
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
     * 保存逃逸事故认定
     */
    public function saveEscapeInfo(){
        $Model = D('CaseCoescape');
        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $this->error($Model->getError());
        }
        $map = array();
        $map['case_client_id'] = I('post.case_client_id','','int');
        $info = M('CaseCoescape')->where($map)->find();
        if(!empty($info)){
            $this->error('申请人已存在，不能重复申请');
        }
        if(I('post.apply_time') == ''){
            if(I('post.base_info') == ''){
                $this->error('请输入当事人、车辆、道路和交通环境等基本情况');
            }
            if(I('post.process') == ''){
                $this->error('请输入道路交通事故发生经过');
            }
            if(I('post.analyse') == ''){
                $this->error('请输入道路交通事故证据及事故形成原因分析');
            }
            if(I('post.reason') == ''){
                $this->error('请输入当事人导致交通事故的过错及责任或者意外原因');
            }
        }
        // 开启事务
        $Model->startTrans();
        if(I('post.id','','int') == ""){
            $data['apply_time'] = strtotime($data['apply_time']);
            $rs = $Model->add($data);
        }else{
            $rs = $Model->save($data);
        }
        // 数据保存失败
        if ($rs === false) {
            $Model->rollback();
            $this->error('保存失败');
        }
        if(I('post.base_info') !== ''){
            //操作日志
            $content = "办案人".$this->my['true_name']."在".date('Y-m-d H:i')."制作了逃逸事故认定书";
            $this->saveCaseLog($this->case['id'],$content);
        }
        // 成功
        $Model->commit();
        $this->success('保存成功');
    }

    /**
     * 逃逸事故认定-书面材料画面加载
     */
    public function escapePhotoList(){
        $id = I('get.id');
        $this->assign('id',$id);
        $this->display('CaseCognizance/escape/photoTable');
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
        $this->display('CaseCognizance/escape/list');
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
        $this->display('CaseCognizance/escape/escapeInfo');
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
        $this->display('CaseCognizance/escape/escapeIssue');
    }

    /**
     * 保存逃逸-送达回执信息
     */
    public function saveEscapeIssueInfo(){
        $Model = D('CaseCoescapeNotice');
        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $this->error($Model->getError());
        }
        $data['post_time'] = strtotime($data['post_time']);
        // 开启事务
        $Model->startTrans();
        if(I('post.id','','int') == ""){
            $rs = $Model->add($data);
        }else{
            $rs = $Model->save($data);
        }
        // 数据保存失败
        if ($rs === false) {
            $Model->rollback();
            $this->error('保存失败');
        }
        //操作日志
        $content = date('Y-m-d H:i')."将逃逸事故认定书送达".I('post.target_user_name');
        $this->saveCaseLog($this->case['id'],$content);
        // 成功
        $Model->commit();
        $this->success('保存成功');
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
        $this->display('CaseCognizance/escape/escapeIssueSms');
    }

    /**
     * 保存逃逸事故下发-短信通知
     */
    public function saveEscapeIssueSmsInfo(){
        $list = I('post.list');
        $case_id = $this->case['id'];
        $ts_case_coescape_id = I('post.ts_case_coescape_id','','int');
        $msg_content = I('post.msg_content','','trim');
        $msg_type = I('post.msg_type','','int');
        if(is_array($list) && count($list) > 0){
            $model = D('CaseCoescapeSms');
            $model->startTrans();
            foreach($list as $key => $val){
                $val['case_id'] = $case_id;
                $val['ts_case_coescape_id'] = $ts_case_coescape_id;
                $val['msg_content'] = $msg_content;
                $val['msg_type'] = $msg_type;
                $data = $model->create($val);
                if ($data === false) {
                    $model->rollback();
                    $this->error($model->getError());
                }
                $rs = $model->add($data);
                if($rs === false){
                    $model->rollback();
                    $this->error('数据保存失败');
                }
            }
            $model->commit();
            $this->success('数据保存成功',U('escapeIssueIndex?case_id='.$case_id.'&id='.$ts_case_coescape_id ));
        }else{
            $this->error('请选择当事人');
        }
    }

    /**
     * 加载图片
     */
    public function photoList(){
        $cate = I('post.cate','','int');
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('CaseCognizance/simple/photoTable');
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
            $this->display('CaseCognizance/unCognizance/investigation_report/checkView');
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
            $this->display('CaseCognizance/unCognizance/investigation_report/backView');
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
            $this->display('CaseCognizance/normal/investigation_report/reviewView');
        }else if($type == "unCognizance"){
            $this->display('CaseCognizance/unCognizance/investigation_report/reviewView');
        }
    }

    /**
     * 提请审批验证
     */
    public function approvalCheck(){
        $cate = I('post.cate','','int');
        if($cate == 2){
            $model = D('CaseCognizanceNormal');
        }else{
            $model = D('CaseCognizanceProve');
        }
        $result = $model->create();
        if($result === false){
            $this->error($model->getError());
        }
        $this->success('验证通过');
    }
}
?>