<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 快赔-当事人
 */
class FastCaseClientController extends FastCommonController {
    /**
     * 首页
     */
    public function add() {
        $map = array();
        $map['pid'] = 0;
        $law_pid = M('Law')->where($map)->select();
        $this->assign('certificate_type',get_custom_config('certificate_type'));
        $this->assign('driver_licence_type',get_custom_config('driver_licence_type'));
        $this->assign('traffic_type',get_custom_config('traffic_type'));
        $this->assign('grade_type',get_custom_config('grade_type'));
        $this->assign('blame_type',get_custom_config('blame_type'));
        $this->assign('hurt_type',get_custom_config('hurt_type'));
        $this->assign('rights_obligations',get_custom_config('rights_obligations'));
        $this->assign('law_pid',$law_pid);
        // 渲染
        $this->display();
    }

    /**
     * 执行新增
     */
    public function insert(){
        $case_id = $this->case['id'];
        $Model = D('CaseClient');
        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $this->error($Model->getError());
        }

        // 转换时间格式
        $data['first_driver_licence_time'] = strtotime($data['first_driver_licence_time']);
        $data['death_time'] = strtotime($data['death_time']);
        $data['escape_catch_man_time'] = strtotime($data['escape_catch_man_time']);
        $data['escape_catch_car_time'] = strtotime($data['escape_catch_car_time']);

        // 开启事务
        $Model->startTrans();
        $id = $Model->add($data);

