<?php

class RFEForm extends AppModel {
		
    public $name = 'RFEForm';
    public $useTable = false;

	public $step1Validation = array(
		'organization_name' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'organization_address' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'organization_city' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'organization_state' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'organization_zip' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'organization_phone' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'ceo_name' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'ceo_title' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'ceo_email' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'ceo_phone' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'billing_name' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'billing_title' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'billing_email' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'billing_phone' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'schedule_first_choice' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'schedule_second_choice' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'schedule_third_choice' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'organization_operating_budget' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		)
	);

	public $step2Validation = array(
	);

	public $step3Validation = array(
		'currently_accredited' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'name_changed' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'additional_comments' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'organization_mission_statement' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'pom_currently_in_use' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'information_gathering_process' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'how_information_is_used' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'certified_pom_staff' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'section_3_comments' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		)
	);

	public $step4Validation = array(
	);

	public $step5Validation = array(
		'organization_name_compliance' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'certification_name' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'certification_date' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'certification_signature' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'certification_checkbox' => array(
			'rule' => array('comparison', '!=', 0),
			'required' => true,
			'message' => 'This field is required'
		)
	);

	public $step6Validation = array(
	);

	public $step7Validation = array(
		'nearest_airport' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'airport_distance' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'travel_hotel_1' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'travel_city_1' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'travel_phone_1' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'travel_distance_1' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		)
	);

	public $step8Validation = array(
		'agreement_organization_name' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'agreement_date' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'agreement_signature' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		),
		'agreement_checkbox' => array(
			'rule' => array('comparison', '!=', 0),
			'required' => true,
			'message' => 'This field is required'
		)
	);

	public $step9Validation = array(
		'checklist_1' => array(
			'rule' => array('comparison', '!=', 0),
			'required' => true,
			'message' => 'This field is required'
		),
		'checklist_2' => array(
			'rule' => array('comparison', '!=', 0),
			'required' => true,
			'message' => 'This field is required'
		),
		'engagement_fee' => array(
			'rule' => 'notEmpty',
			'message' => 'This field is required'
		)
	);

}

?>