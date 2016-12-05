<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 询问笔录
 */
class CaseRecordHandleController extends CaseController {
    /**
     * 列表页
     */
    public function index(){
        $this->assign('record_type',get_custom_config('record_type'));
        $this->display();
    }

    /**
     * 列表页数据加载
     */
    public function indexTable(){
        $case_id = $this->case['id'];
        $condition = get_condition();
        $map = array();
        $map['record_case_id'] = $case_id;
        $map['is_del'] = 0;
        if (is_time($condition['start_time']) && is_time($condition['end_time'])) {
            $map['alarm_time'] = array(array('egt', strtotime($condition['start_time'])), array('elt', strtotime($condition['end_time'])));
        } else if (is_time($condition['end_time'])) {
            $map['alarm_time'] = array(array('elt', strtotime($condition['end_time'])));
        } else if (is_time($condition['start_time'])) {
            $map['alarm_time'] = array(array('egt', strtotime($condition['start_time'])));
        }
        isset($condition['name']) && $map['name'] = array('LIKE','%'.$condition['name'].'%');
        isset($condition['record_type']) && $map['record_type'] = $condition['record_type'];

        $Model = D('CaseRecordHandleView');
        $count = $Model->where($map)->count('CaseRecord.id');
        $page = new Page($count, 15);
        $list = $Model->where($map)->order('CaseRecord.create_time desc')->limit($page->firstrow . ',' . $page->rows)->group('CaseRecord.id')->select();
        foreach($list as $key => $val){
            switch($val['record_type']){
                case "0":
                    $photoList = parent::getPhotoList(61,$val['id']);
                    $list[$key]['num'] = count($photoList);
                    $list[$key]['record_type'] = '询问笔录';
                    break;
                case "1":
                    $photoList = parent::getPhotoList(62,$val['id']);
                    $list[$key]['num'] = count($photoList);
                    $list[$key]['record_type'] = '讯问笔录';
                    break;
                case "2":
                    $photoList = parent::getPhotoList(63,$val['id']);
                    $list[$key]['num'] = count($photoList);
                    $list[$key]['record_type'] = '谈话记录';
                    break;
            }
        }
        $this->assign('list', $list);
        // 分页信息
        $pageInfo = array(
            'totalrows' => $count,
            'totalpage' => $page->totalpages,
            'nowpage' => $page->nowpage,
        );
        $this->assign('page', $pageInfo);
        $this->display('CaseRecordHandle/index/table');
    }

    /**
     * 新增页面加载
     */
    public function add(){
        $relaterArray = array();
        $case_id = $this->case['id'];
        $map = array();
        $map['is_del'] = 0;
        $map['case_id'] = $case_id;
        $map['traffic_type'] = array('neq',8);
        //当事人
        $client = M('CaseClient')->field('id,name')->where($map)->select();
        //当事人相关人
        foreach ($client as $key => $val){
            $map1 = array();
            $map1['case_client_id'] = $val['id'];
            $clientRelater = M('CaseClientRelater')->field('id,name')->where($map1)->select();
            $relaterArray = array_merge($relaterArray,$clientRelater);
        }
        //证人
        $map3 = array();
        $map3['case_id'] = $case_id;
        $witness = M('CaseWitness')->field('id,name')->where($map3)->select();
        $this->assign('client',$client);
        $this->assign('clientRelater',$relaterArray);
        $this->assign('witness',$witness);
        $this->assign('case_id',$case_id);
        $this->assign('certificate_type',get_custom_config('certificate_type'));
        $this->display();
    }

    /**
     * 执行新增
     */
    public function insert(){
        $witnessName = I('post.witness_name');
        $type = I('post.record_type','','int');
        $array = array('询问','讯问','谈话');
        // 实例化模型
        $Model = D('CaseRecord');
        $witnessModel = D('CaseWitness');

        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $msg = str_replace('[name]',$array[$type],$Model->getError());
            $this->error($msg);
        }

        // 转换时间格式
        $data['date_of_birth'] = strtotime($data['date_of_birth']);
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        // 开启事务
        $witnessModel->startTrans();
        //保存证人信息
        if($witnessName != ""){
            $witnessData = $witnessModel->create();
            $witnessData['name'] = $witnessName;
            $witnessData['date_of_birth'] = strtotime($witnessData['date_of_birth']);
            $witnessId = $witnessModel->add($witnessData);
            // 数据保存失败
            if (!$witnessId) {
                $witnessModel->rollback();
                $this->error('数据保存失败');
            }
            $data['name'] = $witnessName;
        }
        $id = M('CaseRecord')->add($data);
        // 数据保存失败
        if (!$id) {
            $witnessModel->rollback();
            $this->error('数据保存失败');
        }

