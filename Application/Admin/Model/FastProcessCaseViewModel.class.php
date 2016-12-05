<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class FastProcessCaseViewModel extends ViewModel {
    public $viewFields = array(
        'CaseInfo' => array(
            'id',
            'code',
            'accident_time',
            'accident_place',
            '_table' => '__CASE__',
            '_type' => 'LEFT',
        ),
        'CaseHandle' => array(
            '_type' => 'LEFT',
            '_on' => 'CaseHandle.case_id=CaseInfo.id',
        ),
        'UserInfo' => array(
            'true_name',
            '_table' => '__USER__',
            '_type' => 'LEFT',
            '_on' => 'CaseHandle.user_id=UserInfo.id',
        )
    );
}
?>