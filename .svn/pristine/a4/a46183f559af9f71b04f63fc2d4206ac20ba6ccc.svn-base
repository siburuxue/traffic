<?php
namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller {

	public function __construct() {

		parent::__construct();

		define('SRC_URL', 'http://src.bandesk.com');
		define('SRC_COMMON_URL', SRC_URL . '/Common');
		define('SRC_MODULE_URL', SRC_URL . '/Home');
		define('WWW_URL', get_wwwroot());

		// $config = M('Config')->cache(true)->getField('name,val');
		// C('custom', $config);

	}

	public function checkCode() {

		$name = I('get.name', '', 'trim,htmlspecialchars');

		$config = array(
			'useCurve' => false,
			'length' => 4,
			'fontttf' => '5.ttf',
		);

		$verify = new \Think\Verify($config);
		$verify->entry($name);

	}

}