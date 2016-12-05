<?php
namespace Home\Widget;
use Think\Controller;

class IncWidget extends Controller {

	public function search() {
		$college = M('College')->select();
		$this->assign('college', $college);

		$classes = M('Classes')->select();
		$this->assign('classes', $classes);
		$this->display('Inc/search');
	}

}
