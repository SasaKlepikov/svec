<?php
/*
 * This function use to store new inquiries of test drive.
 */
add_action('wp_ajax_shedule_test_drive_action','cdhl_shedule_test_drive_action');
add_action('wp_ajax_nopriv_shedule_test_drive_action','cdhl_shedule_test_drive_action');
function cdhl_shedule_test_drive_action() { 
    global $car_dealer_options; 
    $responseArray = array();
    $responseArray = array(            				
		"status"  => "error",
        "msg" => '<div class="alert alert-danger">Something went wrong!</div>'            			
	);
	if(isset($_POST['action']) && $_POST['action'] == 'shedule_test_drive_action') {	
		
        $captcha=$_POST['g-recaptcha-response'];
        $recaptcha_response=cardealer_validate_google_captch($captcha);
		$first_name		  = sanitize_text_field($_POST['first_name']);
		$last_name	 	  = sanitize_text_field($_POST['last_name']);
		$state   	 	  = sanitize_text_field($_POST['state']);
		$zip	    	  = sanitize_text_field($_POST['zip']);
		$preferred_contact= sanitize_text_field($_POST['preferred_contact']);
		$email		 	  = sanitize_text_field($_POST['email']);
		$mobile		 	  = sanitize_text_field($_POST['mobile']);
		$test_drive	 	  = sanitize_text_field($_POST['test_drive']);
        $address	 	  = sanitize_text_field($_POST['address']);
		$date	 		  = sanitize_text_field($_POST['date']);
		$time	 		  = sanitize_text_field($_POST['time']);
		$car_id 		  = sanitize_text_field($_POST['car_id']);
		
        if (!isset($_POST['shedule_test_drive_nonce']) || !wp_verify_nonce($_POST['shedule_test_drive_nonce'], 'shedule_test_drive_nonce'))
		{
			$errors[] =  esc_html__("Sorry, your nonce did not verify.Refresh Page and try again.", "cardealer-helper");
        } elseif($recaptcha_response['success'] == false){                
            $errors[] = esc_html__("Please check the the captcha form", "cardealer-helper");
		} 
		else
		{
			$mail_set = 0;
			$request_date = (isset($recaptcha_response['challenge_ts']) && !empty($recaptcha_response['challenge_ts']))? $recaptcha_response['challenge_ts'] : '';
			$post_id = wp_insert_post( array(
				'post_status' => 'publish',
				'post_type' => 'schedule_test_drive',
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
				update_field( 'last_name', $last_name, $post_id );
				update_field( 'email', $email, $post_id );
				update_field( 'mobile', $mobile, $post_id );
				update_field( 'address', $address, $post_id );
				update_field( 'state', $state, $post_id );
				update_field( 'zip', $zip, $post_id );
				update_field( 'preferred_contact', $preferred_contact, $post_id );
				update_field( 'test_drive', $test_drive, $post_id );
				update_field( 'date', $date, $post_id );
				update_field( 'time', $time, $post_id );				
				
				// MAIL STARTS
				$fromName = empty($car_dealer_options['std_mail_from_name'])? 'no-reply@'.$_SERVER['HTTP_HOST'] : $car_dealer_options['std_mail_from_name'];
				$fromMail = empty($car_dealer_options['std_mail_id_from'])? 'no-reply@'.$_SERVER['HTTP_HOST'] : $car_dealer_options['std_mail_id_from']; 
				$replyToMail = empty($email)? 'no-reply@'.$_SERVER['HTTP_HOST'] : $email;
				$replyToName = empty($first_name)? 'User' : $first_name;
				$subject = empty($car_dealer_options['std_subject'])? esc_html__('Test Drive Inquiry Mail', 'cardealer-helper') : $car_dealer_options['std_subject'];
				
				$headers = "From: " . strip_tags($fromName) . "<".$fromMail.">\r\n";
				$headers .= "Reply-To: ". strip_tags($replyToName) . "<".$replyToMail.">\r\n";				
				$mail_error =0;				
				
				// PREPARE PRODUCT DETAIL FOR MAIL
				$adf_data = $plain_mail = $product ="";
                $car_id = $_POST['car_id'];
                if(isset($car_id) && !empty($car_id)){
					$product = cdhl_get_html_mail_body($car_id);
					$plain_mail = cdhl_get_text_mail_body($car_id);
					
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
											$adf_data .= '<name part="full">'.$first_name.' '.$last_name.'</name>' . PHP_EOL;
											$adf_data .= '<email>'.$email.'</email>' . PHP_EOL;
											$adf_data .= '<phone type="voice" time="day">'.$mobile.'</phone>' . PHP_EOL;
											$adf_data .= '<address>' . PHP_EOL;
												  $adf_data .= '<street line="1">'.$address.'</street>' . PHP_EOL;
												  $adf_data .= '<postalcode>'.$zip.'</postalcode>' . PHP_EOL;
											$adf_data .= '</address>' . PHP_EOL;
										 $adf_data .= '</contact>' . PHP_EOL;
										 $adf_data .= '<comments>' . PHP_EOL;
										 $schedule="";										 								
										 $schedule .= esc_html__('Inquiry Information: ', 'cardealer-helper') . PHP_EOL . PHP_EOL;								
									  	 $schedule .= esc_html__('First Name :', 'cardealer-helper').$first_name . PHP_EOL;
										 $schedule .= esc_html__('Last Name :', 'cardealer-helper').$last_name . PHP_EOL;
										 $schedule .= esc_html__('Email :', 'cardealer-helper').$email . PHP_EOL;
										 $schedule .= esc_html__('Mobile :', 'cardealer-helper').$mobile . PHP_EOL;
										 $schedule .= esc_html__('Address :', 'cardealer-helper').$address . PHP_EOL;
										 $schedule .= esc_html__('State :', 'cardealer-helper').$state . PHP_EOL;
										 $schedule .= esc_html__('Zip :', 'cardealer-helper').$zip . PHP_EOL;
										 $schedule .= esc_html__('Preferred Contact :', 'cardealer-helper').$preferred_contact . PHP_EOL;
										 $schedule .= esc_html__('Test Drive ?:', 'cardealer-helper').$test_drive . PHP_EOL;
										 if( $test_drive == 'yes' ){
											 $schedule .= esc_html__('Date :', 'cardealer-helper').$date . PHP_EOL;
											 $schedule .= esc_html__('Time :', 'cardealer-helper').$time . PHP_EOL;										
										 }
										 $adf_data .= $schedule . PHP_EOL;										 
										 $adf_data .= '</comments>' . PHP_EOL;
									 $adf_data .= '</customer>' . PHP_EOL;
									 $adf_data .= '<vendor>' . PHP_EOL;
										$adf_data .= '<contact>' . PHP_EOL;
											 $adf_data .= '<name part="full">'. $car_dealer_options['std_mail_from_name'] .'</name>' . PHP_EOL;
										$adf_data .= '</contact>' . PHP_EOL;
									 $adf_data .= '</vendor>' . PHP_EOL;
								  $adf_data .= '</prospect>' . PHP_EOL;
								$adf_data .= '</adf>' . PHP_EOL;
                }				
				
				// Sending ADF Mail
				if( isset($car_dealer_options['std_adf_mail_to']) && !empty($car_dealer_options['std_adf_mail_to']) ) {
					$mail_set = 1;
					$to = $car_dealer_options['std_adf_mail_to'];
					// Mail body
					$adf_headers = $headers;	
					$adf_headers .= "MIME-Version: 1.0\r\n";
					$adf_headers .= "content-type: text/plain; charset=UTF-8\r\n";	
					if( !wp_mail( $to, $subject, $adf_data, $adf_headers) ) $mail_error = 1;
				}
				
				// REPLACE TAGS WITH ACTUAL FIEDLS
				$fields = array(
					'CD_FROM_NAME' 	=> $car_dealer_options['std_mail_from_name'], 
					'CD_FIRST_NAME' => $first_name,
					'CD_LAST_NAME' 	=> $last_name,
					'CD_EMAIL' 		=> $email,
					'CD_MOBILE' 	=> $mobile,
					'CD_ADDRESS' 	=> $address,
					'CD_STATE' 		=> $state,
					'CD_ZIP' 		=> $zip,
					'CD_PREFERRED_CONTACT' => $preferred_contact,
					'CD_TEST_DRIVE' => $test_drive,
					'CD_DATE' 		=> $date,
					'CD_TIME' 		=> $time
				);
				
				// Sending HTML Mail
				if( isset($car_dealer_options['std_html_mail_to']) && !empty($car_dealer_options['std_html_mail_to']) ) {
					$mail_set = 1;
					$to = $car_dealer_options['std_html_mail_to'];
					// Mail body
					if( isset($car_dealer_options['sstd_html_body']) && !empty($car_dealer_options['sstd_html_body'])){
						$std_html_body = $car_dealer_options['sstd_html_body'];
						$fields['PRODUCT_DETAIL'] = $product;
						
						foreach( $fields as $tag => $value ) {
							if( strpos($std_html_body, '#'.$tag.'#') !== false )
								$std_html_body = str_replace( '#'.$tag.'#', $value, $std_html_body );
						}
					}
					else
						$std_html_body = esc_html__('One Inquiry Received', 'cardealer-helper');
					
					$html_headers = $headers;
					$html_headers .= "MIME-Version: 1.0\r\n";	
					$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					if( !wp_mail( $to, $subject, $std_html_body, $html_headers) ) $mail_error = 1;
				}
				
				// Sending Text Mail
				if( isset($car_dealer_options['std_txt_mail_to']) && !empty($car_dealer_options['std_txt_mail_to']) ) { 
					$mail_set = 1;
					$to = $car_dealer_options['std_txt_mail_to'];
					// Mail body
					if( isset($car_dealer_options['std_txt_body']) && !empty($car_dealer_options['std_txt_body'])){
						$std_txt_body = $car_dealer_options['std_txt_body'];
						
						$fields['PRODUCT_DETAIL'] = $plain_mail;
						foreach( $fields as $tag => $value ) {
							if( strpos($std_txt_body, '#'.$tag.'#') !== false )
								$std_txt_body = str_replace( '#'.$tag.'#', $value, $std_txt_body );
						}
					}
					else
						$std_txt_body = esc_html__('One Inquiry Received', 'cardealer-helper');
					
					$text_headers = $headers;	
					$text_headers .= "MIME-Version: 1.0\r\n";
					$text_headers .= "content-type: text/plain; charset=UTF-8\r\n";					
					if( !wp_mail( $to, $subject, $std_txt_body, $text_headers) ) $mail_error = 1;
				}
				
				// If not mail is set from admin form options then mail will sent to admin
				if( $mail_set == 0 ) {
					$html_headers = $headers;
					$html_headers .= "MIME-Version: 1.0\r\n";	
					$html_headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					$to = get_option('admin_email');
					// Mail body
					if( isset($car_dealer_options['sstd_html_body']) && !empty($car_dealer_options['sstd_html_body'])){
						$std_html_body = $car_dealer_options['sstd_html_body'];
						$fields['PRODUCT_DETAIL'] = $product;
						
						foreach( $fields as $tag => $value ) {
							if( strpos($std_html_body, '#'.$tag.'#') !== false )
								$std_html_body = str_replace( '#'.$tag.'#', $value, $std_html_body );
						}
					}
					else
						$std_html_body = esc_html__('One Inquiry Received', 'cardealer-helper');
					if( !wp_mail( $to, $subject, $std_html_body,$html_headers) ) $mail_error = 1;
				}
				
				if( $mail_error == 1 ) {
					$errors[] = esc_html__("Sorry there was an error sending your message. Please try again later.", "cardealer-helper");
				} else {
					$responseArray = array(
						'status' => esc_html__("1", "cardealer-helper"),
						'msg' => '<div class="alert alert-success">'.esc_html__("Request sent successfully", "cardealer-helper").'</div>'
					);
				}
				
			} else {					
                $errors[] = esc_html__("Something went wrong, please try again later!", "cardealer-helper");
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