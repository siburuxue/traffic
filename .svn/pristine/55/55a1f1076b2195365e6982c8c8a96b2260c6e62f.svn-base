<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class AccidentSearchLeaderViewModel extends ViewModel {
    public $viewFields = array(
        'CaseInfo' => array(
            'id',
            'code',
            'accident_time ',
            'accident_place',
            'accident_type',
            'is_del',
            '_type' => 'LEFT',
            '_table'=>'__CASE__',
        ),
        'CaseHandle'=>array(
            '_type' => 'LEFT',
            '_on' => 'CaseInfo.id = CaseHandle.case_id',
        ),
        'UserInfo' => array(
            'true_name',
            '_type' => 'LEFT',
            '_on' => 'CaseHandle.user_id = UserInfo.id',
            '_table' => '__USER__'
        ),
        'CaseClient' => array(
            'GROUP_CONCAT(CASE WHEN is_escape <> 0 THEN 1 END)' => 'is_escape',
            'GROUP_CONCAT(CASE WHEN escape_catch_man_time <> 0 THEN 1 END)' => 'is_catch',
            'GROUP_CONCAT(name)' => 'client_names',
            '_type' => 'LEFT',
            '_on' => 'CaseClient.case_id = CaseInfo.id',
        ),
    );
}
?>