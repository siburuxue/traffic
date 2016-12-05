<?php
namespace Admin\Model;
use Think\Model\ViewModel;

class CaseClientAgreementViewModel extends ViewModel {
	public $viewFields = array(
		'CaseClient' => array(
			'id' => 'client_id',
			'name',
			'idno',
			'car_no',
			'grade_type',
			'tel',
			'_type' => 'LEFT',
		),
		'FastAgreement' => array(
			'id',
			'insurance_company',
			'policy_number1',
			'policy_number2',
			'report_number',
			'headstock',
			'right_anterior_horn',
			'left_anterior_horn',
			'tail_stock',
			'right_rear_corner',
			'left_rear_corner',
			'left_side',
			'right_side',
			'rear_end',
			'recessive',
			'backing_up',
			'sliding',
			'open_close_door',
			'violate_traffic_signal',
			'according_stipulations',
			'others',
			'all_responsibility',
			'equal_responsibility',
			'no_responsibility',
			'_type' => 'LEFT',
			'_on' => 'CaseClient.id = FastAgreement.client_id',
		)
	);
}
?>