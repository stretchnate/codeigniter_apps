<?php
	/*
	 * This is the config file for all form validation on ceq
	 */
	$config = array(
		'add_report'	 => array(
			array(
				'field'	 => 'home_teacher',
				'label'	 => 'Home Teacher',
				'rules'	 => 'required|callback_notEqualTo[family]'
			),
			array(
				'field'	 => 'family',
				'label'	 => 'Family',
				'rules'	 => 'required|callback_notEqualTo[home_teacher]'
			),
            array(
                'field'  => 'date_of_visit',
                'label'  => 'Date Of Visit',
                'rules'  => 'required'
            ),
			array(
				'field'	 => 'assessment',
				'label'	 => 'Visit/Contact Type',
				'rules'	 => 'required'
			),
            array(
				'field'	 => 'recaptcha_response_field',
				'label'	 => 'Captcha',
				'rules'	 => 'callback_validateCaptcha'
			)
		)
	);
?>
