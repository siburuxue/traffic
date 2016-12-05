<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 快赔-协议书
 */
class FastAgreementController extends FastCommonController {
    /**
     * 画面加载
     */
    public function index(){
        $gradeType = get_custom_config('grade_type');
        $map = array();
        $map['CaseClient.case_id'] = $this->case['id'];
        $map['CaseClient.traffic_type'] = array('neq',8);
        $list = D('CaseClientAgreementView')->where($map)->order('id desc')->select();
        foreach($list as $key => $val){
            $list[$key]['grade_type'] = str_replace('号牌','',$gradeType[$val['grade_type']]);
        }
		$map = array();
		$map['case_id'] = $this->case['id'];
		$count = M('FastAgreement')->where($map)->count();
        $this->assign('list',$list);
        $this->assign('count',$count);
        $this->display();
    }

    /**
     * 保存协议书数据
     */
    public function save(){
		$clientList = I('post.clientList');
		$model = D('FastAgreement');
		$model->startTrans();
		foreach($clientList as $key => $val){
			if($val['headstock'] == '0' && $val['right_anterior_horn'] == '0' && $val['left_anterior_horn'] == '0' && $val['tail_stock'] == '0' && $val['right_rear_corner'] == '0' && $val['left_rear_corner'] == '0' && $val['left_side'] == '0' && $val['right_side'] == '0'){
				$model->rollback();
				$this->error("请选择 {$val['name']}:碰撞部位");
			}
			if($val['rear_end'] == '0' && $val['recessive'] == '0' && $val['backing_up'] == '0' && $val['sliding'] == '0' && $val['open_close_door'] == '0' && $val['violate_traffic_signal'] == '0' && $val['according_stipulations'] == '0' && $val['others'] == ''){
				$model->rollback();
				$this->error("请选择 {$val['name']}:事故成因");
			}
			if($val['all_responsibility'] == '0' && $val['equal_responsibility'] == '0' && $val['no_responsibility'] == '0'){
				$model->rollback();
				$this->error("请选择 {$val['name']}:责任");
			}
			$data = $model->create($val);
			if($data === false){
				$this->error($model->getError());
			}
			if($data['id'] == ''){
				$rs = $model->add($data);
			}else{
				$rs = $model->save($data);
			}
			if($rs === false){
				$model->rollback();
				$this->error('保存失败');
			}
		}
		$map = array();
		$map['agreement_status'] = 1;
		$map['id'] = I('post.case_id');
		$map['update_user_id'] = $this->my['id'];
		$map['update_time'] = time();
		$rs = M('Case')->save($map);
		if($rs === false){
			$model->rollback();
			$this->error('保存失败');
		}
		$model->commit();
        $this->success('保存成功');
    }

    /**
     * 图片加载
     */
    public function photoList(){
        $cate = 64;
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('FastAgreement/index/photoTable');
    }
}
