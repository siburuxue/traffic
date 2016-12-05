<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 结案归档
 */
class CaseFileHandleInfoController extends CaseDetailController {
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
     * 获取图片列表
     */
    public function photoList() {
        $cate = 60;
        $id = I('post.id','','int');
        $list = parent::getPhotoList($cate,$id);
        $this->assign('list', $list);
        $this->display('CaseFileHandleInfo/index/photoTable');
    }
}
