<?php

function get_custom_config($name = null) {

	$_config = C('custom');
	if (empty($name)) {
		return $_config;
	}

	$name = explode('.', $name);

	foreach ($name as $v) {
		$_config = $_config[$v];
	}
	return $_config;
}
/**
 * 转换时间戳到中文描述
 * @param  [type] $time [description]
 * @return [type]       [description]
 */
function time_unit($time) {
	$time = intval($time);
	$sec = 0;
	$min = 0;
	$hour = 0;

	$hour = floor($time / 3600);
	if ($time - $hour * 3600 > 0) {
		$min = floor(($time - $hour * 3600) / 60);
	}
	if ($time - $hour * 3600 - $min * 60 > 0) {
		$sec = $time - $hour * 3600 - $min * 60;
	}

	$str = '';
	if ($hour > 0) {
		$str .= $hour . '小时';
	}
	if ($min > 0) {
		$str .= $min . '分钟';
	}
	if ($sec > 0) {
		$str .= $sec . '秒钟';
	}
	if ($str == '') {
		$str = '0秒';
	}

	return $str;
}

/**
 * 获取当前站点根URL
 * @return string 地址
 */
function get_wwwroot() {

	$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
	$url .= $_SERVER['HTTP_HOST'] . __ROOT__ . '/';

	return $url;
}

/**
 * 获取图片的绝对URL
 * @param  [type] $path [description]
 * @return [type]       [description]
 */
function get_img($path) {
	if (empty($path)) {
		$url = SRC_COMMON_URL . '/images/no-photo.jpg';
	} else {
		$url = get_url($path);
	}
	return $url;
}

/**
 * 获取目录的带域名的绝对访问路径
 * @param mixed $dir
 * @return
 */
function get_url($dir) {
	$dir = trim($dir, '/');
	return WWW_ROOT . $dir;
}

/**
 * 获取更新主键的数组
 * @param array $old 原主键的一维数组
 * @param array $new 新主键的一位数组
 * @return array
 */
function get_ids_case($old = array(), $new = array()) {
	$del = array();
	$add = array();
	$update = array();
	if (empty($old)) {
		if (false === empty($new)) {
			$add = $new;
		}
	} else {
		if (empty($new)) {
			$del = $old;
		} else {
			$del = array_diff($old, $new);
			$add = array_diff($new, $old);
			$update = array_intersect($old, $new);
		}
	}
	return array('del' => $del, 'add' => $add, 'update' => $update);
}

/**
 * 把字节数格式为 B K M G T 描述转化为字节格式
 * @param string $val
 * @return int
 */
function return_bytes($val) {
	$val = strtolower(trim($val));
	$val = str_replace('b', '', $val);
	$last = $val[strlen($val) - 1];
	switch ($last) {
	case 'p':
		$val *= 1024;
	case 't':
		$val *= 1024;
	case 'g':
		$val *= 1024;
	case 'm':
		$val *= 1024;
	case 'k':
		$val *= 1024;
	}
	return $val;
}

/**
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * @return string
 */
function byte_format($size, $dec = 2) {
	$a = array(
		"B",
		"KB",
		"MB",
		"GB",
		"TB",
		"PB");
	$pos = 0;
	while ($size >= 1024) {
		$size /= 1024;
		$pos++;
	}
	return round($size, $dec) . " " . $a[$pos];
}

/**
 * mstrlen()
 * 获取字符串长度，汉字算2字节
 * @param string $str
 * @return int
 */
function mstrlen($str) {
	return (strlen($str) + mb_strlen($str, 'utf-8')) / 2;
}

/**
 * left()
 * 从字符串左侧截取指定长度，汉字算2字节
 * @param string $str 被截取的字符串
 * @param int $length 截取长度
 * @return string
 */
function left($str, $length) {
	$l = mb_strlen($str, 'utf-8');
	$result = '';
	for ($i = 0; $i < $l; $i++) {
		$result .= mb_substr($str, $i, 1, 'utf-8');
		if (mstrlen($result) >= $length) {
			return $result;
		}
	}
	return $result;
}

function left_str($str = '', $length = 10, $ext = '…') {
	$res = left($str, $length);
	if ($res == $str) {
		return $str;
	} else {
		return $res . $ext;
	}
}

/**
 * list_to_group()
 * 把返回的数据集按字段分组
 * @param mixed $list 要转换的数据集
 * @param mixed $groupby 分组的字段名
 * @return array
 */
function list_to_group($list, $groupby) {
	$group = array();
	if (false === empty($list) && true === is_array($list)) {
		foreach ($list as $key => $data) {
			$group[$data[$groupby]][] = $data;
		}
	}
	return $group;
}

/**
 * list_to_array()
 * 从返回的数据集提取单个字段生成一维数组
 * @param mixed $list 数据集
 * @param mixed $field 提取的字段名
 * @return array
 */
function list_to_array($list, $field) {
	$array = array();
	if (false === empty($list) && true === is_array($list)) {
		foreach ($list as $key => $value) {
			$array[] = $value[$field];
		}
	}
	return $array;
}

