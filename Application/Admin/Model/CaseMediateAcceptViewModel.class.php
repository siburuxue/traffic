<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseMediateAcceptViewModel extends ViewModel {
    public $viewFields = array(
        'CaseMediateAccept' => array(
            'id',
            'handle_id',
            'status',
            '_type' => 'LEFT',
        ),
        'UserInfo' => array(
            'true_name',
            '_table' => '__USER__',
            '_on' => 'UserInfo.id=CaseMediateAccept.handle_id',
        ),
    );
}
?>