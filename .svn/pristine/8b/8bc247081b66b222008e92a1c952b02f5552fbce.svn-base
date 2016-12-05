<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCognizanceRejectViewModel extends ViewModel {
    public $viewFields = array(
        'CaseCognizance' => array(
            'id',
            'back_user_id',
            'back_reason',
            'submit_time',
            'back_time',
            '_type' => 'LEFT',

        ),
        'CaseCognizanceReport' => array(
            '_type' => 'LEFT',
            '_on' => 'CaseCognizanceReport.case_cognizance_id = CaseCognizance.id'
        ),
        'UserInfo' => array(
            'true_name',
            '_type' => 'LEFT',
            '_on' => 'CaseCognizance.back_user_id = UserInfo.id',
            '_table' => '__USER__',
        ),
    );
}
?>