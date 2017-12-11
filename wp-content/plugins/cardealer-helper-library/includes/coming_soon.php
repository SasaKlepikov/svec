<?php
/*
 * This function used on coming soon page for getting mail addresses of visitors to notify
 */
add_action('wp_ajax_cardealer_notify_action','cdhl_notify_action');
add_action('wp_ajax_nopriv_cardealer_notify_action','cdhl_notify_action');
function cdhl_notify_action() {
global $car_dealer_options;
$responseArray = array();
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'cardealer_notify_action') {	
		if($_REQUEST['email'] == "") {
			$responseArray = [
						'status' => esc_html__("0", "cardealer-helper"),
						'msg' => esc_html__("Please provide email id", "cardealer-helper")
						];
		}
		else
		{
			if (filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL) === false) {
				$responseArray = [
						'status' => esc_html__("0", "cardealer-helper"),
						'msg' => esc_html__("Please enter valid email address", "cardealer-helper")
						];
				echo json_encode($responseArray);
				die;
			}
			if ( !isset($_REQUEST['notify_nonce']) || !wp_verify_nonce($_REQUEST['notify_nonce'], 'coming_soon') ){
				$responseArray = [
						'status' => esc_html__("2", "cardealer-helper"),
						'msg' => esc_html__("Sorry, your nonce did not verified. Refresh Page and try again.", "cardealer-helper")
						];
			} else {
				$headers[] = 'From: '.$_REQUEST['email'].' <'.$_REQUEST['email'].'>';
				$headers[] = 'Content-Type: text/html; charset=UTF-8';
				$headers[] = 'Reply-To: '. $_REQUEST['email'] . '\r\n';
				$to = get_bloginfo('admin_email');
				$subject = esc_html__("Notify User Mail", "cardealer-helper");
				$body = sprintf( wp_kses( __( 'Hello, <br> User visited site and requested to notify once site available. Following is the email id : <br><b>%1$s</b><br><br>Regards, <br>Cardealer Team' ), array( 'br' =>array(), 'b' =>array() )), $_REQUEST['email'] );
				// send inquiry mail
				if( wp_mail( $to, $subject, $body, $headers ) )
				{	
					$responseArray = [
								'status' => esc_html__("1", "cardealer-helper"),
								'msg' => esc_html__("Thank You! Your email id received successfully.", "cardealer-helper")
								];
				}
				else
				{
					$responseArray = [
							'status' => esc_html__("0", "cardealer-helper"),
							'msg' => esc_html__("Something went wrong, please try again later!", "cardealer-helper")
							];
				}
			}
		}
	}
	echo json_encode($responseArray);
	die;
}
?>