<?php
namespace Admin\Widget;
use Think\Controller;

class ShortWidget extends Controller {

	public function index($templateCateId = 0, $targetDom = '', $condition = array()) {
		$this->assign('templateCateId', $templateCateId);
		$this->assign('targetDom', $targetDom);
		$this->assign('param', $condition);

		$map['cate'] = $templateCateId;
		$template = M('PhraseTemplate')->where($map)->select();
		$this->assign('template', $template);

		$this->assign('domId', \Org\Util\String::keygen());

		$this->display('Common/short');
	}

}
