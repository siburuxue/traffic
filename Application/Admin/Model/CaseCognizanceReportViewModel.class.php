<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCognizanceReportViewModel extends ViewModel {
    public $viewFields = array(
        'CaseCognizance' => array(
            'check_status',
            'cognizance_type',
            'is_back',
            '_type' => 'LEFT',

        ),
        'CaseCognizanceReport' => array(
            'id',
            'update_time',
            '_on' => 'CaseCognizance.id = CaseCognizanceReport.case_cognizance_id',
        ),
    );
}
?>