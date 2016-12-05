<?php
namespace Admin\Controller;

/**
 * 快赔-简易事故认定
 */
class FastCaseCognizanceController extends FastCommonController {
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
}
