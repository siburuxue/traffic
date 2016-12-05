<?php
namespace Admin\Controller;
use \Lib\Page;

/**
 * 用户
 */
class CaseCatalogController extends CaseCommonController {
    /**
     * 首页界面
     */
    public function index() {
        $map = array();
        $map['case_id'] = $this->case['id'];
        $catalog = M('CaseCatalog')->where($map)->find();
        $this->assign('json',$catalog['json']);
        // 渲染
        $this->display();
    }
}
