<?php
namespace Admin\Controller;
/**
 * 通用类
 */
class CommonController extends PublicController {
	private $renameList = array();
	private $catalog = array();

	/**
	 * 构造函数
	 */
	public function __construct() {
		parent::__construct();
		import("Org.Util.tcpdf.tcpdf_include");
		import("Org.Util.tcpdf.tcpdf");
		// 验证用户登录状态
		$userId = session('userid');
		if (empty($userId)) {
			$this->redirect('Sign/login');
		}

		// 当前用户信息
		$user = D('UserView')->getById($userId);
		if (empty($user)) {
			$this->redirect('Sign/logout');
		}
		$this->my = $user;

		// 当前用户的权限
		$map = array();
		$map['user_id'] = $userId;
		$map['is_del'] = 0;
		$this->myPower = D('PowerView')->where($map)->group('Power.id')->getField('name', true);

		// 全部部门信息
		$map = array();
		$map['is_del'] = 0;
		$this->allDepartment = M('Department')->where($map)->select();

	}

	/**
	 * 获取当前用户的大队信息
	 */
	protected function getMyBrigade() {
		$brigade = array();
		$deep = list_to_deep($this->allDepartment, $this->my['department_id']);
		$deep = array_reverse($deep);
		foreach ($deep as $key => $value) {
			if ($value['cate'] == 2) {
				$brigade = $value;
			}
		}
		return $brigade;
	}

	/**
	 * 保存操作日志
	 * $caseId 案件编号
	 * $content 日志内容
	 */
	protected function saveCaseLog($caseId = 0, $content = '') {
		$time = time();
		$data['case_id'] = $caseId;
		$data['log_time'] = $time;
		$data['log_user_id'] = $this->my['id'];
		$data['content'] = $content;
		return M('CaseLog')->add($data);
	}

	/**
	 * 发送短信
	 * $tels 电话号码数组
	 * $content 发送内容
	 */
	protected function sendSms($tels = array(), $content) {
		foreach ($tels as $key => $val) {

		}
		return true;
	}

	/**
	 * 获取图片列表
	 * $cate int 相册类型
	 */
	public function getPhotosList() {
		$caseId = I('request.case_id', '', 'int');
		$cate = I('request.cate', '', 'int');
		$extIdA = I('request.ext_ida', '', 'int');
		$extIdB = I('request.ext_idb', '', 'int');
		$extIdC = I('request.ext_idc', '', 'int');
		$extIdD = I('request.ext_idd', '', 'int');
		$extIdE = I('request.ext_ide', '', 'int');

		$map = array();
		$caseId === '' || $map['case_id'] = $caseId;
		$map['cate'] = $cate;
		$map['is_del'] = 0;
		$extIdA === '' || $map['ext_ida'] = $extIdA;
		$extIdB === '' || $map['ext_idb'] = $extIdB;
		$extIdC === '' || $map['ext_idc'] = $extIdC;
		$extIdD === '' || $map['ext_idd'] = $extIdD;
		$extIdE === '' || $map['ext_ide'] = $extIdE;

		$list = D('CasePhotoView')->where($map)->select();

		foreach ($list as $key => &$value) {
			$value['image_path'] = get_photo($value['image_path']);
			$value['thumb_path'] = get_photo($value['thumb_path']);
		}
		unset($value);

		$this->success($list);
	}

	/**
	 * 打包下载图片
	 */
	public function download() {

		$photoIds = I('get.ids', '', 'trim');

		if (empty($photoIds)) {
			$this->error('请选择下载图片');
		}

		$photoIds = explode(',', $photoIds);
		if (count($photoIds) < 1) {
			$this->error('请选择下载图片');
		}
		// 从FTP服务器下载的文件存放位置
		$downLoadDir = 'Uploads/Download/';
		$zipDir = 'Uploads/Zip/';

		// 创建临时目录
		\Lib\FileUtil::createDir($downLoadDir);
		\Lib\FileUtil::createDir($zipDir);

		// 提供下载压缩包文件路径
		$zipFilePath = $zipDir . \Org\Util\String::keyGen() . '.zip';

		// 获取图片信息
		$map = array();
		$map['id'] = array('in', $photoIds);
		$photo = D('CasePhotoView')->where($map)->select();
		if (empty($photo)) {
			$this->error('请选择下载图片');
		}
		if (count($photoIds) !== count($photo)) {
			$this->error('请选择下载图片');
		}

		// 创建zip
		$zip = new \ZipArchive();
		$zip->open($zipFilePath, \ZipArchive::OVERWRITE);

		$tempFiles = array();
		// 下载图片
		foreach ($photo as $key => $value) {
			$this->name = $value['name'];
			$filePath = $downLoadDir . \Org\Util\String::keyGen() . '.' . $value['image_ext'];
			$tempFiles[] = $filePath;
			\Org\Net\Http::curlDownload(get_photo($value['image_path']), $filePath);
			$zip->addFile($filePath, $this->name);
		}

		$zip->close();

		// 删除临时文件
		foreach ($tempFiles as $value) {
			\Lib\FileUtil::unlinkFile($value);
		}

		header("Location:$zipFilePath");

	}