        // 数据保存失败
        if (!$id) {
            $Model->rollback();
            $this->error('数据保存失败');
        }
        $_POST['client_id'] = $id;
        // 违法行为及条款
        $lawModel = D('CaseClientLaw');
        $law = I('post.law');
        if (is_array($law) && count($law) > 0) {
            $subData = array();
            foreach ($law as $k => $v) {
                $v['case_client_id'] = $id;

                $subItem = $lawModel->create($v);
                if ($subItem === false) {
                    $Model->rollback();
                    $this->error('违法行为及条款' . ($k + 1) . '行：' . $lawModel->getError());
                }
                $subData[] = $subItem;
            }
            $res = M('CaseClientLaw')->addAll($subData);

            if (!$res) {
                $Model->rollback();
                $this->error('违法行为及条款数据保存失败');
            }
        }
        $map = array();
        $map['id'] = $this->case['id'];
        $map['client_status'] = 1;
        $caseRs = D('Case')->save($map);
        if ($caseRs === false) {
            $Model->rollback();
            $this->error('数据保存失败');
        }
        // 成功
        $Model->commit();
        $this->success('新增成功', U("edit?case_id={$case_id}&id=" . $id));
    }

    /**
     * 编辑页加载
     */
    public function edit(){
        $id = I('get.id','','int');
        if($id == ""){
            $this->error('非法访问');
        }
        $map1 = array();
        $map1['id'] = $id;
        $info = M('CaseClient')->where($map1)->find();
        if(empty($info)){
            $this->error('当事人信息不存在');
        }
        //画面字典项
        $map = array();
        $map['pid'] = 0;
        $traffic_type = get_custom_config('traffic_type');
        $hurt_type = get_custom_config('hurt_type');
        $law_pid = M('Law')->where($map)->select();
        $this->assign('certificate_type',get_custom_config('certificate_type'));
        $this->assign('driver_licence_type',get_custom_config('driver_licence_type'));
        $this->assign('traffic_type',$traffic_type);
        $this->assign('grade_type',get_custom_config('grade_type'));
        $this->assign('blame_type',get_custom_config('blame_type'));
        $this->assign('hurt_type',$hurt_type);
        $this->assign('rights_obligations',get_custom_config('rights_obligations'));
        $this->assign('client_relation',get_custom_config('client_relation'));
        $this->assign('item_name',get_custom_config('item_name'));
        $this->assign('revoke_years',get_custom_config('revoke_years'));
        $this->assign('criminal_coercive_measures',get_custom_config('criminal_coercive_measures'));
        $this->assign('parking_lot',get_custom_config('parking_lot'));
        $this->assign('law_pid',$law_pid);
        //获取当事人列表
        $case_id = $this->case['id'];
        $map2 = array();
        $map2['case_id'] = $case_id;
        $map2['traffic_type'] =8 ;
        $clientList = M('CaseClient')->where($map2)->order('id desc')->select();
        foreach($clientList as $key => $value){
            $clientList[$key]['traffic_type'] = $traffic_type[(int)$clientList[$key]['traffic_type']];
            $clientList[$key]['hurt_type'] = $hurt_type[(int)$clientList[$key]['hurt_type']];
        }

        $map3 = array();
        $map3['case_id'] = $case_id;
        $map3['traffic_type'] = array('neq',8);
        $clientList1 = M('CaseClient')->where($map3)->order('id desc')->select();
        foreach($clientList1 as $key => $value){
            $clientList1[$key]['traffic_type'] = $traffic_type[(int)$clientList1[$key]['traffic_type']];
            $clientList1[$key]['hurt_type'] = $hurt_type[(int)$clientList1[$key]['hurt_type']];
        }
        $clientList = array_merge($clientList1,$clientList);
        $this->assign('clientList',$clientList);
        $this->assign('id',$id);
        $this->display();
    }

    /**
     * 执行编辑
     */
    public function update(){
        $Model = D('CaseClient');
        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $this->error($Model->getError());
        }
        // 当事人主键
        $id = $data['id'];

        // 当事人信息
        $info = M('CaseClient')->getById($id);
        if (empty($info)) {
            $this->error('报警信息不存在');
        }

        // 转换时间格式
        $data['first_driver_licence_time'] = strtotime($data['first_driver_licence_time']);
        $data['death_time'] = strtotime($data['death_time']);
        $data['escape_catch_man_time'] = strtotime($data['escape_catch_man_time']);
        $data['escape_catch_car_time'] = strtotime($data['escape_catch_car_time']);

        // 开启事务
        $Model->startTrans();
        $result = $Model->save($data);

        // 数据保存失败
        if ($result === false) {
            $Model->rollback();
            $this->error('数据保存失败');
        }

        $map = array();
        $map['case_client_id'] = $id;

        //违法行为及条款
        $lawList = I('post.law');
        $lawModel = D('CaseClientLaw');
        M('CaseClientLaw')->where($map)->delete();
        if (is_array($lawList) && count($lawList) > 0) {
            $subData = array();
            foreach ($lawList as $k => $v) {
                $subItem = $lawModel->create($v);
                if ($subItem === false) {
                    $Model->rollback();
                    $this->error('违法行为及条款第' . ($k + 1) . '行：' . $lawModel->getError());
                }
                $subItem['case_client_id'] = $id;
                $subData[] = $subItem;
            }
            $res = M('CaseClientLaw')->addAll($subData);

            if (!$res) {
                $Model->rollback();
                $this->error('违法行为及条款数据保存失败');
            }
        }
        $Model->commit();
        $this->success('编辑成功',U('edit?case_id='.$this->case['id'].'&id='.$id));
    }

    /**
     * 大类联动小类
     */
    public function getSubTitle(){
        $pid = I('post.pid');
        $map = array();
        $map['is_del'] = 0;
        $map['pid'] = $pid;
        $rs = M('Law')->where($map)->select();
        echo json_encode($rs);
    }

    /**
     * 获取当事人详细信息
     */
    public function getClientInfo(){
        //当事人信息
        $id = I('post.id','','int');
        if($id == ""){
            $this->error('非法访问');
        }
        $map1 = array();
        $map1['id'] = $id;
        $info = M('CaseClient')->where($map1)->find();
        if(empty($map1)){
            $this->error('当事人信息不存在');
        }
        //死亡时间
        if($info['death_time'] != "0"){
            $info['death_time'] = date('Y-m-d H:i',$info['death_time']);
        }else{
            $info['death_time'] = '';
        }
        //查获逃逸人时间
        if($info['escape_catch_man_time'] != "0"){
            $info['escape_catch_man_time'] = date('Y-m-d H:i',$info['escape_catch_man_time']);
        }else{
            $info['escape_catch_man_time'] = '';
        }
        //初次领证日期
        if($info['first_driver_licence_time'] != "0"){
            $info['first_driver_licence_time'] = date('Y-m-d',$info['first_driver_licence_time']);
        }else{
            $info['first_driver_licence_time'] = '';
        }
        //查获逃逸车辆时间
        if($info['escape_catch_car_time'] != "0"){
            $info['escape_catch_car_time'] = date('Y-m-d H:i',$info['escape_catch_car_time']);
        }else{
            $info['escape_catch_car_time'] = '';
        }
        //当事人法律条款信息
        $map2 = array();
        $map2['case_client_id'] = $id;
        $lawList = M('CaseClientLaw')->where($map2)->select();
        foreach($lawList as $key => $val){
            $map = array();
            $map['is_del'] = 0;
            $map['pid'] = $val['law_pid'];
            $rs = M('Law')->where($map)->select();
            $lawList[$key]['law_id_list'] = $rs;
        }
        $data = array(
            'info'=>$info,
            'lawList' => $lawList
        );
        echo json_encode($data);
    }
}