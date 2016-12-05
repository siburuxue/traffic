<?php
return array(
	'pathRegex' => "/(?:'|\")(\/Uploads\/Article\/(?:image|video|file)\/\d{8}\/\d{16}\.\w+)(?:'|\")/isU",
	'imageActionName' => 'uploadimage',
	'imageFieldName' => 'upfile',
	'imageMaxSize' => 20480000,
	'imageAllowFiles' => array(
		0 => '.png',
		1 => '.jpg',
		2 => '.jpeg',
		3 => '.gif',
		4 => '.bmp',
	),
	'imageCompressEnable' => true,
	'imageCompressBorder' => 1600,
	'imageInsertAlign' => 'none',
	'imageUrlPrefix' => '',
	'imagePathFormat' => '/Uploads/Article/image/{yyyy}{mm}{dd}/{time}{rand:6}',

	'scrawlActionName' => 'uploadscrawl',
	'scrawlFieldName' => 'upfile',
	'scrawlPathFormat' => '/Uploads/Article/image/{yyyy}{mm}{dd}/{time}{rand:6}',
	'scrawlMaxSize' => 20480000,
	'scrawlUrlPrefix' => '',
	'scrawlInsertAlign' => 'none',

	'snapscreenActionName' => 'uploadimage',
	'snapscreenPathFormat' => '/Uploads/Article/image/{yyyy}{mm}{dd}/{time}{rand:6}',
	'snapscreenUrlPrefix' => '',
	'snapscreenInsertAlign' => 'none',

	'catcherLocalDomain' => array(0 => '127.0.0.1', 1 => 'localhost', 2 => 'aliyuncs.com'),
	'catcherActionName' => 'catchimage',
	'catcherFieldName' => 'source',
	'catcherPathFormat' => '/Uploads/Article/image/{yyyy}{mm}{dd}/{time}{rand:6}',
	'catcherUrlPrefix' => '',
	'catcherMaxSize' => 20480000,
	'catcherAllowFiles' => array(
		0 => '.png',
		1 => '.jpg',
		2 => '.jpeg',
		3 => '.gif',
		4 => '.bmp',
	),

	'videoActionName' => 'uploadvideo',
	'videoFieldName' => 'upfile',
	'videoPathFormat' => '/Uploads/Article/video/{yyyy}{mm}{dd}/{time}{rand:6}',
	'videoUrlPrefix' => '',
	'videoMaxSize' => 102400000,
	'videoAllowFiles' => array(
		0 => '.flv',
		1 => '.swf',
		2 => '.mkv',
		3 => '.avi',
		4 => '.rm',
		5 => '.rmvb',
		6 => '.mpeg',
		7 => '.mpg',
		8 => '.ogg',
		9 => '.ogv',
		10 => '.mov',
		11 => '.wmv',
		12 => '.mp4',
		13 => '.webm',
		14 => '.mp3',
		15 => '.wav',
		16 => '.mid',
	),

	'fileActionName' => 'uploadfile',
	'fileFieldName' => 'upfile',
	'filePathFormat' => '/Uploads/Article/file/{yyyy}{mm}{dd}/{time}{rand:6}',
	'fileUrlPrefix' => '',
	'fileMaxSize' => 20480000,
	'fileAllowFiles' => array(
		0 => '.png',
		1 => '.jpg',
		2 => '.jpeg',
		3 => '.gif',
		4 => '.bmp',
		5 => '.flv',
		6 => '.swf',
		7 => '.mkv',
		8 => '.avi',
		9 => '.rm',
		10 => '.rmvb',
		11 => '.mpeg',
		12 => '.mpg',
		13 => '.ogg',
		14 => '.ogv',
		15 => '.mov',
		16 => '.wmv',
		17 => '.mp4',
		18 => '.webm',
		19 => '.mp3',
		20 => '.wav',
		21 => '.mid',
		22 => '.rar',
		23 => '.zip',
		24 => '.tar',
		25 => '.gz',
		26 => '.7z',
		27 => '.bz2',
		28 => '.cab',
		29 => '.iso',
		30 => '.doc',
		31 => '.docx',
		32 => '.xls',
		33 => '.xlsx',
		34 => '.ppt',
		35 => '.pptx',
		36 => '.pdf',
		37 => '.txt',
		38 => '.md',
		39 => '.xml',
	),

	'imageManagerActionName' => 'listimage',
	'imageManagerListPath' => '/Uploads/Article/image/',
	'imageManagerListSize' => 20,
	'imageManagerUrlPrefix' => '',
	'imageManagerInsertAlign' => 'none',
	'imageManagerAllowFiles' => array(
		0 => '.png',
		1 => '.jpg',
		2 => '.jpeg',
		3 => '.gif',
		4 => '.bmp',
	),

	'fileManagerActionName' => 'listfile',
	'fileManagerListPath' => '/Uploads/Article/file/',
	'fileManagerUrlPrefix' => '',
	'fileManagerListSize' => 20,
	'fileManagerAllowFiles' => array(
		0 => '.png',
		1 => '.jpg',
		2 => '.jpeg',
		3 => '.gif',
		4 => '.bmp',
		5 => '.flv',
		6 => '.swf',
		7 => '.mkv',
		8 => '.avi',
		9 => '.rm',
		10 => '.rmvb',
		11 => '.mpeg',
		12 => '.mpg',
		13 => '.ogg',
		14 => '.ogv',
		15 => '.mov',
		16 => '.wmv',
		17 => '.mp4',
		18 => '.webm',
		19 => '.mp3',
		20 => '.wav',
		21 => '.mid',
		22 => '.rar',
		23 => '.zip',
		24 => '.tar',
		25 => '.gz',
		26 => '.7z',
		27 => '.bz2',
		28 => '.cab',
		29 => '.iso',
		30 => '.doc',
		31 => '.docx',
		32 => '.xls',
		33 => '.xlsx',
		34 => '.ppt',
		35 => '.pptx',
		36 => '.pdf',
		37 => '.txt',
		38 => '.md',
		39 => '.xml',
	),
);
?>