	/**
	 * 打包下载文件
	 */
	public function downloadFiles() {

		$fileIds = I('get.ids', '', 'trim');

		if (empty($fileIds)) {
			$this->error('请选择下载文件');
		}

		$fileIds = explode(',', $fileIds);
		if (count($fileIds) < 1) {
			$this->error('请选择下载文件');
		}
		// 从FTP服务器下载的文件存放位置
		$downLoadDir = 'Uploads/Download/';
		$zipDir = 'Uploads/Zip/';

		// 创建临时目录
		\Lib\FileUtil::createDir($downLoadDir);
		\Lib\FileUtil::createDir($zipDir);

		// 提供下载压缩包文件路径
		$zipFilePath = $zipDir . \Org\Util\String::keyGen() . '.zip';

		// 获取图片信息
		$map = array();
		$map['id'] = array('in', $fileIds);
		$file = D('CaseFileView')->where($map)->select();
		$this->renameList = $file;
		if (empty($file)) {
			$this->error('请选择下载文件');
		}
		if (count($fileIds) !== count($file)) {
			$this->error('请选择下载文件');
		}

		// 创建zip
		$zip = new \ZipArchive();
		$zip->open($zipFilePath, \ZipArchive::OVERWRITE);

		$tempFiles = array();
		foreach ($this->renameList as $key => $val) {
			self::renameFiles($key);
		}
		// 下载图片
		foreach ($this->renameList as $key => $value) {
			$filePath = $downLoadDir . \Org\Util\String::keyGen() . '.' . $value['file_ext'];
			$tempFiles[] = $filePath;
			\Org\Net\Http::curlDownload(get_photo($value['file_path']), $filePath);
			$value['name'] = iconv("utf-8", "gb2312", $value['name']);
			$zip->addFile($filePath, $value['name']);
		}

		$zip->close();

		// 删除临时文件
		foreach ($tempFiles as $value) {
			\Lib\FileUtil::unlinkFile($value);
		}
		header("Location:$zipFilePath");
	}

	/**
	 * 文件重复重命名
	 */
	public function renameFiles($k, $i = 0) {
		foreach ($this->renameList as $key => $val) {
			$currName = $this->renameList[$k]['name'];
			if ($key != $k) {
				if ($currName == $val['name']) {
					$array = explode('.', $currName);
					$subArray = explode('(', $array[count($array) - 2]);
					if (count($subArray) >= 2) {
						$subArray[count($subArray) - 2] = $subArray[count($subArray) - 2] . "(" . ($i + 1) . ")";
						array_pop($subArray);
					} else {
						$subArray[0] = $subArray[0] . "(" . ($i + 1) . ")";
					}
					$array[count($array) - 2] = implode('(', $subArray);
					$this->renameList[$k]['name'] = implode('.', $array);
					self::renameFiles($k, $i + 1);
				}
			}
		}
	}

