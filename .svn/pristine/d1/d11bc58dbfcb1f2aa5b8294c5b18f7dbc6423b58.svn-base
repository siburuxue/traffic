<?php
namespace Admin\Controller;

/**
 * 复核科 - 复核申请 - 案件当事人的相关人维护
 */
class CaseClientRelaterCaseReviewHandleController extends CommonController {

	/**
	 * 首页
	 */
	public function index() {
		// 默认当前当事人编号
		$nowCaseClientId = I('get.case_client_id', 0, 'int');
		$this->assign('nowCaseClientId', $nowCaseClientId);

		// 案件编号
		$caseId = I('get.case_id', '', 'int');
		if ($caseId === '') {
			$this->error('非法操作');
		}
		$this->assign('caseId', $caseId);

		// 当事人列表
		$map = array();
		$map['case_id'] = $caseId;
		$map['is_del'] = 0;
		$caseClient = M('CaseClient')->where($map)->select();
		if (empty($caseClient)) {
			$this->error('尚无当事人');
		}
		$this->assign('caseClient', $caseClient);

		// 与当事人关系
		$this->assign('clientRelation', get_custom_config('client_relation'));

		// 权利义务告知
		$this->assign('rightsObligations', get_custom_config('rights_obligations'));

		$this->display();

	}
	/**
	 * 获取指定案件当事人的相关人员列表
	 */
	public function getCaseClientRelater() {
		// 当事人编号
		$caseClientId = I('get.case_client_id', '', 'int');
		if ($caseClientId === '') {
			$this->error('非法操作');
		}

		// 当事人相关人列表
		$map = array();
		$map['case_client_id'] = $caseClientId;
		$caseClientRelater = M('CaseClientRelater')->where($map)->select();

		$this->success($caseClientRelater);
	}

	/**
	 * 批量更新当事人的相关人员信息
	 */
	public function update() {
		// 当事人编号
		$caseClientId = I('post.case_client_id', '', 'int');
		if ($caseClientId === '') {
			$this->error('非法操作');
		}

		// 当事人信息
		$map = array();
		$map['id'] = $caseClientId;
		$map['is_del'] = 0;
		$caseClient = M('CaseClient')->where($map)->find();
		if (empty($caseClient)) {
			$this->error('当事人不存在');
		}

		// 新的相关人员列表
		$newList = I('post.item');
		$_POST['client_relaters'] = $newList;

		// 实例化模型
		$Model = D('CaseClientRelater');

		// 当提交的相关人员列表为空时，直接删除全部相关人
		if (empty($newList)) {
			$map = array();
			$map['case_client_id'] = $caseClientId;
			$Model->where($map)->delete();
			$this->success('更新成功');
		}

		// 需要新增的相关人
		$addList = array();
		// 需要更新的
		$updateIds = array();
		$updateList = array();
		// 需要删除的
		$deleteIds = array();

		// 验证过滤
		foreach ($newList as $key => &$value) {
			if ($value['id'] == 0) {
				$value['add_type'] = 1;
				unset($value['id']);
			}
			$value['case_client_id'] = $caseClientId;
			$value = $Model->create($value);
			if (false === $value) {
				$this->error('第' . ($key + 1) . '条记录：' . $Model->getError());
			}
			if (isset($value['id'])) {
				$updateIds[] = $value['id'];
				$updateList[] = $value;
			} else {
				$addList[] = $value;
			}
		}
		unset($key, $value);

		// 搜索全部原记录
		$map = array();
		$map['case_client_id'] = $caseClientId;
		$oldList = $Model->where($map)->select();

		if (!empty($oldList)) {
			// 确定需要删除的编号
			foreach ($oldList as $key => $value) {
				if (false === in_array($value['id'], $updateIds)) {
					$deleteIds[] = $value['id'];
				}
			}
		}

		// 如果查询到的记录数与需要更新的加需要删除的不符合
		if (count($oldList) !== count($deleteIds) + count($updateIds)) {
			$this->error('非法操作');
		}

		// 开启事务
		$Model->startTrans();

		// 更新
		if (!empty($updateList)) {
			foreach ($updateList as $key => $value) {
				$res = $Model->save($value);
				if (!$res) {
					$Model->rollback();
					$this->error('数据更新失败');
				}
			}
		}

		// 删除
		if (!empty($deleteIds)) {
			$map = array();
			$map['id'] = array('in', $deleteIds);
			$res = $Model->where($map)->delete();
			if (!$res) {
				$Model->rollback();
				$this->error('数据更新失败');
			}
		}

		// 新增
		if (!empty($addList)) {
			$res = $Model->addAll($addList);
			if (!$res) {
				$Model->rollback();
				$this->error('数据更新失败');
			}
		}

		$Model->commit();
		$this->success('更新成功');

	}

}