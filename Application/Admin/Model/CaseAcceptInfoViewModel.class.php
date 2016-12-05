<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseAcceptInfoViewModel extends ViewModel {
    public $viewFields = array(
        'CaseInfo'=>array(
            'id'=>'case_id',
            '_table'=>'__CASE__',
            '_type'=>'LEFT'
        ),
        'CaseAlarm'=>array(
            '_type'=>'LEFT',
            '_on'=>'CaseInfo.id=CaseAlarm.case_id'
        ),
        'Alarm'=>array(
            'FROM_UNIXTIME(alarm_time)'=>'alarm_time',
            'case_source',
            'alarm_name',
            'contact',
            '_type'=>'LEFT',
            '_on'=>'CaseAlarm.alarm_id = Alarm.id'
        )
    );
}
?>