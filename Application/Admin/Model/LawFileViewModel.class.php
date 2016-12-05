<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class LawFileViewModel extends ViewModel {
    public $viewFields = array(
        'CaseFile' => array(
            'id',
            'case_id',
            'cate',
            'ext_ida',
            'ext_idb',
            'ext_idc',
            'ext_idd',
            'ext_ide',
            'file_id',
            'train',
            '_type' => 'LEFT'
        ),
        'File' => array(
            'name',
            'path',
            'file_size',
            'ext',
            'create_time',
            '_table' => '__FILE__',
            '_type' => 'LEFT',
            '_on' => 'File.id = CaseFile.file_id'
        ),
        'User' => array(
            'true_name',
            '_table' => "__USER__",
            '_on' => 'File.create_user_id = User.id'
        ),
    );
}
?>