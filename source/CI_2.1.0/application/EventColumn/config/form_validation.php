<?php
	/*
	 * This is the config file for all form validation on EventColumn
	 */
	$config = array(
		'add_event'	 => array(
			array(
				'field'	 => 'event_name',
				'label'	 => 'Event Name',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'start_date',
				'label'	 => 'Start Date',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'end_date',
				'label'	 => 'End Date',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_location_name]',
				'label'	 => 'Location',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][lat_long]',
				'label'	 => 'Coordinates',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_address]',
				'label'	 => 'Address',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_city]',
				'label'	 => 'City',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_state]',
				'label'	 => 'State',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][event_zip]',
				'label'	 => 'Zipcode',
				'rules'	 => 'required|numeric|exact_length[5]'
			),
			array(
				'field'	 => 'event_details_locations[0][event_country]',
				'label'	 => 'Country',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'event_details_locations[0][smoking]',
				'label'	 => 'Smoking',
				'rules'	 => ''
			),
			array(
				'field'	 => 'event_details_locations[0][food]',
				'label'	 => 'Food',
				'rules'	 => ''
			),
            array(
				'field'	 => 'event_details_locations[0][event_cost]',
				'label'	 => 'Admission',
				'rules'	 => ''
			),
			array(
				'field'	 => 'event_details_locations[0][age]',
				'label'	 => 'Age Range',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'description',
				'label'	 => 'Details',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'category',
				'label'	 => 'Category',
				'rules'	 => 'required'
			)
		),
		'add_user'	 => array(
			array(
				'field'	 => 'username',
				'label'	 => 'Username',
				'rules'	 => 'required|min_length[8]|max_length[32]|alpha_dash|is_unique[USERS.username]'
			),
			array(
				'field'	 => 'email',
				'label'	 => 'Email',
				'rules'	 => 'required|valid_email|is_unique[USERS.email]'
			),
//			array(
//				'field'	 => 'confirm_email',
//				'label'	 => 'Confirm Email',
//				'rules'	 => 'required|valid_email|matches[email]'
//			),
			array(
				'field'	 => 'password',
				'label'	 => 'Password',
				'rules'	 => 'required|min_length[8]|max_length[32]|callback_validate_password'//using a callback to validate
			),
//			array(
//				'field'	 => 'confirm_password',
//				'label'	 => 'Confirm Password',
//				'rules'	 => 'required|min_length[8]|max_length[32]|matches[password]'
//			),
//			array(
//				'field'	 => 'zip',
//				'label'	 => 'Zip',
//				'rules'	 => 'required|exact_length[5]|numeric'
//			),
			array(
				'field'	 => 'agree_to_terms_and_policies',
				'label'	 => 'Agree To Terms and Policies',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'recaptcha_response_field',
				'label'	 => 'Captcha',
				'rules'	 => 'callback_validate_captcha'
			)
		),
		'contact_us' => array (
			array(
				'field'	 => 'username',
				'label'	 => 'Username',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'email',
				'label'	 => 'Email',
				'rules'	 => 'required|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => 'Subject',
				'rules' => 'required'
			),
			array(
				'field' => 'email_text',
				'label' => 'Email Text',
				'rules' => 'required|max_length[1000]'
			),
			array(
				'field'	 => 'recaptcha_response_field',
				'label'	 => 'Captcha',
				'rules'	 => 'callback_validate_captcha'
			)
		),
		'mini_search' => array(
			array(
				'field'	 => 'mini_search_zip',
				'label'	 => 'Zip',
				'rules'	 => 'required|exact_length[5]|numeric'
			)
		),
		'forgot_password' => array(
			array(
				'field'	 => 'email',
				'label'	 => 'Email',
				'rules'	 => 'required|valid_email|callback_emailExists'
			)
		),
		'login'	 => array(
			array(
				'field'	 => 'login_username',
				'label'	 => 'Username',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'login_password',
				'label'	 => 'Password',
				'rules'	 => 'required'
			)
		),
		'advanced_search' => array(
			array(
				'field' => 'event_title',
				'label' => 'Event Title',
				'rules' => ''
			),
			array(
				'field' => 'city',
				'label' => 'City',
				'rules' => 'alpha'
			),
			array(
				'field' => 'state',
				'label' => 'State',
				'rules' => 'exact_length[2]'
			),
			array(
				'field' => 'zip',
				'label' => 'Zip',
				'rules' => 'numeric|exact_length[5]'
			),
			array(
				'field' => 'from_date',
				'label' => 'From Date',
				'rules' => ''
			),
			array(
				'field' => 'to_date',
				'label' => 'To Date',
				'rules' => ''
			)
		),
		'update_profile'	 => array(
			array(
				'field'	 => 'email',
				'label'	 => 'Email',
				'rules'	 => 'valid_email|is_unique[USERS.email]'
			),
			array(
				'field'	 => 'current_password',
				'label'	 => 'Current Password',
				'rules'	 => 'required'
			),
			array(
				'field'	 => 'new_password',
				'label'	 => 'New Password',
				'rules'	 => 'min_length[8]|max_length[32]|callback_validate_password'//using a callback to validate
			),
			array(
				'field'	 => 'confirm_new_password',
				'label'	 => 'Confirm New Password',
				'rules'	 => 'min_length[8]|max_length[32]|matches[new_password]'
			),
			array(
				'field'	 => 'zip',
				'label'	 => 'Zip',
				'rules'	 => 'exact_length[5]|numeric'
			),
		)
	);
?>
