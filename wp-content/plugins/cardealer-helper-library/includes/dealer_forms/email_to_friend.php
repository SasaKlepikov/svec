<?php
add_action("wp_ajax_email_to_friend", "cdhl_email_to_friend");
add_action("wp_ajax_nopriv_email_to_friend", "cdhl_email_to_friend");
if(!function_exists("cdhl_email_to_friend")){
    function cdhl_email_to_friend(){
		global $car_dealer_options;
		$responseArray = array(            				
				"status"  => "error",
                "msg" => "Something went wrong!"            			
		);
        $errors = array();
        if($_POST['action'] == "email_to_friend"){            
            $captcha=$_POST['g-recaptcha-response'];
            $recaptcha_response=cardealer_validate_google_captch($captcha);			
            $uname         = sanitize_text_field($_POST['uname']);
			$email         = sanitize_text_field($_POST['email']);
			$friends_email = sanitize_text_field($_POST['friends_email']);
			$message 	   = sanitize_text_field($_POST['message']);
			$fromMail      = isset( $car_dealer_options['site_email'] )? $car_dealer_options['site_email'] : get_option('admin_email');
			
            if($recaptcha_response['success'] == false){                
                $errors[] = esc_html__("Please check the the captcha form", "cardealer-helper");
    		} elseif (!isset($_POST['etf_nonce'])||!wp_verify_nonce($_POST['etf_nonce'],'email-to-friend-form')){
    			$errors[] =  esc_html__("Sorry, your nonce did not verify.Refresh Page and try again.", "cardealer-helper");
            }else {    			
				$subject = empty($car_dealer_options['email_friend_subject'])? ucwords(str_replace("_", " ", sanitize_text_field($_POST['form']))) : $car_dealer_options['email_friend_subject'];
				$fromName = empty($car_dealer_options['email_friend_from_name'])? esc_html__('Car Dealer', 'cardealer-helper') : $car_dealer_options['email_friend_from_name'];
				
				$headers = "From: " . strip_tags($fromName) . "<".$fromMail.">\r\n";
				$headers .= "Reply-To: ". strip_tags($fromName) . "<".$email.">\r\n";				
				$headers .= "MIME-Version: 1.0\r\n";	
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				$mail_error =0;				
            
            	
                $car_id = $_POST['car_id'];
                $product="";
                if(isset($car_id) && !empty($car_id)){
                    $product = cdhl_get_html_mail_body($car_id);
                }
				
				$messageBody = esc_html__('Hello ', 'cardealer-helper');
				$messageBody .= (isset($uname) && !empty($uname))? $uname.',<br><br>' : '';			
				
    			$name    = (isset($uname) && !empty($uname) ? $uname : "");
    			$friend  = (isset($friends_email) && !empty($friends_email) ? $friends_email : "");
    			$messageBody .= (isset($message) && !empty($message) ? $message : "");
    
       			$body = $messageBody."<br><br>";
				$body .= esc_html__("Product Detail :", "cardealer-helper");
                $body .= "<br>".$product."<br><br>";
				$body .= esc_html__("--
This e-mail was sent from a contact form on Cardealer (". esc_url(get_site_url()) .")", "cardealer-helper")."\r\n";
    			
                if(wp_mail($friend, $subject, $body, $headers)){
                    $responseArray = array(            				
            				"status"  => esc_html__("1", "cardealer-helper"),
                            "msg" => '<div class="alert alert-success">'.esc_html__("Sent Successfully", "cardealer-helper").'</div>'            			
            		);
                } else {   			
                    $errors[] = esc_html__("Sorry there was an error sending your message. Please try again later.", "cardealer-helper");    
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
        exit();
    }
}
?>