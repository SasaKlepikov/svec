<?php
/*
 * This function use for subscribe user in mailchimp.
 */
add_action('wp_ajax_mailchimp_singup','cdhl_mailchimp_signup_action');
add_action('wp_ajax_nopriv_mailchimp_singup','cdhl_mailchimp_signup_action');
function cdhl_mailchimp_signup_action(){
	
	require_once trailingslashit(CDHL_PATH) . 'includes/lib/MCAPI.class.php'; // mailchimp class library
	
    global $car_dealer_options;
    $mailchimp_api_key = isset($car_dealer_options['mailchimp_api_key']) ? $car_dealer_options['mailchimp_api_key'] : '';
    $mailchimp_list_id = isset($car_dealer_options['mailchimp_list_id']) ? $car_dealer_options['mailchimp_list_id'] : '';
	//INCLUDE DEFAULT MAILCHIMP FILES FINISH.
	$apikey = $mailchimp_api_key;
	//$listId = 'YOUR MAILCHIMP LIST ID - see lists() method';
	$listId = $mailchimp_list_id;
	
	$apiUrl = 'http://api.mailchimp.com/1.3/';
    
    if (!isset($_REQUEST['mailchimp_nonce'])||!wp_verify_nonce($_REQUEST['mailchimp_nonce'],'mailchimp_news')){
		esc_html_e("Sorry, Something went wrong, Please refresh page and try again.", "cardealer-helper");
        wp_die();
    }
	//CONFIGURE VARIABLES START.
	$api = new MCAPI($apikey);
	$email = sanitize_text_field($_REQUEST['news_letter_email']);
	$merge_vars = array('FNAME' => '');
	
	//CONFIGURE VARIABLES FINISH.
	//MAKE API CALLSE FOR MAILCHIMP START.
	
	$retval = $api->listSubscribe( $listId, $email, $merge_vars );
	//MAKE API CALLSE FOR MAILCHIMP FINISH.
	//CONFIGURE ERROR OR SUCCESS PROCESS START.
	if ($api->errorCode){
		echo $api->errorMessage."\n";
	} else {
		esc_html_e("Successfully Subscribed. Please check confirmation email.", 'cardealer-helper' );
	}
	
	wp_die();
}