<?php
namespace Admin\Controller;
use \Lib\Page;
use Think\Model;

/**
 * 用户
 */
class CustomSearchTemplateServiceController extends CommonController {
	/**
	 * 首页界面
	 */
	public function index() {
		// 渲染
		$this->display();
	}

	/**
	 * 表格界面
	 */
	public function indexTable() {
        $bridgeInfo = $this->getMyBrigade();
		// 搜索条件
		$map = array();
        $map['bridge_id'] = $bridgeInfo['id'];
		$map['is_del'] = 0;
		// 列表信息
		$Model = M('CustomSearchTemplate');
		$count = $Model->where($map)->count('distinct id');
		$page = new Page($count, 15);
		$list = $Model->where($map)->order('id desc')->limit($page->firstrow . ',' . $page->rows)->select();

		// 分页信息
		$pageInfo = array(
			'totalrows' => $count,
			'totalpage' => $page->totalpages,
			'nowpage' => $page->nowpage,
		);
		$this->assign('page', $pageInfo);
        $this->assign('list', $list);
		// 渲染
		$this->display('CustomSearchTemplateService/index/table');
	}

	/**
	 * 新增界面
	 */
	public function add() {
		// 渲染
		$this->display();
	}

	/**
	 * 新增
	 */
	public function insert() {
        $model = D('CustomSearchTemplate');
        $data = $model->create();
        if($data === false){
            $this->error($model->getError());
        }
        $bridgeInfo = $this->getMyBrigade();
        $data['bridge_id'] = $bridgeInfo['id'];
        $id = $model->add($data);
        if(!$id){
            $this->error('新增失败');
        }
		$this->success('新增成功');
	}

	/**
	 * 编辑界面
	 */
	public function edit() {
        // 获取用户编号
        $id = I('get.id', '', 'int');
        if ($id === '') {
            $this->error('非法操作');
        }
        $info = M('CustomSearchTemplate')->getById($id);
        $this->assign('info',$info);
		// 渲染
		$this->display();
	}

	/**
	 * 更新
	 */
	public function update() {
        // 获取用户编号
        $id = I('post.id', '', 'int');
        if ($id === '') {
            $this->error('非法操作');
        }
        $model = D('CustomSearchTemplate');
        $data = $model->create();
        if($data === false){
            $this->error($model->getError());
        }
        $rs = $model->save($data);
        if($rs === false){
            $this->error('新增失败');
        }
		$this->success('保存成功');
	}

	/**
	 * 删除
	 */
	public function delete() {
		$id = I('get.id', '', 'int');
		if ($id === '') {
			$this->error('非法操作');
		}
		$map['id'] = $id;
		$result = M('CustomSearchTemplate')->where($map)->setField('is_del', 1);
		if ($result) {
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}

    public function queryTable() {
        $config = get_custom_config();
        $model = new Model();
        $sql = I('param.sql');
        $countSql = "select count(1) as num from ({$sql}) A";
        $countRs = $model->query($countSql);
        $count = $countRs[0]['num'];
        $page = new Page($count, 15);
        // 分页信息
        $pageInfo = array(
            'totalrows' => $count,
            'totalpage' => $page->totalpages,
            'nowpage' => $page->nowpage,
        );
        $sql = $sql . " limit " . $page->firstrow . "," . $page->rows;
        $list = $model->query($sql);
        $keys = array_keys($list[0]);
        foreach($keys as $key => $val){
            if(strpos($val,'_') === 0){
                $array = explode('__',$val);
                $keys[$key] = $array[1];
                $type = substr($array[0],1);
                $subConfig = $config[$type];
                foreach ($list as $k => $v){
                    $item = $v[$val];
                    $list[$k][$val] = $subConfig[$item];
                }
            }
        }
        $this->assign('page', $pageInfo);
        $this->assign('list', $list);
        $this->assign('keys', $keys);
        // 渲染
        $this->display('CustomSearchTemplateService/query/table');
    }
}
