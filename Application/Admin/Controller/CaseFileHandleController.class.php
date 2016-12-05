<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 结案归档
 */
class CaseFileHandleController extends CaseCommonController {
    /**
     * 画面加载
     */
    public function index(){
        $case_id = $this->case['id'];
        //案件信息
        $map = array();
        $map['id'] = $case_id;
        $map['is_del'] = 0;
        $caseInfo = M('Case')->field('code,accident_time,accident_place')->where($map)->find();
        //工作记录信息
        $map = array();
        $map['case_id'] = $case_id;
        $workLog = M('CaseWorkLog')->where($map)->select();

        $this->assign('info',$workLog[0]);
        $this->assign('caseInfo',$caseInfo);
        $this->display();
    }

    /**
     * 保存工作记录
     */
    public function save(){
        $model = D('CaseWorkLog');
        $data = $model->create();
        if($data === false){
            $this->error($model->getError());
        }
        $model->startTrans();
        if(I('post.id','','int') === ''){
            $rs = $model->add($data);
        }else{
            $rs = $model->save($data);
        }
        if($rs === false){
            $model->rollback();
            $this->error('保存失败');
        }
        $model->commit();
        $this->success('保存成功');
    }

    /**
     * 获取图片列表
     */
    public function photoList() {
        $cate = 60;
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('CaseFileHandle/index/photoTable');
    }

    /**
     * 生成工作记录
     */
    public function make(){
        $map = array();
        $map['case_id'] = $this->case['id'];
        $list = M('CaseLog')->where($map)->order('id desc')->select();
        $this->success($list);
    }
}
