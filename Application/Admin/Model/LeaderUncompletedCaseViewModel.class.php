<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class LeaderUncompletedCaseViewModel extends ViewModel {
    public $viewFields = array(
        'CaseCheck'=>array(
            'id'=>'check_id',
            'item_id',
            'check_user_id',
            'cate',
            'level',
            '_type'=>'LEFT',
        ),
        'CaseInfo'=>array(
            'id',
            'code',
            'accident_time',
            'accident_place',
            'accident_type',
            'department_id',
            'create_user_id',
            'update_time',
            '_table'=>'__CASE__',
            '_on' => 'CaseInfo.id = CaseCheck.case_id'
        ),
        'CaseHandle'=>array(
            'is_now',
            'user_id',
            '_on'=>'CaseInfo.id = CaseHandle.case_id'
        ),
        'CaseUser' => array(
            'true_name' => 'case_user',
            '_table'=>'__USER__',
            '_on'=>'CaseUser.id = CaseHandle.user_id'
        ),
        'Department'=>array(
            'name'=>'department_name',
            '_on'=>'Department.id = CaseInfo.department_id'
        )
    );
}
?>