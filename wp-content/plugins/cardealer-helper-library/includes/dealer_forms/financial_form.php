<?php
/*
 * This function use to store new inquiries of test drive.
 */
add_action('wp_ajax_financial_form_action','cdhl_financial_form_action');
add_action('wp_ajax_nopriv_financial_form_action','cdhl_financial_form_action');
function cdhl_financial_form_action() {
    global $car_dealer_options;
    $responseArray = array();
	if(isset($_POST['action']) && $_POST['action'] == 'financial_form_action') {	
		$captcha=$_POST['g-recaptcha-response'];
        $recaptcha_response=cardealer_validate_google_captch($captcha);
		$first_name   		    = sanitize_text_field($_POST['first_name']);
		$middle_initial			= sanitize_text_field($_POST['middle_initial']);
		$last_name    			= sanitize_text_field($_POST['last_name']);
		$street_address    		= sanitize_text_field($_POST['street_address']);
		$city     				= sanitize_text_field($_POST['city']);
		$zip     				= sanitize_text_field($_POST['zip']);
		$preferred_email_address= sanitize_text_field($_POST['preferred_email_address']);
		$daytime_phone_number   = sanitize_text_field($_POST['daytime_phone_number']);
		$mobile_phone_number    = sanitize_text_field($_POST['mobile_phone_number']);
		$date_of_birth_month	= sanitize_text_field($_POST['date_of_birth_month']);
		$date_of_birth_day		= sanitize_text_field($_POST['date_of_birth_day']);
		$date_of_birth_year		= sanitize_text_field($_POST['date_of_birth_year']);
		$social_security_number = sanitize_text_field($_POST['social_security_number']);
		$employer_name          = sanitize_text_field($_POST['employer_name']);
		$employer_phone         = sanitize_text_field($_POST['employer_phone']);		
		$job_title    		    = sanitize_text_field($_POST['job_title']);
		$annual_income          = sanitize_text_field($_POST['annual_income']);
		$living_arrangements    = sanitize_text_field($_POST['living_arrangements']);		
		$length_of_time_at_current_add_year     = sanitize_text_field($_POST['length_of_time_at_current_add_year']);
		$length_of_time_at_current_add_month    = sanitize_text_field($_POST['length_of_time_at_current_add_month']);		
		$length_of_employment_year    			= sanitize_text_field($_POST['length_of_employment_year']);
		$length_of_employment_month   			= sanitize_text_field($_POST['length_of_employment_month']);
		$other_income_ammount_monthly   		= sanitize_text_field($_POST['other_income_ammount_monthly']);
		$other_income_source   					= sanitize_text_field($_POST['other_income_source']);
		$additional_information  			    = sanitize_text_field($_POST['additional_information']);
		$joint_application    					= sanitize_text_field($_POST['joint_application']);		
		$monthly_rent          					= sanitize_text_field($_POST['monthly_rent']);
		$car_id 		  						= sanitize_text_field($_POST['car_id']);
		
        if(isset($_POST['joint_application']) && $_POST['joint_application'] == 'on')
		{
			$joint_first_name    					 = sanitize_text_field($_POST['first_name_joint']);
			$joint_middle_initial					 = sanitize_text_field($_POST['middle_initial_joint']);
			$joint_last_name     					 = sanitize_text_field($_POST['last_name_joint']);
			$joint_relationship_to_applicant		 = sanitize_text_field($_POST['relationship_to_applicant']);
			$joint_street_address					 = sanitize_text_field($_POST['street_address_joint']);
			$joint_city          					 = sanitize_text_field($_POST['city_joint']);
			$joint_state		  					 = sanitize_text_field($_POST['state_joint']);
			$joint_zip          					 = sanitize_text_field($_POST['zip_joint']);
			$joint_preferred_email_address    		 = sanitize_text_field($_POST['preferred_email_address_joint']);
			$joint_daytime_phone_number     		 = sanitize_text_field($_POST['daytime_phone_number_joint']);
			$joint_mobile_phone_number    			 = sanitize_text_field($_POST['mobile_phone_number_joint']);
			$joint_date_of_birth_month				 = sanitize_text_field($_POST['date_of_birth_month_joint']);
			$joint_date_of_birth_day				 = sanitize_text_field($_POST['date_of_birth_day_joint']);
			$joint_date_of_birth_year				 = sanitize_text_field($_POST['date_of_birth_year_joint']);			 
			$joint_social_security_number   		 = sanitize_text_field($_POST['social_security_number_joint']);			 
			$joint_employer_name    				 = sanitize_text_field($_POST['employer_name_joint']);
			$joint_employer_phone    				 = sanitize_text_field($_POST['employer_phone_joint']);		
			$joint_job_title    					 = sanitize_text_field($_POST['job_title_joint']);
			$joint_length_of_employment_year		 = sanitize_text_field($_POST['length_of_employment_year_joint']);
			$joint_length_of_employment_month		 = sanitize_text_field($_POST['length_of_employment_month_joint']);
			$joint_annual_income    				 = sanitize_text_field($_POST['annual_income_joint']);
			$joint_living_arrangements				 = sanitize_text_field($_POST['living_arrangements_joint']);
			$joint_monthly_rent    					 = sanitize_text_field($_POST['monthly_rent_joint']);
			$joint_length_of_time_at_current_add_year= sanitize_text_field($_POST['length_of_time_at_current_add_year_joint']);
			$joint_other_income_ammount_monthly		 = sanitize_text_field($_POST['other_income_ammount_monthly_joint']);
			$joint_other_income_source				 = sanitize_text_field($_POST['other_income_source_joint']);
			$joint_additional_information			 = sanitize_text_field($_POST['additional_information_joint']);
		}
		if (!isset($_POST['financial_nonce'])||!wp_verify_nonce($_POST['financial_nonce'],'financial_form')){
			$errors[] =  esc_html__("Sorry, your nonce did not verify.Refresh Page and try again.", "cardealer-helper");
        } elseif($recaptcha_response['success'] == false){                
            $errors[] = esc_html__("Please check the the captcha form", "cardealer-helper");
		} else {
			$mail_set = 0;
			$request_date = (isset($recaptcha_response['challenge_ts']) && !empty($recaptcha_response['challenge_ts']))? $recaptcha_response['challenge_ts'] : '';
			$post_id = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'financial_inquiry',
				'post_title' => $first_name. ' ' .$last_name,
				'post_content' => ''
			) );
			
			if($post_id) {
				if( isset($car_id) && !empty($car_id) ){
					$caryear = wp_get_post_terms($car_id, 'car_year');
					$car_year = (isset($caryear))? $caryear[0]->name : '';
					$carmake = wp_get_post_terms($car_id, 'car_make');
					$car_make = (isset($carmake))? $carmake[0]->name : '';
					$carmodel = wp_get_post_terms($car_id, 'car_model');
					$car_model = (isset($carmodel))? $carmodel[0]->name : '';
					$cartrim = wp_get_post_terms($car_id, 'car_trim');
					$car_trim = (isset($cartrim))? $cartrim[0]->name : '';
					$carvin = wp_get_post_terms($car_id, 'car_vin_number');
					$car_vin = (isset($carvin))? $carvin[0]->name : '';
					$carstock = wp_get_post_terms($car_id, 'car_stock_number');
					$car_stock = (isset($carstock))? $carstock[0]->name : '';
					$regularprice = get_post_meta($car_id, 'regular_price',true);
					$car_regular_price = (isset($regularprice))? $regularprice : '';
					$saleprice = get_post_meta($car_id, 'sale_price',true);
					$car_sale_price = (isset($saleprice))? $saleprice : '';
							   
					update_field( 'car_id', $car_id, $post_id );
					update_field( 'car_year_inq', $car_year, $post_id );
					update_field( 'car_make_inq', $car_make, $post_id );
					update_field( 'car_model_inq', $car_model, $post_id );
					update_field( 'car_trim_inq', $car_trim, $post_id );
					update_field( 'vin_number', $car_vin, $post_id );
					update_field( 'stock_number', $car_stock, $post_id );
					update_field( 'regular_price', $car_regular_price, $post_id );
					update_field( 'sale_price', $car_sale_price, $post_id );
				}
				update_field( 'first_name', $first_name, $post_id );
				update_field( 'middle_initial', $middle_initial, $post_id );
				update_field( 'last_name', $last_name, $post_id );
				update_field( 'street_address', $street_address, $post_id );
				update_field( 'city', $city, $post_id );
				update_field( 'state', $_POST['state'], $post_id );
				update_field( 'zip', $zip, $post_id );
				update_field( 'preferred_email_address', $preferred_email_address, $post_id );
				update_field( 'daytime_phone_number', $daytime_phone_number, $post_id );
				update_field( 'mobile_phone_number', $mobile_phone_number, $post_id );
				update_field( 'date_of_birth', $date_of_birth_month."-".$date_of_birth_day."-".$date_of_birth_year, $post_id );
				update_field( 'living_arrangements', $living_arrangements, $post_id );
				update_field( 'ssn', $social_security_number, $post_id );
				update_field( 'employer_name', $employer_name, $post_id );
				update_field( 'monthly_rent_payment', $monthly_rent, $post_id );
				update_field( 'employer_phone', $employer_phone, $post_id );
				update_field( 'job_title', $job_title, $post_id );
				update_field( 'len_time_at_curr_addr', $length_of_time_at_current_add_year." ".$length_of_time_at_current_add_month, $post_id );
				update_field( 'len_of_employment',$length_of_employment_year." ".$length_of_employment_month, $post_id );
				update_field( 'annual_income', $annual_income, $post_id );	
				update_field( 'other_income_amt', $other_income_ammount_monthly, $post_id );	
				update_field( 'other_income_source', $other_income_source, $post_id );
				update_field( 'additional_info', $additional_information, $post_id );				
				update_field( 'joint_application', $joint_application, $post_id );	
				
				update_field( 'joint_first_name', $joint_first_name, $post_id );
				update_field( 'joint_middle_name', $joint_middle_initial, $post_id );
				update_field( 'joint_last_name', $joint_last_name, $post_id );
				update_field( 'joint_relationship_to_applicant', $joint_relationship_to_applicant, $post_id );
				update_field( 'joint_street_address', $joint_street_address, $post_id );
				update_field( 'joint_city', $joint_city, $post_id );
				update_field( 'joint_state', $joint_state, $post_id );
				update_field( 'joint_zip', $joint_zip, $post_id );
				update_field( 'joint_preferred_email_address', $joint_preferred_email_address, $post_id );
				update_field( 'joint_day_time_phone_no', $joint_daytime_phone_number, $post_id );
				update_field( 'joint_mobile_phone_number', $joint_mobile_phone_number, $post_id );
				update_field( 'joint_date_of_birth', $joint_date_of_birth_month."-".$joint_date_of_birth_day."-".$joint_date_of_birth_year, $post_id );
				update_field( 'joint_living_arrangements', $joint_living_arrangements, $post_id );
				update_field( 'joint_ssn', $joint_social_security_number, $post_id );
				update_field( 'joint_employer_name', $joint_employer_name, $post_id );
				update_field( 'joint_monthly_rent_payment', $joint_monthly_rent, $post_id );
				update_field( 'joint_employer_phone', $joint_employer_phone, $post_id );
				update_field( 'joint_job_title', $joint_job_title, $post_id );
				update_field( 'joint_len_time_at_curr_addr', $joint_length_of_time_at_current_add_year." ".$_POST['length_of_time_at_current_add_month_joint'], $post_id );				
				update_field( 'joint_len_of_employment',$joint_length_of_employment_year." ".$joint_length_of_employment_month, $post_id );				
				update_field( 'joint_annual_income', $joint_annual_income, $post_id );
				update_field( 'joint_other_income_amt', $joint_other_income_ammount_monthly, $post_id );
				update_field( 'joint_other_income', $joint_other_income_source, $post_id );
				update_field( 'joint_additional_info', $joint_additional_information, $post_id );
				
				$fromName = empty($car_dealer_options['financial_form_from_name'])? 'no-reply@'.$_SERVER['HTTP_HOST'] : $car_dealer_options['financial_form_from_name'];
				$fromMail = empty($car_dealer_options['financial_form_mail_id_from'])? 'no-reply@'.$_SERVER['HTTP_HOST'] : $car_dealer_options['financial_form_mail_id_from']; 
				$replyToMail = empty($_POST['preferred_email_address'])? 'no-reply@'.$_SERVER['HTTP_HOST'] : $_POST['preferred_email_address'];
				$replyToName = empty($first_name)? 'User' : $first_name;
				$subject = empty($car_dealer_options['financial_form_subject'])? esc_html__('Test Drive Inquiry Mail', 'cardealer-helper') : $car_dealer_options['financial_form_subject'];
					
				$headers = "From: " . strip_tags($fromName) . "<".$fromMail.">\r\n";
				$headers .= "Reply-To: ". strip_tags($replyToName) . "<".$replyToMail.">\r\n";				
				$mail_error =0;
				
				// PREPARE PRODUCT DETAIL FOR MAIL
				$adf_data = $plain_mail = $product ="";
				if(isset($car_id) && !empty($car_id)){
					$plain_mail = cdhl_get_text_mail_body($car_id);
					$product = cdhl_get_html_mail_body($car_id);
					// SET MAIL BODY FOR ADF MAIL
						$adf_data .='<?xml version="1.0"?>' . PHP_EOL;
							$adf_data .= '<?ADF VERSION="1.0"?>' . PHP_EOL;
							$adf_data .= '<adf>' . PHP_EOL;
							  $adf_data .= '<prospect>' . PHP_EOL;
								$adf_data .= '<requestdate>'. $request_date .'</requestdate>' . PHP_EOL;
							   $adf_data .= '<vehicle>' . PHP_EOL;
								   $adf_data .= '<year>'.$car_year.'</year>' . PHP_EOL;
								   $adf_data .= '<make>'.$car_make.'</make>' . PHP_EOL;
								   $adf_data .= '<model>'.$car_model.'</model>' . PHP_EOL;
								   $adf_data .= '<vin>'.$car_vin.'</vin>' . PHP_EOL;
								   $adf_data .= '<stock>'.$car_stock.'</stock>' . PHP_EOL;
								   $adf_data .= '<trim>'.$car_trim.'</trim>' . PHP_EOL;
									  $adf_data .= '</vehicle>' . PHP_EOL;
										$adf_data .= '<customer>' . PHP_EOL;
											$adf_data .= '<contact>' . PHP_EOL;
												$adf_data .= '<name part="full">'.$first_name.' '.$middle_initial.' '.$last_name.'</name>' . PHP_EOL;
												$adf_data .= '<email>'.$_POST['preferred_email_address'].'</email>' . PHP_EOL;
												$adf_data .= '<phone type="voice" time="day">'.$mobile_phone_number.'</phone>' . PHP_EOL;
												$adf_data .= '<address>' . PHP_EOL;
													  $adf_data .= '<street line="1">'.$street_address.'</street>' . PHP_EOL;												  
													  $adf_data .= '<city>'.$city.'</city>'.PHP_EOL;
													  $adf_data .= '<postalcode>'.$zip.'</postalcode>' . PHP_EOL;
												$adf_data .= '</address>' . PHP_EOL;
											 $adf_data .= '</contact>' . PHP_EOL;
											 $adf_data .= '<comments>' . PHP_EOL;
											
											// ADD FORM DATA INTO COMMENT
											$customer_detail= esc_html__('Financial Inquiry Information : ', 'cardealer-helper') . PHP_EOL . PHP_EOL;								
											$customer_detail .= esc_html__('Financial Information :', 'cardealer-helper') . PHP_EOL . PHP_EOL;									$customer_detail .= esc_html__('First Name :', 'cardealer-helper').$first_name . PHP_EOL;
											$customer_detail .= esc_html__('Middle Initial :', 'cardealer-helper').$middle_initial . PHP_EOL;
											$customer_detail .= esc_html__('Last Name :', 'cardealer-helper').$last_name . PHP_EOL;
											$customer_detail .= esc_html__('Street Address :', 'cardealer-helper').$street_address . PHP_EOL;
											$customer_detail .= esc_html__('City :', 'cardealer-helper').$city . PHP_EOL;
											$customer_detail .= esc_html__('State :', 'cardealer-helper').$_POST['state'] . PHP_EOL;
											$customer_detail .= esc_html__('Zip :', 'cardealer-helper').$zip . PHP_EOL;
											$customer_detail .= esc_html__('Preferred Email Address :', 'cardealer-helper').$preferred_email_address . PHP_EOL;
											$customer_detail .= esc_html__('Daytime Phone Number :', 'cardealer-helper').$daytime_phone_number . PHP_EOL;
											$customer_detail .= esc_html__('Mobile Phone Number :', 'cardealer-helper').$mobile_phone_number. PHP_EOL;
											$customer_detail .= esc_html__('Date of Birth :', 'cardealer-helper').$date_of_birth_month."-".$date_of_birth_day."-".$date_of_birth_year . PHP_EOL;
											$customer_detail .= esc_html__('Living Arrangements :', 'cardealer-helper').$living_arrangements . PHP_EOL;
											$customer_detail .= esc_html__('Social Security Number (SSN) :', 'cardealer-helper').$social_security_number . PHP_EOL;
											$customer_detail .= esc_html__('Employer Name :', 'cardealer-helper').$employer_name . PHP_EOL;								
											$customer_detail .= esc_html__('Monthly Rent/Mortgage Payment :', 'cardealer-helper').$monthly_rent . PHP_EOL;
											$customer_detail .= esc_html__('Employer Phone :', 'cardealer-helper').$employer_phone . PHP_EOL;
											$customer_detail .= esc_html__('Job Title :', 'cardealer-helper').$job_title . PHP_EOL;
											$customer_detail .= esc_html__('Length of Time at Current Address :', 'cardealer-helper').$length_of_time_at_current_add_year.":".$length_of_time_at_current_add_month . PHP_EOL;
											$customer_detail .= esc_html__('Length of Employment :', 'cardealer-helper').$length_of_employment_year.":".$length_of_employment_month . PHP_EOL;
											$customer_detail .= esc_html__('Annual Income :', 'cardealer-helper').$annual_income . PHP_EOL;
											$customer_detail .= esc_html__('Other Income Amount(Monthly) :', 'cardealer-helper').$other_income_ammount_monthly . PHP_EOL;
											$customer_detail .= esc_html__('Other Income Source :', 'cardealer-helper').$other_income_source . PHP_EOL;
											$customer_detail .= esc_html__('Additional Information :', 'cardealer-helper').$additional_information . PHP_EOL . PHP_EOL . PHP_EOL;
											
											// ADD JOINT APPLICATION DETAIL ONLY IF JOINT APPLICATION IS CHECKED INSIDE FINANCIAL FORM
											if(isset($_POST['joint_application']) && $_POST['joint_application'] == 'on')
											{	
												$customer_detail .= esc_html__('Joint Application Detail :', 'cardealer-helper') . PHP_EOL . PHP_EOL;							
												$customer_detail .= esc_html__('First Name :', 'cardealer-helper').$joint_first_name . PHP_EOL;
												$customer_detail .= esc_html__('Middle Initial :', 'cardealer-helper').$joint_middle_initial . PHP_EOL;
												$customer_detail .= esc_html__('Last Name :', 'cardealer-helper').$_POST['last_name_joint'] . PHP_EOL;
												$customer_detail .= esc_html__('Relationship To Applicant :', 'cardealer-helper').$_POST['relationship_to_applicant'] . PHP_EOL;
												$customer_detail .= esc_html__('Street-Address :', 'cardealer-helper').$joint_street_address . PHP_EOL;
												$customer_detail .= esc_html__('City :', 'cardealer-helper').$joint_city . PHP_EOL;
												$customer_detail .= esc_html__('State :', 'cardealer-helper').$joint_state . PHP_EOL;
												$customer_detail .= esc_html__('Zip :', 'cardealer-helper').$joint_zip . PHP_EOL;
												$customer_detail .= esc_html__('Preferred Email Address :', 'cardealer-helper').$joint_preferred_email_address . PHP_EOL;
												$customer_detail .= esc_html__('Daytime Phone Number :', 'cardealer-helper').$joint_daytime_phone_number . PHP_EOL;
												$customer_detail .= esc_html__('Mobile Phone Number :', 'cardealer-helper').$joint_mobile_phone_number . PHP_EOL;
												$customer_detail .= esc_html__('Date of Birth :', 'cardealer-helper').$$joint_date_of_birth_month."-".$joint_date_of_birth_day."-".$joint_date_of_birth_year . PHP_EOL;												
												$customer_detail .= esc_html__('Social Security Number (SSN) :', 'cardealer-helper').$joint_social_security_number . PHP_EOL;
												$customer_detail .= esc_html__('Employer Name :', 'cardealer-helper').$joint_employer_name . PHP_EOL;				
												$customer_detail .= esc_html__('Employer Phone No:', 'cardealer-helper').$joint_employer_phone . PHP_EOL;
												$customer_detail .= esc_html__('Job Title :', 'cardealer-helper').$joint_job_title . PHP_EOL;
												$customer_detail .= esc_html__('Length of Employment :', 'cardealer-helper').$joint_length_of_employment_year.":".$joint_length_of_employment_month . PHP_EOL;
												$customer_detail .= esc_html__('Length of Time at Current Address :', 'cardealer-helper').$joint_length_of_time_at_current_add_year.":".$_POST['length_of_time_at_current_add_month_joint'] . PHP_EOL;
												$customer_detail .= esc_html__('Annual Income :', 'cardealer-helper').$joint_annual_income . PHP_EOL;
												$customer_detail .= esc_html__('Living Arrangements :', 'cardealer-helper').$joint_living_arrangements . PHP_EOL;
												$customer_detail .= esc_html__('Other Income Amount(Monthly) :', 'cardealer-helper').$joint_other_income_ammount_monthly . PHP_EOL;
												$customer_detail .= esc_html__('Other Income Source :', 'cardealer-helper').$joint_other_income_source . PHP_EOL;
												$customer_detail .= esc_html__('Additional Information :', 'cardealer-helper').$joint_additional_information . PHP_EOL;
											}
											$adf_data .= $customer_detail . PHP_EOL;
											$adf_data .= '</comments>' . PHP_EOL;
										 $adf_data .= '</customer>' . PHP_EOL;
										 $adf_data .= '<vendor>' . PHP_EOL;
											$adf_data .= '<contact>' . PHP_EOL;
												 $adf_data .= '<name part="full">'. $car_dealer_options['financial_form_from_name'] .'</name>' . PHP_EOL;
											$adf_data .= '</contact>' . PHP_EOL;
										 $adf_data .= '</vendor>' . PHP_EOL;
									  $adf_data .= '</prospect>' . PHP_EOL;
									$adf_data .= '</adf>' . PHP_EOL;
				}

				// Sending ADF Mail
					if( isset($car_dealer_options['financial_form_adf_mail_to']) && !empty($car_dealer_options['financial_form_adf_mail_to']) ) {
						$mail_set = 1;
						$to = $car_dealer_options['financial_form_adf_mail_to'];
						// Mail body
						$adf_headers = $headers;	
						$adf_headers .= "MIME-Version: 1.0\r\n";
						$adf_headers .= "content-type: text/plain; charset=UTF-8\r\n";	
						if( !wp_mail( $to, $subject, $adf_data, $adf_headers) ) $mail_error = 1;
					}
					
					
				// Mail body
				$fields = array(
					'CD_FROM_NAME' 	=> $car_dealer_options['financial_form_from_name'], 
					'CD_FIRST_NAME' => $first_name,
					'CD_MIDDLE_INIT' => $middle_initial,
					'CD_LAST_NAME' 	=> $last_name,
					'CD_STREET_ADD' => $street_address,
					'CD_CITY'		=> $city,
					'CD_STATE'		=> $_POST['state'],
					'CD_ZIP'		=> $zip,
					'CD_PREF_EMAIL_ADD' => $preferred_email_address,
					'CD_DAYTIME_PHONE_NO' => $daytime_phone_number,
					'CD_MOBILE_PHONE_NO' => $mobile_phone_number,
					'CD_DATE_OF_BIRTH' => $date_of_birth_month."-".$date_of_birth_day."-".$date_of_birth_year,
					'CD_LIVING_ARRANG' => $living_arrangements,
					'CD_SSN'		=> $social_security_number,
					'CD_EMPLOYER_NAME' => $employer_name,
					'CD_MONTHLY_RENT' => $monthly_rent,
					'CD_EMPLOYER_PHONE' => $employer_phone,
					'CD_JOB_TITLE'	=> $job_title,
					'CD_LEN_OF_TIME_AT_CUR_ADD' => $length_of_time_at_current_add_year.":".$length_of_time_at_current_add_month,
					'CD_LENGTH_OF_EMP' => $length_of_employment_year.":".$length_of_employment_month,
					'CD_ANNUAL_INCOME' => $annual_income,
					'CD_OTHER_INC_AMT_MONTHLY' => $other_income_ammount_monthly,
					'CD_OTHER_INCOME_SOURCE' => $other_income_source,
					'CD_ADD_INFO' => $additional_information,
					'CD_JOINT_FIRST_NAME'=> $joint_first_name,
					'CD_JOINT_MIDDLE_INIT'=> $joint_middle_initial,
					'CD_JOINT_LAST_NAME'=> $joint_last_name,
					'CD_JOINT_REL_TO_APPLICANT'=>$_POST['relationship_to_applicant'],
					'CD_JOINT_STREET_ADD'=>$joint_street_address,
					'CD_JOINT_CITY'=>$joint_city,
					'CD_JOINT_STATE'=>$joint_state,
					'CD_JOINT_ZIP'=>$joint_zip,
					'CD_JOINT_PREFERRED_EMAIL_ADD'=>$joint_preferred_email_address,
					'CD_JOINT_DAYTIME_PHONE_NO'=>$joint_daytime_phone_number,
					'CD_JOINT_MOBILE_PHONE_NO'=>$joint_mobile_phone_number,
					'CD_JOINT_DATE_OF_BIRTH'=>$joint_date_of_birth_month."-".$joint_date_of_birth_day."-".$joint_date_of_birth_year,
					'CD_JOINT_SSN'=>$joint_social_security_number,
					'CD_JOINT_EMP_NAME'=>$joint_employer_name,
					'CD_JOINT_EMP_PHONE'=>$joint_employer_phone,
					'CD_JOINT_JOB_TITLE'=>$joint_job_title,
					'CD_JOINT_LENGTH_OF_EMP'=>$joint_length_of_employment_year.":".$joint_length_of_employment_month,				
					'CD_JOINT_ANNUAL_INCOME'=>$joint_annual_income,
					'CD_JOINT_LIVING_ARRANG'=>$joint_living_arrangements,
					'CD_JOINT_MONTHLY_RENT'=>$joint_monthly_rent,
					'CD_JOINT_LENGTH_OF_TIME'=>$joint_length_of_time_at_current_add_year.":".$_POST['length_of_time_at_current_add_month_joint'],
					'CD_JOINT_OTHER_INC_AMT_MONTHLY'=>$joint_other_income_ammount_monthly,
					'CD_JOINT_OTHER_INC_SOURCE'=>$joint_other_income_source,
					'CD_JOINT_ADD_INFO'=>$joint_additional_information
				);
				
				// Sending HTML Mail
					if( isset($car_dealer_options['financial_form_html_mail_to']) && !empty($car_dealer_options['financial_form_html_mail_to']) ) {
						$mail_set = 1;
						$to = $car_dealer_options['financial_form_html_mail_to'];
						// Mail body
						if( isset($car_dealer_options['financial_form_html_body']) && !empty($car_dealer_options['financial_form_html_body'])){
							$financial_form_html_body = $car_dealer_options['financial_form_html_body'];
							$fields['PRODUCT_DETAIL'] = $product;
							foreach( $fields as $tag => $value ) {
								if( strpos($financial_form_html_body, '#'.$tag.'#') !== false )
									$financial_form_html_body = str_replace( '#'.$tag.'#', $value, $financial_form_html_body );
							}
						}
						else
							$financial_form_html_body = esc_html__('One Inquiry Received', 'cardealer-helper');
						$html_headers = $headers;
						$html_headers .= "MIME-Version: 1.0\r\n";	
						$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
						if( !wp_mail( $to, $subject, $financial_form_html_body, $html_headers) ) $mail_error = 1;
					}
					
				// Sending Text Mail
					if( isset($car_dealer_options['financial_form_text_mail_to']) && !empty($car_dealer_options['financial_form_text_mail_to']) ) { 
					$mail_set = 1;
						$to = $car_dealer_options['financial_form_text_mail_to'];
						// Mail body
						if( isset($car_dealer_options['financial_form_text_body']) && !empty($car_dealer_options['financial_form_text_body'])){
							$financial_form_text_body = $car_dealer_options['financial_form_text_body'];
							
							$fields['PRODUCT_DETAIL'] = $plain_mail;
							foreach( $fields as $tag => $value ) {
								if( strpos($financial_form_text_body, '#'.$tag.'#') !== false )
									$financial_form_text_body = str_replace( '#'.$tag.'#', $value, $financial_form_text_body );
							}
						}
						else
							$financial_form_text_body = esc_html__('One Inquiry Received', 'cardealer-helper');
						
						$text_headers = $headers;	
						$text_headers .= "MIME-Version: 1.0\r\n";
						$text_headers .= "content-type: text/plain; charset=UTF-8\r\n";	
				
						if( !wp_mail( $to, $subject, $financial_form_text_body, $text_headers) ) $mail_error = 1;
					}
					// If not mail is set from admin form options then mail will sent to admin
					if( $mail_set == 0 ) {
						$html_headers = $headers;
						$html_headers .= "MIME-Version: 1.0\r\n";	
						$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
						$to = get_option('admin_email');
						// Mail body
						if( isset($car_dealer_options['financial_form_html_body']) && !empty($car_dealer_options['financial_form_html_body'])){
							$financial_form_html_body = $car_dealer_options['financial_form_html_body'];
							$fields['PRODUCT_DETAIL'] = $product;
							
							foreach( $fields as $tag => $value ) {
								if( strpos($financial_form_html_body, '#'.$tag.'#') !== false )
									$financial_form_html_body = str_replace( '#'.$tag.'#', $value, $financial_form_html_body );
							}
						}
						else
							$financial_form_text_body = esc_html__('One Inquiry Received', 'cardealer-helper');
						if( !wp_mail( $to, $subject, $financial_form_text_body,$html_headers) ) $mail_error = 1;
					}
					
					if( $mail_error == 1 ) {
						$errors[] = esc_html__("Sorry there was an error sending your message. Please try again later.", "cardealer-helper");
					} else {
						$responseArray = array(
							'status' => esc_html__("1", "cardealer-helper"),
							'msg' => '<div class="alert alert-success">'.esc_html__("Request sent successfully", "cardealer-helper").'</div>'
						);
					}
				}
			} 
            
            if(!empty($errors)){
            $err="";
				foreach($errors as $error){
					$err.="<p>".$error.'</p>';        
				}
				$responseArray = array(            				
					"status"  => "error",
					"msg" => '<div class="alert alert-danger">'.$err.'</div>'             			
				);
			}
	}
	echo wp_json_encode($responseArray);
	die;
}
?>