	/**
	 * 生成PDF
	 */
	public function createPDF() {
		$this->catalog = get_custom_config('catalog');
		$case_id = I('get.case_id');
		$time = time();
		$map = array();
		$map['CasePhoto.case_id'] = $case_id;
		$map['CasePhoto.is_document'] = 1;
		$list = D('CasePhotoView')->where($map)->order('Cate.train,CasePhoto.train,CasePhoto.id')->select();
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		foreach ($list as $key => &$value) {
			self::createCatalog($key, $value['cate']);
			$value['image_path'] = get_photo($value['image_path']);
			$pdf->AddPage();
			$pdf->Image($value['image_path'], '', '', 500, 700, '', '', '', false, 300, '', false, false, 1, false, false, false);
		}
		$model = M('CaseCatalog');
		$model->startTrans();
		$map = array();
		$map['case_id'] = $case_id;
		$rs = $model->where($map)->delete();
		if ($rs === false) {
			$model->rollback();
		}
		$map['json'] = json_encode($this->catalog);
		$map['create_user_id'] = $this->my['id'];
		$map['create_time'] = $time;
		$map['update_user_id'] = $this->my['id'];
		$map['update_time'] = $time;
		$id = $model->add($map);
		if (!$id) {
			$model->rollback();
		}
		$model->commit();
		$pdfName = \Org\Util\String::keyGen();
		$pdf->Output($pdfName . '.pdf', 'D');
	}

	/**
	 * 生成目录
	 * @param $key 图片的index
	 * @param $cate 图片类型
	 */
	private function createCatalog($key, $cate) {
		foreach ($this->catalog as $k => &$v) {
			if (in_array($cate, $v['cate'])) {
				if ($this->catalog[$k]['tags'] == '') {
					$this->catalog[$k]['tags'] = array(($key + 1) . "-" . ($key + 1));
				} else {
					$tags = $this->catalog[$k]['tags'];
					$tagArray = explode('-', $tags[0]);
					$tagArray[1] = $key + 1;
					$this->catalog[$k]['tags'] = array(implode('-', $tagArray));
				}
				if (in_array($cate, array(5, 6, 7, 8))) {
					foreach ($v['nodes'] as $index => $value) {
						if (in_array($cate, $value['cate'])) {
							if ($cate == $value['cate'][0]) {
								if ($this->catalog[$k]['nodes'][$index]['tags'] == '') {
									$this->catalog[$k]['nodes'][$index]['tags'] = array(($key + 1) . "-" . ($key + 1));
								} else {
									$tags = $this->catalog[$k]['nodes'][$index]['tags'];
									$tagArray = explode('-', $tags[0]);
									$tagArray[1] = $key + 1;
									$this->catalog[$k]['nodes'][$index]['tags'] = array(implode('-', $tagArray));
								}
							}
							if (count($value['cate']) > 1) {
								foreach ($value['nodes'] as $j => $item) {
									if ($cate == $item['cate'][0]) {
										if ($this->catalog[$k]['nodes'][$index]['nodes'][$j]['tags'] == '') {
											$this->catalog[$k]['nodes'][$index]['nodes'][$j]['tags'] = array(($key + 1) . "-" . ($key + 1));
										} else {
											$tags = $this->catalog[$k]['nodes'][$index]['nodes'][$j]['tags'];
											$tagArray = explode('-', $tags[0]);
											$tagArray[1] = $key + 1;
											$this->catalog[$k]['nodes'][$index]['nodes'][$j]['tags'] = array(implode('-', $tagArray));
										}
										return;
									}
								}
							}
						}
					}
				} else {
					foreach ($v['nodes'] as $index => $value) {
						if ($cate == $value['cate'][0]) {
							if ($this->catalog[$k]['nodes'][$index]['tags'] == '') {
								$this->catalog[$k]['nodes'][$index]['tags'] = array(($key + 1) . "-" . ($key + 1));
							} else {
								$tags = $this->catalog[$k]['nodes'][$index]['tags'];
								$tagArray = explode('-', $tags[0]);
								$tagArray[1] = $key + 1;
								$this->catalog[$k]['nodes'][$index]['tags'] = array(implode('-', $tagArray));
							}
							return;
						}
					}
				}
			}
		}
	}

