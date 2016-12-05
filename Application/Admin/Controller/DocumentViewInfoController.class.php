<?php
namespace Admin\Controller;

/**
 * 文书查看
 */
class DocumentViewInfoController extends CaseDetailController {
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
        $this->display('DocumentViewInfo/index/photoTable');
    }
}
