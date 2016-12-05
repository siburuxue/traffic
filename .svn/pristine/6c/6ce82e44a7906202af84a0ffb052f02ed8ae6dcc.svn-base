<?php
namespace Admin\Controller;

/**
 * 日历
 */
class CalendarController extends CommonController {
    /**
     * 画面加载
     */
    public function index(){
        $this->display();
    }

    /**
     * 保存数据
     */
    public function update(){
        $map = array();
        $map['year'] = I('post.year','','int');
        $map['month'] = I('post.month','','int');
        $map['day'] = I('post.day','','int');
        $rs = M('Calendar')->where($map)->setField('is_holidays',I('post.is_holidays',I('post.is_holidays','','int')));
        if($rs === fasle){
            $this->error('数据保存失败');
        }
        $this->success('数据保存成功');
    }

    /**
     * 获取当前月份的天
     */
    public function getDays(){
        $map = array();
        $map['year'] = I('post.year');
        $map['month'] = I('post.month');
        $map['is_holidays'] = 1;
        $model = M('Calendar');
        $rs = $model->field('group_concat(day) as days')->where($map)->select();
        echo json_encode($rs[0]);
    }
}