/**
 * list_to_deep()
 * 把返回的数据集转化为深度菜单数组
 * @param mixed $list 原数据集
 * @param mixed $id 当前节点主键值
 * @param string $id_name 主键字段名
 * @param string $pid_name 父节点字段名
 * @param mixed $deep 返回的数据集 根节点->当前节点顺序排列
 * @param integer $root 根节点主键值
 * @return array
 */
function list_to_deep($list, $id, $id_name = 'id', $pid_name = 'pid', $deep = array(), $root = 0) {
	$node = list_search($list, "$id_name=$id");
	if (!empty($node)) {
		array_unshift($deep, $node[0]);
		if ($node[0][$pid_name] != $root) {
			$deep = list_to_deep($list, $node[0][$pid_name], $id_name, $pid_name, $deep, $root);
		}
	}
	return $deep;
}

/**
 * get_all_child()
 * 获取当前节点以及该节点下所有子节点的主键值
 * @param mixed $list 原数据集
 * @param mixed $id 当前节点主键值
 * @param string $id_name 主键字段称
 * @param string $pid_name 父节点字段名
 * @return
 */
function get_all_child($list, $id, $id_name = 'id', $pid_name = 'pid') {
	$id_arr[] = intval($id);
	$child = list_to_array(list_search($list, "$pid_name=$id"), $id_name);
	if (!empty($child)) {
		foreach ($child as $v) {
			$id_arr = array_merge($id_arr, get_all_child($list, $v, $id_name, $pid_name));
		}
	}
	return $id_arr;
}

/**
 * 将树形结构转化成HTML标签库中OPTIONS使用的数组
 * @access public
 * @param array $tree 通过list_to_tree得到的数组
 * @param array $result 结果数组可传入默认值
 * @param int $level 层级，层级余越大，"┃"符号出现的次数越多
 * @param string $value option的value对应在$tree中的字段名
 * @param string $name option的显示文字对应在$tree中的字段名
 * @param string $child $tree的子节点名称
 * @return array
 */
function tree_to_array($tree, $level = 0, $result = array(), $space = '|&nbsp;&nbsp;&nbsp;&nbsp;', $last_space = '|--&nbsp;&nbsp;', $prefix_name = '_prefix', $child_name = '_child') {
	$prefix = $last_space;
	for ($i = 0; $i < $level; $i++) {
		$prefix = $space . $prefix;
	}
	$level++;
	foreach ($tree as $key => $data) {
		$data[$prefix_name] = $prefix;
		$children = array();
		if (isset($data[$child_name])) {
			$children = $data[$child_name];
			unset($data[$child_name]);
		}
		$result[] = $data;
		if (!empty($children)) {
			$result = tree_to_array($children, $level, $result, $space, $last_space, $prefix_name, $child_name);
		}
	}
	return $result;
}

/**
 * 把返回的数据集转换成Tree
 * @access public
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
	// 创建Tree
	$tree = array();
	if (is_array($list)) {
		// 创建基于主键的数组引用
		$refer = array();
		foreach ($list as $key => $data) {
			$refer[$data[$pk]] = &$list[$key];
		}
		foreach ($list as $key => $data) {
			// 判断是否存在parent
			$parentId = $data[$pid];
			if ($root == $parentId) {
				$tree[] = &$list[$key];
			} else {
				if (isset($refer[$parentId])) {
					$parent = &$refer[$parentId];
					$parent[$child][] = &$list[$key];
				}
			}
		}
	}
	return $tree;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc') {
	if (is_array($list)) {
		$refer = $resultSet = array();
		foreach ($list as $i => $data) {
			$refer[$i] = &$data[$field];
		}

		switch ($sortby) {
		case 'asc': // 正向排序
			asort($refer);
			break;
		case 'desc': // 逆向排序
			arsort($refer);
			break;
		case 'nat': // 自然排序
			natcasesort($refer);
			break;
		}
		foreach ($refer as $key => $val) {
			$resultSet[] = &$list[$key];
		}

		return $resultSet;
	}
	return false;
}

/**
 * 在数据列表中搜索
 * @access public
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list, $condition) {
	if (is_string($condition)) {
		parse_str($condition, $condition);
	}

	// 返回的结果集合
	$resultSet = array();
	foreach ($list as $key => $data) {
		$find = false;
		foreach ($condition as $field => $value) {
			if (isset($data[$field])) {
				if (0 === strpos($value, '/')) {
					$find = preg_match($value, $data[$field]);
				} elseif ($data[$field] == $value) {
					$find = true;
				}
			}
		}
		if ($find) {
			$resultSet[] = &$list[$key];
		}

	}
	return $resultSet;
}

/**
 * authcode()
 * discuz的加密解密方法
 * @param mixed $string 要加密解密的字符串
 * @param string $operation 方法 DECODE 解密 ENCODE 加密
 * @param string $key 密钥
 * @param integer $expiry
 * @return
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4; // 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥
	$key = md5($key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for ($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if ($operation == 'DECODE') {
		if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace('=', '', base64_encode($result));
	}
}

?>