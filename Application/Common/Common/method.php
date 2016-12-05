<?php
/**
 * 获取图片路径
 */
function get_photo($url) {
	return C('FTP.url') . trim($url, '/');
}
/**
 * 获取指定编号字典组信息
 * @param  [type] $name [description]
 * @return [type]       [description]
 */
function get_dict($name) {
	$map['dict_name'] = $name;
	$map['is_del'] = 0;
	$list = D('DictOptionView')->where($map)->order('train asc,id asc')->field('id,name')->select();
	return $list;
}
/**
 * 获取搜索条件
 * @return [type] [description]
 */
function get_condition() {
	$request = $_REQUEST;
	$condition = array();
	foreach ($request as $key => $value) {
		$value = trim($value);
		if (substr($key, 0, 10) === 'condition_' && $value != '') {
			$condition[substr($key, 10)] = $value;
		}
	}
	return $condition;
}

/**
 * 生成案件序号
 * @param  array $myBrigade 当前用户所在大队信息
 * @param  int $time 记录创建时间
 * @return int
 */
function create_case_num($myBrigade, $time) {
	$startTime = mktime(0, 0, 0, 1, 1, date('Y', $time));
	$endTime = mktime(0, 0, 0, 1, 1, date('Y', $time) + 1) - 1;
	$map = array();
	$map['create_time'] = array(array('egt', $startTime), array('elt', $endTime));
	$map['department_id'] = $myBrigade['id'];
	$map['cate'] = 0;
	$num = M('Case')->where($map)->max('num');
	$num = intval($num) + 1;
	return $num;
}

/**
 * 生成案件编号
 * @param  array $myBrigade 当前用户所在大队信息
 * @param  int $num  案件序号
 * @param  int $time 记录创建时间
 * @return string
 */
function create_case_code($myBrigade, $num, $time) {
	return $myBrigade['area_code'] . date('Y', $time) . str_pad($num, 5, '0', STR_PAD_LEFT);
}

/**
 * 生成快赔案件序号
 * @param date 'Ym'格式日期
 * @return int
 */
function create_fast_case_num($date) {
	$map = array();
	$map['code'] = array('LIKE', $date . "%");
	$map['cate'] = 1;
	$count = M('Case')->where($map)->max('num');
	return $count + 1;
}

/**
 * 生成快赔案件编号
 * @param $date 'Ym'格式日期
 * @return string
 */
function create_fast_case_code($date, $num) {
	return $date . str_pad($num, 4, 0, STR_PAD_LEFT);
}

/**
 * 权限判断
 * @param array $myPower 当前用户拥有的权限
 * @param string $needPower 需要的权限，多个权限使用逗号分隔
 * @param string $logic 判断逻辑
 * @return boolean
 */
function is_power($myPower = array(), $needPower = '', $logic = 'and') {
	// 如果当前用户没有任何权限或者未指定需要的权限
	if (empty($myPower) || empty($needPower)) {
		return false;
	}

	if (false === strpos($needPower, ',')) {
		// 只有一个条件时
		return in_array($needPower, $myPower);
	} else {
		// 多个条件时
		$needPower = explode(',', $needPower);
		if ($logic === 'and') {
			// 需要全部满足时
			foreach ($needPower as $power) {
				if (false === in_array($power, $myPower)) {
					return false;
				}
			}
			return true;
		} else {
			// 只需一个满足时
			foreach ($needPower as $power) {
				if (true === in_array($power, $myPower)) {
					return true;
				}
			}
			return false;
		}
	}

}
?>