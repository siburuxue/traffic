<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 询问笔录
 */
class CaseRecordHandleInfoController extends CaseDetailController {
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
        $this->display('CaseRecordHandleInfo/index/table');
    }
    /**
     * 编辑页面加载
     */
    public function detail(){
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
     * 获取图片列表
     */
    public function photoList(){
        $cate = I('post.cate','','int');
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('CaseRecordHandleInfo/index/photoTable');
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