<?php
namespace Admin\Controller;

/**
 * 案件通用类
 */
class CaseController extends CaseCommonController {

	/**
	 * 构造函数
	 */
	public function __construct() {

		parent::__construct();

		// 判断案件处理类型是否是普通
		if ($this->case['cate'] != 0) {
			$this->error('案件处理类型错误');
		}

		// 验证当前用户操作权限
		/*if (false === is_power($this->myPower, 'handle')) {
			$this->error('没有权限');
		}*/

	}

}