<?php
namespace Admin\Controller;

/**
 * 受案信息 - 受案登记
 */
class CaseAcceptHandleInfoController extends CaseDetailController {
	/**
	 * 编辑受案登记
	 */
	public function detail() {
		$id = I('get.id', '', 'int');
		$map = array();
		$map['id'] = $id;
		$info = M('CaseAccept')->where($map)->find();
		
		//审批人
		$list = $this->getCheckUserList("case_accept_check_level_1");

		//案件来源
		$this->assign('caseSource', get_dict('case_source'));
		$this->assign('info', $info);
		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 审核记录
	 */
	public function editCheckTable() {
		$this->display('CaseAcceptHandleInfo/detail/checkTable');
	}

	/**
	 * 受案登记图片
	 */
	public function editPhotoTable() {
		$this->display('CaseAcceptHandleInfo/detail/photoTable');
	}

	/**
	 * 获取图片列表
	 */
	public function photoList() {
		$cate = 1;
		$id = I('post.id','','int');
		$list = parent::getPhotoList($cate,$id);
		$this->assign('list', $list);
		$this->display('CaseAcceptHandleInfo/detail/photoTable');
	}

	/**
	 * 审批列表
	 */
	public function checkTable() {
		$id = I('post.id', '', 'int');
		$map = array();
		$map['item_id'] = $id;
		$map['cate'] = 0;
		$map['case_id'] = $this->case['id'];
		$model = D('CaseCheckView');
		$list = $model->where($map)->select();
		$array = array('待审核', '通过', '拒绝');
		foreach ($list as $key => $val) {
			$list[$key]['status'] = $array[$val['status']];
		}
		$this->assign('list', $list);
		$this->display('CaseAcceptHandleInfo/detail/checkTable');
	}
}