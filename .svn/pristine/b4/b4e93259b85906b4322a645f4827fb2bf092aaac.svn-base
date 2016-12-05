<?php
namespace Admin\Controller;
use Lib\FileUtil;
use Lib\Ftp;

/**
 * 相片
 */
class CasePhotoController extends CommonController {

	public function __construct() {

		parent::__construct();

		// 获取案件编号
		$caseId = I('request.case_id', '', '');
		if ($caseId === '') {
			$this->error('无效的案件编号');
		}
		// 获取案件信息
		$case = D('CaseView')->getById($caseId);

		if (empty($case)) {
			$this->error('案件不存在');
		}
		if ($case['is_del'] == 1) {
			$this->error('案件已作废');
		}
		// 当前案件信息
		$this->case = $case;

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
		$tpl = I('get.tpl', '', 'trim');
		if ($cate === '') {
			$this->error('未指定图片所在节点');
		}
		$this->assign('cate', $cate);
		$this->assign('ext_ida', $extIda);
		$this->assign('ext_idb', $extIdb);
		$this->assign('ext_idc', $extIdc);
		$this->assign('ext_idd', $extIdd);
		$this->assign('ext_ide', $extIde);

		$this->display($tpl);

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
			$this->error('未指定图片所在节点');
		}
		// 上传文件根目录
		$rootPath = 'Uploads/';
		$saveName = \Org\Util\String::keyGen();
		$savePath = 'Image/';
		// 创建根目录
		FileUtil::createDir($rootPath . $savePath);
		FileUtil::createDir($rootPath . 'Thumb/');

		// 上传
		$upload = new \Think\Upload(); // 实例化上传类
		$upload->maxSize = C('UPLOAD_MAXSIZE'); // 设置附件上传大小
		$upload->exts = C('UPLOAD_EXTS'); // 设置附件上传类型
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

		// 本地服务器原图路径
		$tempImagePath = $rootPath . $info['savepath'] . $info['savename'];
		// 本地服务器缩略图路径
		$tempThumbPath = $rootPath . 'Thumb/' . $info['savename'];
		// 储存到FTP时文件名称
		$fileName = $saveName . '.' . strtolower($info['ext']);
		// 储存到FTP原图片路径
		$imagePath = $rootPath . $this->case['code'] . '/image/' . $cate . '/' . $fileName;
		// 储存到FTP缩略图路径
		$thumbPath = $rootPath . $this->case['code'] . '/thumb/' . $cate . '/' . $fileName;

		// 生成缩略图
		$image = new \Think\Image();
		$image->open($tempImagePath);
		$imageWidth = $image->width();
		$imageHeight = $image->height();
		$image->thumb(C('PHOTO_THUMB_WIDTH'), C('PHOTO_THUMB_HEIGHT'), \Think\Image::IMAGE_THUMB_CENTER)->save($tempThumbPath);
		$thumbSize = filesize($tempThumbPath);

		// 上传到FTP服务器
		$ftp = new Ftp();
		// 创建链接
		$res = $ftp->start(C('FTP'));
		// 上传原图
		if ($res) {
			$res = $ftp->put('/' . $imagePath, $tempImagePath);
		}
		// 上传缩略图
		if ($res) {
			$res = $ftp->put('/' . $thumbPath, $tempThumbPath);
		}

		// 删除本地服务器文件
		FileUtil::unlinkFile($tempImagePath);
		FileUtil::unlinkFile($tempThumbPath);

		if (false === $res) {
			$ftp->delete('/' . $imagePath);
			$ftp->delete('/' . $thumbPath);
			$this->error($ftp->get_error());
		}

		$Model = M('CasePhoto');
		// 开启事务
		$Model->startTrans();

		// 原图
		$imageData = array();
		$imageData['name'] = $info['name'];
		$imageData['path'] = $imagePath;
		$imageData['file_size'] = $info['size'];
		$imageData['width'] = $imageWidth;
		$imageData['height'] = $imageHeight;
		$imageData['ext'] = strtolower($info['ext']);
		$imageData['create_time'] = $time;
		$imageData['create_user_id'] = $userId;
		$imageData['update_time'] = $time;
		$imageData['update_user_id'] = $userId;
		$imageData['is_del'] = 0;
		$imageId = M('Image')->add($imageData);

		// 缩略图
		$thumbData = array();
		$thumbData['name'] = '';
		$thumbData['path'] = $thumbPath;
		$thumbData['file_size'] = $thumbSize;
		$thumbData['width'] = 400;
		$thumbData['height'] = 565;
		$thumbData['ext'] = strtolower($info['ext']);
		$thumbData['create_time'] = $time;
		$thumbData['create_user_id'] = $userId;
		$thumbData['update_time'] = $time;
		$thumbData['update_user_id'] = $userId;
		$thumbData['is_del'] = 0;
		$thumbId = M('Image')->add($thumbData);

		if (!$imageId || !$thumbId) {
			$Model->rollback();
			$ftp->delete('/' . $imagePath);
			$ftp->delete('/' . $thumbPath);
			$this->error('数据保存失败');
		}

		$data['case_id'] = $this->case['id'];
		$data['cate'] = $cate;
		$data['ext_ida'] = $extIda;
		$data['ext_idb'] = $extIdb;
		$data['ext_idc'] = $extIdc;
		$data['ext_idd'] = $extIdd;
		$data['ext_ide'] = $extIde;
		$data['image_id'] = $imageId;
		$data['thumb_id'] = $thumbId;
		$data['train'] = 0;
		$data['create_time'] = $time;
		$data['create_user_id'] = $userId;
		$data['update_time'] = $time;
		$data['update_user_id'] = $userId;
		$data['is_del'] = 0;
		$photoId = $Model->add($data);

		if (!$photoId) {
			$Model->rollback();
			$ftp->delete('/' . $imagePath);
			$ftp->delete('/' . $thumbPath);
			$this->error('数据保存失败');
		}
		// 关闭FTP链接
		$ftp->close();
		$Model->commit();
		$this->success('上传成功');

	}

	public function delete() {
		// 获取删除的照片编号一维数组
		$ids = I('post.ids');
		// 验证图片编号数组
		if (empty($ids) || false === is_array($ids) || count($ids) <= 0) {
			$this->error('非法操作');
		}
		// 查询全部需要删除的照片
		$map = array();
		$map['id'] = array('in', $ids);
		$casePhoto = D('CasePhotoView')->where($map)->group('CasePhoto.id')->select();
		if (empty($casePhoto) || count($casePhoto) !== count($ids)) {
			$this->error('请选择有效的图片');
		}
		// 获取需要删除的图片编号和路径
		$imageIds = array();
		$imagePaths = array();
		foreach ($casePhoto as $key => $value) {
			$imageIds[] = $value['image_id'];
			$imageIds[] = $value['thumb_id'];
			$imagePaths[] = $value['image_path'];
			$imagePaths[] = $value['thumb_path'];
		}

		$Model = M('CasePhoto');
		// 开启事务
		$Model->startTrans();
		// 删除照片表
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
		$map['id'] = array('in', $imageIds);
		$res = M('Image')->where($map)->delete();
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
			foreach ($imagePaths as $path) {
				$ftp->delete('/' . $path);
			}
		}
		$ftp->close();

		// 保存
		$Model->commit();
		$this->success('删除成功');

	}

}