	/**
	 * 获取案件状态
	 */
	protected function getCaseStatus($caseId = 0) {

		$map = array();
		$map['id'] = $caseId;
		$case = D('CaseView')->where($map)->find();

		// 无有效案件信息
		if (empty($case)) {
			return '案件不存在';
		}

		// 案件已作废
		if ($case['is_del'] == 1) {
			return '案件已作废';
		}

		// 案件正在被领导审批的信息
		$map = array();
		$map['case_id'] = $caseId;
		$map['status'] = 0;
		$caseCheck = M('CaseCheck')->where($map)->select();
		if (!empty($caseCheck)) {
			foreach ($caseCheck as $key => $value) {
				if ($value['cate'] == 0) {
					return '受案登记待审批';
				}
			}
			foreach ($caseCheck as $key => $value) {
				if ($value['cate'] == 2) {
					return '事故认定待审批';
				} elseif ($value['cate'] == 3) {
					return '呈请事故中止待审批';
				} elseif ($value['cate'] == 4) {
					return '道路交通事故证明待审批';
				}
			}
			foreach ($caseCheck as $key => $value) {
				if ($value['cate'] == 10) {
					return '检验鉴定延期申请待审批';
				} elseif ($value['cate'] == 11) {
					return '检验鉴定超期申请待审批';
				} elseif ($value['cate'] == 12) {
					return '检验鉴定延期、超期申请待审批';
				} elseif ($value['cate'] == 13) {
					return '重新申请检验鉴定待审批';
				}
			}
			foreach ($caseCheck as $key => $value) {
				if ($value['cate'] == 40) {
					return '复核结论待审批';
				}
			}
		}

		if ($case['cognizance_status'] == 0 && $case['checkup_status'] == 0 && $case['accept_status'] == 1) {
			return '委托检验鉴定';
		}
		if ($case['cognizance_status'] == 0 && $case['checkup_status'] == 1 && $case['accept_status'] == 1) {
			return '待提交调查报告';
		}
		if ($case['cognizance_status'] == 1) {
			return '待制作事故认定';
		}

		$map = array();
		$map['case_id'] = $caseId;
		$caseCognizance = M('CaseCognizance')->where($map)->order('create_time desc')->find();
		if (!empty($caseCognizance) && $caseCognizance['check_status'] == 2) {
			return '事故认定待送达';
		}

		$str = '未知';
		if ($case['review_submit_status'] == 1) {
			$str = 'XXX提请复核';
			if ($case['review_accept_status'] == 1) {
				$str = '复核受理';
				if ($case['review_result_status'] == 1) {
					$str = '待提交调查报告';
				}
			}
		}

		return $str;
	}

