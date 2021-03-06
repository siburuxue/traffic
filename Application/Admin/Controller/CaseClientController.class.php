<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 当事人
 */
class CaseClientController extends CaseController {

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
        $data['blood_time'] = strtotime($data['blood_time']);
        $data['detain_time'] = strtotime($data['detain_time']);
        $data['detain_return_time'] = strtotime($data['detain_return_time']);

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

        //相关人员信息
        $relaterList = I('post.client_relaters');
        $relaterModel = D('CaseClientRelater');
        if (is_array($relaterList) && count($relaterList) > 0) {
            foreach ($relaterList as $k => $v) {
                $_POST['i'] = $k;
                if($v['flg'] == "delete"){
                    $map2 = array();
                    $map2['id'] = $v['id'];
                    $res = M('CaseClientRelater')->where($map2)->delete();
                }else{
                    $subItem = $relaterModel->create($v);
                    if ($subItem === false) {
                        $Model->rollback();
                        $this->error('相关人员信息第' . ($k + 1) . '行：' . $relaterModel->getError());
                    }
                    if($v['id'] == ""){
                        $res = M('CaseClientRelater')->add($subItem);
                    }else{
                        $res = M('CaseClientRelater')->save($subItem);
                    }
                }
                if ($res === false) {
                    $Model->rollback();
                    $this->error('相关人员信息数据保存失败');
                }
            }
        }
        //号牌种类
        $grade_type = get_custom_config('grade_type');
        //操作日志
        if(I('post.blood_time') !== ''){
            $content = I('post.blood_time')."提取".I('post.name')."血样";
            $this->saveCaseLog($this->case['id'],$content);
        }
        if(I('post.detain_time') !== ''){
            $content = I('post.detain_time')."扣留".(I('post.car_no') == "" ? "车牌号未填写":I('post.car_no')."号").(I('post.grade_type') == "" ? "车辆类型未填写" : str_replace('号牌','',$grade_type[I('post.grade_type')]));
            $this->saveCaseLog($this->case['id'],$content);
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
            $rs = M('Law')->where($map)->select();
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
                $detainList[$key]['return_time'] = date('Y-m-d H:i',$val['return_time']);
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
     * 扣押
     */
    public function seizure(){
        $Model = D('CaseClientDetain');
        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $this->error($Model->getError());
        }
        $data['detain_time'] = strtotime($data['detain_time']);
        $id = $Model->add($data);
        if ($id === false) {
            $Model->rollback();
            $this->error($Model->getLastSql());
        }
        $Model->commit();
        $this->success($id);
    }

    /**
     * 返还
     */
    public function reback(){
        $id = I('post.id');
        $returnTime = I('post.return_time');
        if($returnTime == ''){
            $this->error('请选择返还时间');
        }
        $map = array();
        $map['id'] = $id;
        $data = array();
        $data['return_time'] = strtotime($returnTime);
        $data['return_user_id'] = $this->my['id'];
        $data['status'] = 1;
        $rs = M('CaseClientDetain')->where($map)->save($data);
        if($rs === false){
            $this->error('操作失败');
        }else{
            $this->success('操作成功');
        }
    }

    /**
     * 添加采血管编辑页面加载
     */
    public function blood(){
        $array = array();
        $id = I('get.id');
        $Model = D('BloodtubeCateView');
        $map = array();
        $map['BloodtubeCate.case_client_id'] = $id;
        $map['BloodtubeCate.is_del'] = 0;
        $map['Bloodtube.is_del'] = 0;
        $map['Bloodtube.is_recover'] = 0;
        $list = $Model->where($map)->order('BloodtubeCate.update_time')->select();
        for($i = 0;$i < count($list);$i++){
            if($i % 2 == 0){
                array_push($array,array(
                    'i' => ($i / 2) + 1,
                    'used_time' => $list[$i]['used_time1'],
                    'bloodtube_cate_id' => $list[$i]['bloodtube_cate_id'],
                    'code1' => $list[$i]['code']
                ));
            }else{
                $array[($i - 1) / 2]['code2'] = $list[$i]['code'];
            }
        }
        $array = json_encode($array);
        $array = str_replace("'","\u0027",$array);
        $this->assign('json',$array);
        $this->assign('id',$id);
        $this->display('CaseClient/edit/blood');
    }

    /**
     * 执行添加采血管信息
     */
    public function insertBlood(){
        $data = I('post.bloodList');
        if(!is_array($data) || count($data) == 0){
            $this->error('请输入采血管信息');
        }
        $client_id = $data[0]['case_client_id'];
        $model = M('BloodtubeCate');
        $model->startTrans();
        //清空历史数据
        $map2 = array();
        $map2['case_client_id'] = $client_id;
        $data1 = array();
        $data1['used_time'] = 0;
        $data1['case_client_id'] = 0;
        $data1['used_user_id'] = 0;
        $data1['case_id'] = 0;
        $data1['is_used'] = 0;
        $data1['update_time'] = time();
        $data1['update_user_id'] = $this->my['id'];
        $result1 = $model->where($map2)->save($data1);
        if($result1 === false){
            $model->rollback();
            $this->error('数据保存失败');
        }

        foreach($data as $key => $val){
            $case_client_id = $val['case_client_id'];
            $case_id = $val['case_id'];
            //采血管ID
            $id = $val['id'];
            $code = $val['code'];
            $used_time = $val['used_time'];
            if($used_time === ""){
                $this->error('第'.($key + 1)."行提取时间不能为空");
            }
            if($code === ""){
                $this->error('第'.($key + 1)."行第一个采血管不能为空");
            }
            $rs = $model->getById($id);
            if($rs['is_to_department'] == "0"){
                $this->error("第".($key +1)."行采血管未被派发到大队");
            }else if($rs['to_user_user_id'] != $this->my['id']){
                $this->error("第".($key +1)."行采血管未指派给当前用户使用");
            }else if($rs['is_used'] == '1'){
                $this->error("第".($key +1)."行采血管已经被使用");
            }
            $data = array();
            $data['used_time'] = strtotime($used_time);
            $data['case_client_id'] = $case_client_id;
            $data['used_user_id'] = $this->my['id'];
            $data['case_id'] = $case_id;
            $data['is_used'] = 1;
            $data['update_time'] = time();
            $data['update_user_id'] = $this->my['id'];
            $map1 = array();
            $map1['id'] = $id;
            $result12 = $model->where($map1)->save($data);
            if($result12 === false){
                $model->rollback();
                $this->error('数据保存失败');
            }
        }
        $model->commit();
        $this->success('数据保存成功');
    }

    /**
     * 获得采血管编号
     */
    public function getCode(){
        $code = I('post.code');
        $map = array();
        $map['code'] = $code;
        $rs = M('Bloodtube')->where($map)->find();
        if(empty($rs)){
            $this->error('采血管编号不存在');
        }
        $map1 = array();
        $map1['bloodtube_cate_id'] = $rs['bloodtube_cate_id'];
        $map1['code'] = array('neq',$code);
        $rs1 = M('Bloodtube')->where($map1)->find();
        $this->success(json_encode($rs1));
    }

    /**
     * 编辑获得当事人的采血管
     */
    public function getBloodInfo(){
        $id = I('post.id');
        $map = array();
        $map['BloodtubeCate.case_client_id'] = $id;
        $map['BloodtubeCate.is_del'] = 0;
        $map['Bloodtube.is_del'] = 0;
        $map['Bloodtube.is_recover'] = 0;
        $list = D('BloodtubeCateView')->where($map)->order('BloodtubeCate.update_time')->select();
        echo json_encode($list);
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