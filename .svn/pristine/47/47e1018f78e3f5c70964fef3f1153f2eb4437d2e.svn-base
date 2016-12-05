<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseMediateApplyViewModel extends ViewModel {
    public $viewFields = array(
        'CaseMediateApply' => array(
            'id',
            'case_id',
            'case_client_id',
            'case_mediate_accept_id',
            'apply_idno',
            'apply_time',
            'user_type',
            'apply_status',
            '_type' => 'LEFT',
        ),
        'CaseClient' => array(
            'name',
            '_on' => 'CaseClient.id=CaseMediateApply.case_client_id',
        ),
    );
}
?>