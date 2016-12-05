<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseCognizanceSimpleViewModel extends ViewModel {
    public $viewFields = array(
        'CaseCognizance' => array(
            'id',
            'is_printed',
            'is_back',
            '_type' => 'LEFT',
        ),
        'CaseCognizanceSimple' => array(
            'info',
            'compensate',
            '_on' => 'CaseCognizanceSimple.case_cognizance_id = CaseCognizance.id',
        ),
    );
}
?>