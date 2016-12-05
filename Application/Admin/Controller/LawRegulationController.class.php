<?php
namespace Admin\Controller;

/**
 * 法律法规
 */
class LawRegulationController extends CommonController {
    private $array = array();
    /**
     * 首页界面
     */
    public function index() {
        // 渲染
        $this->display();
    }

    /**
     * 首页表格
     */
    public function indexTable(){
        $map = array();
        $map['is_del'] = 0;
        $list = M('LawRegulation')->where($map)->select();
        foreach($list as $key => $val){
            $map = array();
            $map['ext_ida'] = $val['id'];
            $map['cate'] = 1;
            $count = M('CaseFile')->where($map)->count();
            $list[$key]['count'] = $count;
        }
        $list = list_to_tree($list);
        $list = tree_to_array($list);
        $this->assign('list',$list);
        $this->display("LawRegulation/index/table");
    }

    /**
     * 添加文件夹画面加载
     */
    public function addFolder(){
        $map = array();
        $map['is_del'] = 0;
        $list = M('lawRegulation')->where($map)->select();
        $list = list_to_tree($list);
        $list = tree_to_array($list);
        $this->assign('list',$list);
        $this->display();
    }

    /**
     * 添加文件夹
     */
    public function insertFolder(){
        $model = D('LawRegulation');
        $data = $model->create();
        if($data === false){
            $this->error($model->getError());
        }
        $id = $model->add($data);
        if($id === false){
            $this->error('保存失败');
        }
        $this->success('保存成功');
    }

    /**
     * 编辑文件夹画面加载
     */
    public function editFolder(){
        $info = M('LawRegulation')->getById(I('get.id'));
        $map = array();
        $map['is_del'] = 0;
        $list = M('lawRegulation')->where($map)->select();
        $list = list_to_tree($list);
        $list = tree_to_array($list);
        $this->assign('list',$list);
        $this->assign('info',$info);
        $this->display();
    }

    /**
     * 修改文件夹
     */
    public function updateFolder(){
        $model = D('LawRegulation');
        $data = $model->create();
        if($data === false){
            $this->error($model->getError());
        }
        $rs = $model->save($data);
        if($rs === false){
            $this->error('保存失败');
        }
        $this->success('保存成功');
    }

    /**
     * 删除文件夹
     */
    public function delete(){
        $map = array();
        $map['id'] = I('post.id');
        $map['is_del'] = 1;
        $rs = M('LawRegulation')->save($map);
        if($rs === false){
            $this->error('删除失败');
        }
        $this->success('删除成功');
    }

    /**
     * 查看画面加载
     */
    public function viewFile(){
        $id = I('get.id');
        $info = M('LawRegulation')->getById($id);
        $this->assign('id',$id);
        $this->assign('info',$info);
        $this->display();
    }

    /**
     * 查看Table加载
     */
    public function viewFileTable(){
        $id = I('post.id');
        $map = array();
        $map['cate'] = 1;
        $map['ext_ida'] = $id;
        $map['CaseFile.is_del'] = 0;
        $map['File.is_del'] = 0;
        $list = D('LawFileView')->where($map)->select();
        foreach($list as $key => $val){
            $list[$key]['path'] = get_photo($list[$key]['path']);
        }
        $this->assign('list',$list);
        $this->display('LawRegulation/view/table');
    }

    /**
     * 查看画面加载
     */
    public function viewAll(){
        $id = I('get.id');
        $info = M('LawRegulation')->getById($id);
        $this->assign('id',$id);
        $this->assign('info',$info);
        $this->display();
    }

    /**
     * 查看全部Table加载
     */
    public function viewAllTable(){
        $id = I('post.id');
        //获取所有文件的文件夹路径
        $map['is_del'] = 0;
        $tree = M('LawRegulation')->where($map)->select();
        $tree = list_to_tree($tree);
        $tree = tree_to_array($tree);

        //获取所有子文件夹的ID
        $map = array();
        $map['is_del'] = 0;
        $list = M('LawRegulation')->where($map)->select();
        $list = list_to_tree($list);
        $obj = array();
        foreach($list as $key => $val){
            if($val['id'] == $id){
                $obj = $val;
                break;
            }
        }
        array_push($this->array,$obj['id']);
        self::getIds($obj['_child']);
        //获取文件夹下的文件
        $map = array();
        $map['cate'] = 1;
        $map['ext_ida'] = array('in',implode(',',$this->array));
        $map['CaseFile.is_del'] = 0;
        $map['File.is_del'] = 0;
        $list = D('LawFileView')->where($map)->order('CaseFile.ext_ida asc,File.id desc')->select();
        foreach($list as $key => $val){
            $list[$key]['path'] = get_photo($list[$key]['path']);
            foreach($tree as $k => $v){
                if($v['id'] == $val['ext_ida']){
                    $list[$key]['folderPath'] = $v['_prefix'].$v['title'];
                }
            }
        }
        $this->assign('list',$list);
        $this->display('LawRegulation/view/allTable');
    }

    //获取所有子文件夹的ID
    private function getIds($child){
        foreach($child as $key => $val){
            array_push($this->array,$val['id']);
            if(count($val['_child']) > 0){
                self::getIds($val['_child']);
            }
        }
    }
}