	/**
	 * 计算时限
	 */
	public function calculateTimeLimit($data, $k = 'id', $time_limit = 0) {
		if (!is_array($data)) {
			return false;
		}
		foreach ($data as $key => $val) {
			//案件ID
			$id = $val[$k];
			//现场勘查信息
			//现场勘查之日起 10日内
			$map = array();
			$map['case_id'] = $id;
			$surveyInfo = M('CaseSurvey')->where($map)->find();
			if (!empty($surveyInfo)) {
				$data[$key]['timestamp'] = $surveyInfo['start_time'] + 10 * 24 * 60 * 60;
				$data[$key]['timeLimit'] = date('Y-m-d', $data[$key]['timestamp']);
			}

			//肇事逃逸
			//交通肇事逃逸查获车辆和驾驶人后10日内
			$map = array();
			$map['case_id'] = $id;
			$map['is_escape'] = 1;
			$catchTime = M('CaseClient')->where($map)->max('CASE WHEN escape_catch_man_time > escape_catch_car_time THEN escape_catch_man_time ELSE escape_catch_car_time END');
			if ($catchTime > 0) {
				$data[$key]['timestamp'] = $catchTime + 10 * 24 * 60 * 60;
				$data[$key]['timeLimit'] = date('Y-m-d', $data[$key]['timestamp']);
			}

			//检验鉴定
			$map = array();
			$map['ts_case_checkup.case_id'] = $id;
			$map['ts_case_checkup.is_cancel'] = 0;
			$map['ts_case_checkup.is_del'] = 0;
			$map['ts_case_checkup_report.is_back'] = 0;
			$map['ts_case_checkup_report.is_del'] = 0;
			$checkUpTime = M('CaseCheckup')
				->join(' LEFT JOIN ts_case_checkup_report on ts_case_checkup_report.case_checkup_id = ts_case_checkup.id')
				->where($map)
				->max('ts_case_checkup_report.create_time');

			if ($checkUpTime != '' && $checkUpTime != null && $checkUpTime != '0') {
				$data[$key]['timestamp'] = $checkUpTime + 5 * 24 * 60 * 60;
				$data[$key]['timeLimit'] = date('Y-m-d', $data[$key]['timestamp']);
			}
			//事故认定时限
			//如果是一般事故认定调查报告审批通过时间起60日内
			$map = array();
			$map['case_id'] = $id;
			$map['cognizance_type'] = 1;
			$map['is_back'] = 0;
			$cognizanceInfo = M('CaseCognizance')->where($map)->find();
			if (!empty($cognizanceInfo)) {
				if ($cognizanceInfo['is_make'] == '1') {
					$data[$key]['timestamp'] = 2147483647; //int整数最大值
					$data[$key]['timeLimit'] = '已完成';
				} else {
					$map = array();
					$map['status'] = 1;
					$map['case_id'] = $id;
					$map['cate'] = 3;
					$stopInfo = M('CaseCheck')->where($map)->find();
					if (!empty($stopInfo)) {
						$data[$key]['timestamp'] = (int) $stopInfo['check_time'] + 60 * 24 * 60 * 60;
						$data[$key]['timeLimit'] = date('Y-m-d', $data[$key]['timestamp']);
					}
				}
			}
			//如果是简易事故认定 没有时限
			$map = array();
			$map['case_id'] = $id;
			$map['cognizance_type'] = 0;
			$map['is_back'] = 0;
			$simpleInfo = M('CaseCognizance')->where($map)->find();
			if (!empty($simpleInfo)) {
				$data[$key]['timestamp'] = 2147483646; //int整数最大值-1
				$data[$key]['timeLimit'] = '-';
			}
			//如果上面的事都没做时限取事故发生时间起10日内
			if ($data[$key]['timestamp'] == '') {
				$caseInfo = M('Case')->getById($id);
				if (!empty($caseInfo)) {
					$data[$key]['timestamp'] = $caseInfo['accident_time'] + 10 * 24 * 60 * 60;
					$data[$key]['timeLimit'] = date('Y-m-d', $data[$key]['timestamp']);
				}
			}
		}
		if ($time_limit) {
			if ($time_limit == 1) {
				usort($data, function ($a, $b) {
					if ($a['timestamp'] > $b['timestamp']) {
						return 1;
					} else if ($a['timestamp'] < $b['timestamp']) {
						return -1;
					} else if ($a['timestamp'] == $b['timestamp']) {
						return $a['accident_time'] < $b['accident_time'];
					}
				});

			}
			if ($time_limit == 2) {
				usort($data, function ($b, $a) {
					if ($a['timestamp'] > $b['timestamp']) {
						return 1;
					} else if ($a['timestamp'] < $b['timestamp']) {
						return -1;
					} else if ($a['timestamp'] == $b['timestamp']) {
						return $a['accident_time'] < $b['accident_time'];
					}
				});

			}

		} else {

			usort($data, function ($a, $b) {
				if ($a['timestamp'] > $b['timestamp']) {
					return 1;
				} else if ($a['timestamp'] < $b['timestamp']) {
					return -1;
				} else if ($a['timestamp'] == $b['timestamp']) {
					return $a['accident_time'] < $b['accident_time'];
				}
			});

		}

		$now = time();
		foreach ($data as $key => $val) {
			if ($val['timestamp'] <= $now) {
				$data[$key]['time_status'] = -2; //已过期
			} else {
				if ($val['timestamp'] - $now <= 3 * 24 * 60 * 60) {
					$data[$key]['time_status'] = -1; //将要过期
				} else {
					$data[$key]['time_status'] = 0;
				}
			}
		}
		return $data;
	}

