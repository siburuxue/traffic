<?php
/**
 * 是否是一个有效的字典名称
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
function is_dict_name($str) {
	return regex($str, '/^[a-zA-Z_][a-zA-Z0-9_]{1,255}$/');
}
/**
 * 是否是一个有效的手机号码
 * @param  string  $str 被验证的字符串
 * @return boolean
 */
function is_mobile($str) {
	return regex($str, 'mobile');
}
/**
 * 是否是一个有效的传真
 * @param  string  $str 被验证的字符串
 * @return boolean
 */
function is_fax($str) {
	return regex($str, 'fax');
}

/**
 * 是否是一个有效的邮箱地址
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
function is_email($str) {
	return regex($str, 'email');
}

/**
 * 是否是一个有效的邮政编码
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
function is_zip($str) {
	return regex($str, 'zip');
}

/**
 * 是否是由数字组成
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
function is_number($str) {
	return regex($str, 'number');
}

/**
 * 是否是一个有效的真实姓名
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
function is_true_name($str) {
	$len = mb_strlen($str, 'utf-8');
	if ($len > 50 || $len < 2) {
		return false;
	} else {
		return true;
	}
}

/**
 * 是否是一个有效的昵称
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
function is_nickname($str) {
	$len = mstrlen($str);
	if ($len > 50 || $len < 2) {
		return false;
	} else {
		return true;
	}
}

/**
 * 是否是一个有效的编号
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
function is_code($str) {
	return regex($str, '/^\d{20}$/');
}

/**
 * 是否是一个有效的用户名
 * 4-20字节，汉字占2字节，不区分大小写
 * @param string $username 被验证字符串
 * @return boolean
 */
function is_user_name($username) {

	$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
	$len = mstrlen($username);
	if ($len > 20 || $len < 4 || preg_match("/\s+|^c:\\con\\con|[%,\*\"\s\<\>\&]|$guestexp/is", $username)) {
		return false;
	}
	if (is_mobile($username) || is_email($username)) {
		return false;
	}
	return true;
}

/**
 * 是否是一个有效的密码
 * 6-16位，数字、字母、符号，区分大小写
 * @param string $str 被验证字符串
 * @return boolean
 */
function is_password($str) {
	$sample = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$sample .= "abcdefghijklmnopqrstuvwxyz";
	$sample .= "0123456789";
	$sample .= "`~!@#\$%^&*()-_=+\\|{}[]:;\"'<>,.?/";
	$len = strlen($str);
	if ($len < 6 || $len > 16) {
		return false;
	}
	for ($i = 0; $i < $len; $i++) {
		$each_char = mb_substr($str, $i, 1);
		if (substr_count($sample, $each_char) == 0) {
			return false;
		}
	}
	return true;
}

/**
 * 是否是一个有效的时间
 * 格式为 YYYY-MM-DD HH:NN:SS
 * @param string $str 被验证字符串
 * @return boolean
 */
function is_time($str) {
	$arr = explode(' ', $str);
	if (count($arr) < 1 && count($arr) > 2) {
		return false;
	}
	if (false === is_date($arr[0])) {
		return false;
	}
	if (count($arr) > 1) {
		$time_arr = explode(':', $arr[1]);
		if (count($time_arr) < 1 || count($time_arr) > 3) {
			return false;
		}
		if (!regex($time_arr[0], 'number') || intval($time_arr[0]) >= 24 || intval($time_arr[0]) < 0) {
			return false;
		}
		if (count($time_arr) > 1) {
			if (!regex($time_arr[1], 'number') || intval($time_arr[1]) >= 60 || intval($time_arr[1]) < 0) {
				return false;
			}
		}
		if (count($time_arr) > 2) {
			if (!regex($time_arr[2], 'number') || intval($time_arr[2]) >= 60 || intval($time_arr[2]) < 0) {
				return false;
			}
		}
	}
	return true;
}

/**
 * 是否是一个有效的日期
 * 格式 YYYT-MM-DD
 * @param string $str 被验证字符串
 * @return boolean
 */
function is_date($str) {
	$str = trim($str);
	if (!regex($str, 'date')) {
		return false;
	}
	$dateArr = explode('-', $str);
	return checkdate(intval($dateArr[1]), intval($dateArr[2]), intval($dateArr[0]));
}

/**
 * 使用正则验证数据
 * @param string $value 要验证的数据
 * @param string $rule 验证规则
 * @return boolean
 */
function regex($value, $rule) {
	$validate = array(
		'require' => '/.+/',
		'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
		'url' => '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!#\w]*)?$/',
		'currency' => '/^\d+(\.\d+)?$/',
		'number' => '/^\d+$/',
		'zip' => '/^\d{6}$/',
		'integer' => '/^[-\+]?\d+$/',
		'double' => '/^[-\+]?\d+(\.\d+)?$/',
		'english' => '/^[A-Za-z]+$/',
		'mobile' => '/^1{1}\d{10}$/',
		'date' => '/\d{4}-\d{1,2}-\d{1,2}/',
		'funcname' => '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/u',
		'slug' => '/^[a-zA-Z_][a-zA-Z0-9_]{0,50}$/',
		'fax' => '/(^[0-9]{3,4}\-[0-9]{7,8}\-[0-9]{3,4}$)|(^[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{7,8}\-[0-9]{3,4}$)|(^[0-9]{7,15}$)/',
	);
	// 检查是否有内置的正则表达式
	if (isset($validate[strtolower($rule)])) {
		$rule = $validate[strtolower($rule)];
	}
	return preg_match($rule, $value) === 1;
}

/**
 * 金额验证
 */
function is_money($str) {
	return regex($str, 'currency');
}

/**
 * 身份证验证
 */
function is_id_card($str) {
	if (trim($str) == '') {
		return false;
	} else {
		return true;
	}
}
function is_id_card1($vStr) {
	$vCity = array(
		'11', '12', '13', '14', '15', '21', '22',
		'23', '31', '32', '33', '34', '35', '36',
		'37', '41', '42', '43', '44', '45', '46',
		'50', '51', '52', '53', '54', '61', '62',
		'63', '64', '65', '71', '81', '82', '91',
	);
	if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) {
		return false;
	}

	if (!in_array(substr($vStr, 0, 2), $vCity)) {
		return false;
	}

	$vStr = preg_replace('/[xX]$/i', 'a', $vStr);
	$vLength = strlen($vStr);
	if ($vLength == 18) {
		$vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
	} else {
		$vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
	}
	if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) {
		return false;
	}

	if ($vLength == 18) {
		$vSum = 0;
		for ($i = 17; $i >= 0; $i--) {
			$vSubStr = substr($vStr, 17 - $i, 1);
			$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
		}
		if ($vSum % 11 != 1) {
			return false;
		}

	}
	return true;
}