        $caseData['id'] = $this->case['id'];
        $caseData['record_status'] = 1;
        $caseRs = D('Case')->save($caseData);
        if($caseRs === false){
            $Model->rollback();
            $this->error('数据保存失败');
        }
        //操作日志
        $content = I('post.start_time').$array[$type].(I('is_client') == 1 ? '当事人' : '证人').I('post.name');
        $this->saveCaseLog($this->case['id'],$content);
        // 成功
        $witnessModel->commit();
        $this->success('新增成功', U("edit?case_id={$this->case['id']}&id=" . $id));
    }

    /**
     * 编辑页面加载
     */
    public function edit(){
        $id = I('get.id');
        $map = array();
        $map['is_del'] = 0;
        $map['id'] = $id;
        $info = M('CaseRecord')->where($map)->find();
        $relaterArray = array();
        $case_id = $this->case['id'];
        $map4 = array();
        $map4['is_del'] = 0;
        $map4['case_id'] = $case_id;
        $map4['traffic_type'] = array('neq',8);
        //当事人
        $client = M('CaseClient')->field('id,name')->where($map4)->select();
        //当事人相关人
        foreach ($client as $key => $val){
            $map1 = array();
            $map1['case_client_id'] = $val['id'];
            $clientRelater = M('CaseClientRelater')->field('id,name')->where($map1)->select();
            $relaterArray = array_merge($relaterArray,$clientRelater);
        }
        //证人
        $map3 = array();
        $map3['case_id'] = $case_id;
        $witness = M('CaseWitness')->field('id,name')->where($map3)->select();
        $this->assign('client',$client);
        $this->assign('clientRelater',$relaterArray);
        $this->assign('witness',$witness);
        $this->assign('id',$id);
        $this->assign('info',$info);
        $this->assign('certificate_type',get_custom_config('certificate_type'));
        $this->display();
    }

    /**
     * 执行编辑
     */
    public function update(){
        $id = I('post.id');
        // 实例化模型
        $Model = D('CaseRecord');
        $type = I('post.record_type','','int');
        $array = array('询问','讯问','谈话');
        // 创建数据
        $data = $Model->create();
        if (false === $data) {
            $msg = str_replace('[name]',$array[$type],$Model->getError());
            $this->error($msg);
        }
        // 报警信息
        $info = M('CaseRecord')->getById($id);
        if (empty($info)) {
            $this->error('询问笔录不存在');
        }
        if ($info['is_del'] == 1) {
            $this->error('询问笔录已删除');
        }
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        // 开启事务
        $Model->startTrans();
        $result = $Model->save($data);

        // 数据保存失败
        if (!$result) {
            $Model->rollback();
            $this->error('数据保存失败');
        }
        // 成功
        $Model->commit();
        $this->success('更新成功');
    }

    /**
     * 删除
     */
    public function delete(){
        // 获取编号
        $id = I('get.id', '', 'int');
        if ($id === '') {
            $this->error('非法操作');
        }

        // 实例化模型
        $Model = M('CaseRecord');

        // 开启事务
        $Model->startTrans();

        // 更新锁定状态
        $map = array();
        $map['id'] = $id;
        $result = $Model->where($map)->setField('is_del', 1);

        if (!$result) {
            $Model->rollback();
            $this->error('删除失败');
        }
        $map = array();
        $map['case_id'] = $this->case['id'];
        $map['is_del'] = 0;
        $count = M('CaseRecord')->where($map)->count();
        if($count === 0){
            $caseData['id'] = $this->case['id'];
            $caseData['record_status'] = 0;
            $caseRs = D('Case')->save($caseData);
            if($caseRs === false){
                $Model->rollback();
                $this->error('删除失败');
            }
        }
        $map = array();
        $map['case_id'] = $this->case['id'];
        $recordInfo = M('CaseRecord')->getById($id);
        if($recordInfo['type'] == '0'){
            $map['cate'] = 61;
        }else if($recordInfo['type'] == '1'){
            $map['cate'] = 62;
        }else if($recordInfo['type'] == '2'){
            $map['cate'] = 63;
        }
        $para['ext_ida'] = $id;
        $para = array();
        $para['is_document'] = 0;
        $para['update_user_id'] = $this->my['id'];
        $para['update_time'] = time();
        $rs = M('CasePhoto')->where($map)->save($para);
        if($rs === false){
            $Model->rollback();
            $this->error('删除失败');
        }
        // 成功
        $Model->commit();
        $this->success('删除成功');
    }

    /**
     * 根据ID获得被询问人的详细信息
     */
    public function info(){
        $id = I('post.id');
        $map = array();
        $info = array();
        if(strpos($id,"clientRelater") !== false){
            //当事人相关人信息
            $map['id'] = str_replace("clientRelater","",$id);
            $model = M('CaseClientRelater');
            $info = $model->field("sex,'' as id_type,idno,tel,address,'' as home_place,'' as brithday")->where($map)->find();
        }else if(strpos($id,"client") !== false){
            //当事人信息
            $map['id'] = str_replace("client","",$id);
            $info = M('CaseClient')->field("sex,id_type,idno,tel,address,'' as home_place,'' as brithday")->where($map)->find();
        }else if(strpos($id,"witness") !== false){
            //证人信息
            $map['id'] = str_replace("witness","",$id);
            $info = M('CaseWitness')->field("sex,home_place,address,id_type,idno,FROM_UNIXTIME(date_of_birth,'%Y-%m-%d') as brithday,tel")->where($map)->find();
        }
        echo json_encode($info);
    }

    /**
     * 获取图片列表
     */
    public function photoList(){
        $cate = I('post.cate','','int');
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('CaseRecordHandle/index/photoTable');
    }

    /**
     * 获取文书页数
     */
    public function getCount(){
        $cate = I('post.cate','','int');
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->success(count($list));
    }

    /**
     * 上传图片画面加载
     */
    public function photoTable(){
        $id = I('get.id','','int');
        $type = I('get.type');
        if($type == '询问笔录'){
            $cate = 61;
        }else if($type == '讯问笔录'){
            $cate = 62;
        }else{
            $cate = 63;
        }
        $this->assign('id',$id);
        $this->assign('type',$type);
        $this->assign('cate',$cate);
        $this->display();
    }
}