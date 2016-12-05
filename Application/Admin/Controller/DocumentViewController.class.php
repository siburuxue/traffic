<?php
namespace Admin\Controller;

/**
 * 文书查看
 */
class DocumentViewController extends CaseCommonController {
    /**
     * 首页界面
     */
    public function index() {
       // 渲染
        $this->display();
    }

    /**
     * 获取图片
     */
    public function photoList(){
        $cate = I('post.cate');
        $map = array();
        $map['cate'] = array('in',$cate);
        $map['case_id'] = $this->case['id'];
        $map['is_del'] = 0;
        $map['is_document'] = 1;
        $list = D('CasePhotoView')->order("CasePhoto.train")->where($map)->select();
        foreach ($list as $key => &$value) {
            $value['image_path'] = get_photo($value['image_path']);
            $value['thumb_path'] = get_photo($value['thumb_path']);
        }
        $this->assign('list',$list);
        $this->display('DocumentView/index/photoTable');
    }

    /**
     * 修改图片位置
     */
    public function changeTrain(){
        $map = array();
        $map['id'] = I('post.id','','int');
        $map['train'] = I('post.train','','int');
        $map['update_user_id'] = $this->my['id'];
        $map['update_time'] = time();
        $model = M('CasePhoto');
        $model->save($map);
    }

    /**
     * 验证是否有图片
     */
    public function check(){
        $caseId = I('get.case_id','','int');
        if($caseId == ''){
            $this->error('非法操作');
        }
        $map = array();
        $map['case_id'] = $caseId;
        $info = M('CasePhoto')->where($map)->find();
        if(empty($info)){
            $this->error('未上传任何图片，不能制作卷宗文书');
        }else{
            $this->success('');
        }
    }
}
