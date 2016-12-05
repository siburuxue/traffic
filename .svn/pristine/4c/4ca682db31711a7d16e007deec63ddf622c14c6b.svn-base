<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCognizanceEscapeViewModel extends ViewModel {
    public $viewFields = array(
        'CaseCoescape' => array(
            'id',
            'apply_time',
            '_type' => 'LEFT',
        ),
        'CaseClient' => array(
            'name',
            '_on' => 'CaseClient.id = CaseCoescape.case_client_id',
        ),
    );
}
?>