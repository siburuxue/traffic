<?php
namespace Admin\Controller;
use Lib\FileUtil;
use Lib\Ftp;

/**
 * 上传文件
 */
class FilesController extends CommonController {
	public function __construct() {

		parent::__construct();

		// 获取案件编号
		$caseId = I('request.case_id', '', '');
		if($caseId == ''){
			// 获取案件信息
			$case = D('CaseView')->getById($caseId);
			// 当前案件信息
			$this->case = $case;
		}else{
			$this->case = array('code' => 0);
		}


	}
	/**
	 * 首页
	 */
	public function index() {
		$cate = I('get.cate', '', 'int');
		$extIda = I('get.ext_ida', '', 'int');
		$extIdb = I('get.ext_idb', '', 'int');
		$extIdc = I('get.ext_idc', '', 'int');
		$extIdd = I('get.ext_idd', '', 'int');
		$extIde = I('get.ext_ide', '', 'int');
		if ($cate === '') {
			$this->error('未指定文件所在节点');
		}
		$this->assign('cate', $cate);
		$this->assign('ext_ida', $extIda);
		$this->assign('ext_idb', $extIdb);
		$this->assign('ext_idc', $extIdc);
		$this->assign('ext_idd', $extIdd);
		$this->assign('ext_ide', $extIde);

		$this->display();

	}

	public function upload() {
		$time = time();
		$userId = $this->my['id'];
		$cate = I('post.cate', '', 'int');
		$extIda = I('post.ext_ida', 0, 'int');
		$extIdb = I('post.ext_idb', 0, 'int');
		$extIdc = I('post.ext_idc', 0, 'int');
		$extIdd = I('post.ext_idd', 0, 'int');
		$extIde = I('post.ext_ide', 0, 'int');
		if ($cate === '') {
			$this->error('未指定文件所在节点');
		}
		// 上传文件根目录
		$rootPath = 'Uploads/';
		$saveName = \Org\Util\String::keyGen();
		$savePath = 'File/';
		// 创建根目录
		FileUtil::createDir($rootPath . $savePath);

		// 上传
		$upload = new \Think\Upload(); // 实例化上传类
		$upload->maxSize = C('UPLOAD_MAXSIZE'); // 设置附件上传大小
		$upload->exts = C('UPLOAD_FILES_EXTS'); // 设置附件上传类型
		$upload->rootPath = $rootPath; // 设置附件上传根目录
		$upload->savePath = $savePath; // 设置附件上传（子）目录
		$upload->saveName = $saveName; //
		$upload->autoSub = false;
		// 上传文件
		$info = $upload->uploadOne($_FILES['file']);
		if (!$info) {
			// 上传错误提示错误信息
			$this->error($upload->getError());
		}

		// 本地服务器文件路径
		$tempFilePath = $rootPath . $info['savepath'] . $info['savename'];
		// 储存到FTP时文件名称
		$fileName = $saveName . '.' . strtolower($info['ext']);
		// 储存到FTP原文件路径
		$filePath = $rootPath . $this->case['code'] . '/file/' . $cate . '/' . $fileName;

		// 上传到FTP服务器
		$ftp = new Ftp();
		// 创建链接
		$res = $ftp->start(C('FTP'));
		// 上传文件
		if ($res) {
			$res = $ftp->put('/' . $filePath, $tempFilePath);
		}

		// 删除本地服务器文件
		FileUtil::unlinkFile($tempFilePath);

		if (false === $res) {
			$ftp->delete('/' . $filePath);
			$this->error($ftp->get_error());
		}

		$Model = M('CaseFile');
		// 开启事务
		$Model->startTrans();

		//上传文件
		$fileData = array();
		$fileData['name'] = $info['name'];
		$fileData['path'] = $filePath;
		$fileData['file_size'] = $info['size'];
		$fileData['ext'] = strtolower($info['ext']);
		$fileData['create_time'] = $time;
		$fileData['create_user_id'] = $userId;
		$fileData['update_time'] = $time;
		$fileData['update_user_id'] = $userId;
		$fileData['is_del'] = 0;
		$fileId = M('File')->add($fileData);

		if (!$fileId) {
			$Model->rollback();
			$ftp->delete('/' . $filePath);
			$this->error('数据保存失败');
		}

		$data['case_id'] = $this->case['id'];
		$data['cate'] = $cate;
		$data['ext_ida'] = $extIda;
		$data['ext_idb'] = $extIdb;
		$data['ext_idc'] = $extIdc;
		$data['ext_idd'] = $extIdd;
		$data['ext_ide'] = $extIde;
		$data['file_id'] = $fileId;
		$data['train'] = 0;
		$data['create_time'] = $time;
		$data['create_user_id'] = $userId;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$data['is_del'] = 0;
		$photoId = $Model->add($data);

		if (!$photoId) {
			$Model->rollback();
			$ftp->delete('/' . $filePath);
			$this->error('数据保存失败');
		}
		// 关闭FTP链接
		$ftp->close();
		$Model->commit();
		$this->success('上传成功');
	}

	public function delete() {
		// 获取删除的文件编号一维数组
		$ids = I('post.ids');
		// 验证文件编号数组
		if (empty($ids) || false === is_array($ids) || count($ids) <= 0) {
			$this->error('非法操作');
		}
		// 查询全部需要删除的文件
		$map = array();
		$map['id'] = array('in', $ids);
		$caseFile = D('CaseFileView')->where($map)->group('CaseFile.id')->select();
		if (empty($caseFile) || count($caseFile) !== count($ids)) {
			$this->error('请选择有效的文件');
		}
		// 获取需要删除的文件编号和路径
		$fileIds = array();
		$filePaths = array();
		foreach ($caseFile as $key => $value) {
			$fileIds[] = $value['file_id'];
			$filePaths[] = $value['file_path'];
		}

		$Model = M('CaseFile');
		// 开启事务
		$Model->startTrans();
		// 删除文件表
		$map = array();
		$map['id'] = array('in', $ids);
		$res = $Model->where($map)->delete();
		// 处理异常
		if (!$res) {
			$Model->rollback();
			$this->error('数据操作失败');
		}
		// 删除文件表
		$map = array();
		$map['id'] = array('in', $fileIds);
		$res = M('File')->where($map)->delete();
		// 处理异常
		if (!$res) {
			$Model->rollback();
			$this->error('数据操作失败');
		}
		// 删除文件
		$ftp = new Ftp();
		// 创建链接
		$res = $ftp->start(C('FTP'));

		// 删除原图
		if ($res) {
			foreach ($filePaths as $path) {
				$ftp->delete('/' . $path);
			}
		}
		$ftp->close();

		// 保存
		$Model->commit();
		$this->success('删除成功');

	}

}