	/**
	 * 计算某个案件的时限
	 * @param $id 案件ID
	 */
	public function calculateCaseTimeLimit($id) {
		$timestamp = "";
		$timeLimit = "";
		//现场勘查信息
		//现场勘查之日起 10日内
		$map = array();
		$map['case_id'] = $id;
		$surveyInfo = M('CaseSurvey')->where($map)->find();
		if (!empty($surveyInfo)) {
			$timestamp = $surveyInfo['start_time'] + 10 * 24 * 60 * 60;
			$timeLimit = 10;
		}

		//肇事逃逸
		//交通肇事逃逸查获车辆和驾驶人后10日内
		$map = array();
		$map['case_id'] = $id;
		$map['is_escape'] = 1;
		$catchTime = M('CaseClient')->where($map)->max('CASE WHEN escape_catch_man_time > escape_catch_car_time THEN escape_catch_man_time ELSE escape_catch_car_time END');
		if ($catchTime > 0) {
			$timestamp = $catchTime + 10 * 24 * 60 * 60;
			$timeLimit = 10;
		}

		//检验鉴定
		$map = array();
		$map['ts_case_checkup.case_id'] = $id;
		$map['ts_case_checkup.is_cancel'] = 0;
		$map['ts_case_checkup.is_del'] = 0;
		$map['ts_case_checkup_report.is_back'] = 0;
		$map['ts_case_checkup_report.is_del'] = 0;
		$checkUpTime = M('CaseCheckup')
			->join(' LEFT JOIN ts_case_checkup_report on ts_case_checkup_report.case_checkup_id = ts_case_checkup.id')
			->where($map)
			->max('ts_case_checkup_report.create_time');

		if ($checkUpTime != '' && $checkUpTime != null && $checkUpTime != '0') {
			$timestamp = $checkUpTime + 5 * 24 * 60 * 60;
			$timeLimit = 5;
		}
		//事故认定时限
		//如果是一般事故认定调查报告审批通过时间起60日内
		$map = array();
		$map['case_id'] = $id;
		$map['cognizance_type'] = 1;
		$map['is_back'] = 0;
		$cognizanceInfo = M('CaseCognizance')->where($map)->find();
		if (!empty($cognizanceInfo)) {
			if ($cognizanceInfo['is_make'] == '1') {
				$timestamp = '已完成';
				$timeLimit = 60;
			} else {
				$map = array();
				$map['status'] = 1;
				$map['case_id'] = $id;
				$map['cate'] = 3;
				$stopInfo = M('CaseCheck')->where($map)->find();
				if (!empty($stopInfo)) {
					$timestamp = (int) $stopInfo['check_time'] + 60 * 24 * 60 * 60;
					$timeLimit = 60;
				}
			}
		}
		//如果是简易事故认定 没有时限
		$map = array();
		$map['case_id'] = $id;
		$map['cognizance_type'] = 0;
		$map['is_back'] = 0;
		$simpleInfo = M('CaseCognizance')->where($map)->find();
		if (!empty($simpleInfo)) {
			$timestamp = '-';
			$timeLimit = '-';
		}
		//如果上面的事都没做时限取事故发生时间起10日内
		if ($timestamp == '') {
			$caseInfo = M('Case')->getById($id);
			if (!empty($caseInfo)) {
				$timestamp = $caseInfo['accident_time'] + 10 * 24 * 60 * 60;
				$timeLimit = 10;
			}
		}
		$result = array();
		$result['timeLimit'] = $timeLimit;
		if ($timestamp != '已完成' && $timestamp != '-') {
			$timeDiff = $timestamp - time();
			if ($timeDiff < 0) {
				$result['timestamp'] = '已过期';
			} else {
				$result['timestamp'] = $timeLimit - floor($timeDiff / (24 * 60 * 60));
			}
		} else {
			$result['timestamp'] = $timestamp;
		}
		return $result;
	}

	/**
	 * 生成二维码
	 * @param $text 表示生成二位的的信息文本
	 * @param bool $outfile 表示是否输出二维码图片 文件，默认否
	 * @param int $level 表示容错率，也就是有被覆盖的区域还能识别，分别是 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）
	 * @param int $size 表示生成图片大小，默认是3
	 * @param int $margin 表示二维码周围边框空白区域间距值
	 * @param bool $saveandprint 表示是否保存二维码并显示
	 * @return bool|string 成功、失败|二维码图片的路径
	 */
	protected function createQRCode($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint = false) {
		Vendor("phpqrcode.phpqrcode");
		$fileDir = $_SERVER['DOCUMENT_ROOT'] . 'Uploads/qrcode/' . date('Ymd') . '/';
		if (!file_exists($fileDir)) {
			mkdir($fileDir);
		}
		$src = \Org\Util\String::keyGen() . ".png";
		$outfile = $fileDir . $src;
		\QRcode::png($text, $outfile, $level, $size, $margin);
		return 'Uploads/qrcode/' . date('Ymd') . '/' . $src;
	}

}