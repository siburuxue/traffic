<?php
return array(
	// 加载扩展配置
	'LOAD_EXT_CONFIG' => array(
		'db', // 数据库配置信息
		'ueditor' => 'ueditor', // 百度富文本编辑器UEDITOR
		'alimail' => 'alimail', // 阿里云邮件推送
		'custom' => 'custom', // 字典维护
		'custom_case_ext' => 'custom_case_ext', // 字典维护---交通事故信息采集补录选项
	),
	// 自动加载命名空间
	'AUTOLOAD_NAMESPACE' => array(
		'Lib' => COMMON_PATH . 'Lib',
	),
	'LOAD_EXT_FILE' => 'method,check',
	// ******************************************************************
	// 以下为自定义配置
	'BASE_BLANK_CHARLIST' => array("\0", "\t", "\n", "\x0B", "\r", " "),
	'BASE_ENTER_CHARLIST' => array("\0", "\t", "\n", "\x0B", "\r"),
	// ******************************************************************
	// 上传文件
	'UPLOAD_MAXSIZE' => 1024 * 1024 * 20,
	'UPLOAD_EXTS' => array('jpg', 'gif', 'png', 'jpeg'),
	'UPLOAD_FILES_EXTS' => array('txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'vsd'),
	// 图片裁剪
	'PHOTO_WIDTH' => 900,
	'PHOTO_HEIGHT' => 1350,
	// 缩略图尺寸
	'PHOTO_THUMB_WIDTH' => 400,
	'PHOTO_THUMB_HEIGHT' => 565,

	// FTP配置
	'FTP' => array(
		'url' => 'http://dl.dalianke.com/',
		'server' => '120.27.38.151',
		'username' => 'photo',
		'password' => 'photo123',
		'port' => 21,
		'pasv' => false,
		'ssl' => false,
		'timeout' => 200,
	),

);
?>