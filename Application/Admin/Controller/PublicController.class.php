<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 全局类
 */
class PublicController extends Controller {

	/**
	 * 构造函数
	 */
	public function __construct() {

		parent::__construct();

		// 站点访问根目录
		define('WWW_ROOT', get_wwwroot());
		// 资源文件根目录
		define('SRC_URL', 'http://src.dalianke.com');
		// 公共资源
		define('SRC_COMMON_URL', SRC_URL . '/Common');
		// 项目资源
		define('SRC_MODULE_URL', SRC_URL . '/Admin');

		// 动态配置
		$config = M('Config')->getField('name,val');
		C('customDB', $config);

	}

	/**
	 * 生成验证码
	 */
	public function checkCode() {
		// 验证码类型
		$name = I('get.name', '', 'trim,htmlspecialchars');
		// 验证码配置信息
		$config = array(
			'useCurve' => false,
			'length' => 4,
			'fontttf' => '5.ttf',
		);
		// 创建验证码
		$verify = new \Think\Verify($config);
		$verify->entry($name);
	}

}