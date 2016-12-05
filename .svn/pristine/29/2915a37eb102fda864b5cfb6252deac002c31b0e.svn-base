<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 处罚
 */
class CasePunishHandleInfoController extends CaseDetailController {
    /**
     * 画面加载
     */
    public function index(){
        $id = I('get.id','','int');
        $traffic_type = get_custom_config('traffic_type');
        $hurt_type = get_custom_config('hurt_type');
        //获取当事人列表
        $case_id = $this->case['id'];
        $map2 = array();
        $map2['case_id'] = $case_id;
        $map2['traffic_type'] = array('neq',8);
        $clientList = M('CaseClient')->where($map2)->order('id desc')->select();
        foreach($clientList as $key => $value){
            $clientList[$key]['traffic_type'] = $traffic_type[(int)$clientList[$key]['traffic_type']];
            $clientList[$key]['hurt_type'] = $hurt_type[(int)$clientList[$key]['hurt_type']];
        }
        $this->assign('id',$id);
        $this->assign('clientList',$clientList);
        $this->assign('revoke_years',get_custom_config('revoke_years'));
        $this->assign('rights_obligations',get_custom_config('rights_obligations'));
        $this->display();
    }

    /**
     * 获取当事人对应的处罚信息
     */
    public function getClientPunishInfo(){
        $clientId = I('post.clientId');
        $map = array();
        $map['case_client_id'] = $clientId;
        $punishInfo = M('CasePunish')->where($map)->find();
        if(empty($punishInfo)){
            $punishInfo = array();
            //当事人信息
            $clientInfo = M('CaseClient')->getById($clientId);
            //当事人-违法行为及条款
            $map1 = array();
            $map1['case_client_id'] = $clientId;
            $clientLawList = M('CaseClientLaw')->where($map1)->select();
            //调查报告信息
            $map2 = array();
            $map2['cognizance_type'] = 1;
            $map2['case_id'] = $this->case['id'];
            $cognizanceInfo = M('CaseCognizance')->where($map2)->order('id desc')->find();
            $map3 = array();
            $map3['case_cognizance_id'] = $cognizanceInfo['id'];
            $map3['is_last'] = 1;
            $reportInfo = M('CaseCognizanceReport')->field('punish')->where($map3)->find();
            //是否警告
            $punishInfo['punish_is_warning'] = $clientInfo['punish_is_warning'];
            //是否罚款
            $punishInfo['punish_is_fine'] = $clientInfo['punish_is_fine'];
            //罚款金额
            $punishInfo['punish_fine_money'] = $clientInfo['punish_money'];
            //扣分
            $punishInfo['punish_fine_score'] = $clientInfo['punish_score'];
            //是否暂扣
            $punishInfo['punish_is_seize'] = $clientInfo['punish_is_seize'];
            //暂扣时间
            $punishInfo['punish_seize_date'] = $clientInfo['punish_seize_time'];
            //是否吊销
            $punishInfo['punish_is_revoke'] = $clientInfo['punish_is_revoke'];
            //吊销执行时间
            $punishInfo['punish_revoke_date'] = $clientInfo['punish_revoke_time'];
            //是否拘留
            $punishInfo['punish_is_detain'] = $clientInfo['punish_is_detain'];
            //拘留时间
            $punishInfo['punish_detain_date'] = $clientInfo['punish_detain_time'];
            //案件类别
            $punishInfo['criminal_case_type'] = $clientInfo['criminal_case_type'];
            $illegal = '';
            foreach($clientLawList as $key => $val){
                //违法行为条款 大类
                $lawInfo = M('LawRegulation')->getById($val['law_pid']);
                $subLawInfo = M('LawRegulation')->getById($val['law_id']);
                $illegal .= ($key + 1).".".$lawInfo['title'].'    '.$subLawInfo['title']."\n";
            }
            //违法行为
            $punishInfo['illegal'] = $illegal;
            //处罚意见
            $punishInfo['opinion'] = $reportInfo['punish'];
            $punishInfo['id'] = '';
        }
        //行政处罚决定书
        $list25 = parent::getPhotoList(25,$punishInfo['id']);
        $list26 = parent::getPhotoList(26,$punishInfo['id']);
        $list27 = parent::getPhotoList(27,$punishInfo['id']);
        $list28 = parent::getPhotoList(28,$punishInfo['id']);
        $list29 = parent::getPhotoList(29,$punishInfo['id']);
        $list30 = parent::getPhotoList(30,$punishInfo['id']);
        $punishInfo['count25'] = count($list25);
        $punishInfo['count26'] = count($list26);
        $punishInfo['count27'] = count($list27);
        $punishInfo['count28'] = count($list28);
        $punishInfo['count29'] = count($list29);
        $punishInfo['count30'] = count($list30);
        echo json_encode($punishInfo);
    }

    /**
     * 获取图片列表
     */
    public function photoList(){
        $cate = I('post.cate','','int');
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('CaseCognizance/simple/photoTable');
    }

    /**
     * 文书列表
     */
    public function photoTable(){
        $array = array(
            25 => '行政处罚决定书',
            26 => '行政拘留回执',
            27 => '刑事拘留文书',
            28 => '逮捕文书',
            29 => '取保候审文书',
            30 => '移送起诉文书',
        );
        $cate = I('get.cate');
        $id = I('get.id');
        $this->assign('cate',$cate);
        $this->assign('id',$id);
        $this->assign('title',$array[$cate]);
        $this->display();
    }
}