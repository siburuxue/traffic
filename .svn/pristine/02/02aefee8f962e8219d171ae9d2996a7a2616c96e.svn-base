<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 当事人
 */
class CaseClientInfoController extends CaseDetailController {

    /**
     * 编辑页加载
     */
    public function detail(){
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
        $law_pid = M('LawRegulation')->where($map)->select();
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
        //提取时间
        if($info['blood_time'] != "0"){
            $info['blood_time'] = date('Y-m-d H:i',$info['blood_time']);
        }else{
            $info['blood_time'] = '';
        }
        //返还时间
        if($info['detain_return_time'] != "0"){
            $info['detain_return_time'] = date('Y-m-d',$info['detain_return_time']);
        }else{
            $info['detain_return_time'] = '';
        }
        //扣留时间
        if($info['detain_time'] != "0"){
            $info['detain_time'] = date('Y-m-d',$info['detain_time']);
        }else{
            $info['detain_time'] = '';
        }
        //罚款金额
        if($info['punish_money'] == "0.00"){
            $info['punish_money'] = "";
        }
        //扣分
        if($info['punish_score'] == "0"){
            $info['punish_score'] = "";
        }
        //当事人法律条款信息
        $map2 = array();
        $map2['case_client_id'] = $id;
        $lawList = M('CaseClientLaw')->where($map2)->select();
        foreach($lawList as $key => $val){
            $map = array();
            $map['is_del'] = 0;
            $map['pid'] = $val['law_pid'];
            $rs = M('LawRegulation')->where($map)->select();
            $lawList[$key]['law_id_list'] = $rs;
        }
        //当事人相关人列表
        $relationList = M('CaseClientRelater')->where($map2)->select();
        //物品扣压列表
        $detainList = M('CaseClientDetain')->where($map2)->select();
        //物品名称字典
        $item_name = get_custom_config('item_name');

        foreach($detainList as $key => $val){
            $detainList[$key]['detain_time'] = date('Y-m-d',$val['detain_time']);
            if($val['return_time'] != "0"){
                $detainList[$key]['return_time'] = date('Y-m-d',$val['return_time']);
            }else{
                $detainList[$key]['return_time'] = '';
            }
            $name_id = (int)$val['name_id'];
            $name = $val['name'];
            $detainList[$key]['name'] = $item_name[$name_id].' '.$name;
        }
        //采血管信息
        $Model = D('BloodtubeCateView');
        $map = array();
        $map['BloodtubeCate.case_client_id'] = $id;
        $map['BloodtubeCate.is_del'] = 0;
        $map['Bloodtube.is_del'] = 0;
        $map['Bloodtube.is_recover'] = 0;
        $bloodList = $Model->where($map)->order('BloodtubeCate.update_time')->select();
        //获取图片信息
        $photoList = parent::getPhotoList(2,$id);
        $data = array(
            'info'=>$info,
            'lawList' => $lawList,
            'relationList' => $relationList,
            'detainList' => $detainList,
            'bloodList' => $bloodList,
            'photoList' => $photoList,
        );
        echo json_encode($data);
    }

    /**
     * 获取图片列表
     */
    public function photoList(){
        $cate = 2;
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->success($list);
    }
}