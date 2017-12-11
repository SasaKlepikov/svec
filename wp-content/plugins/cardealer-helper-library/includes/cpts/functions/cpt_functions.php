<?php 
global $form_cpts, $wp_version;

// REMOVE ROW ACTIONS FOR DEALER FORMS POST TYPES [ 'ADD' ACTION ]
add_filter( 'post_row_actions', 'cdhl_forms_row_actions', 10, 2 );
function cdhl_forms_row_actions( $actions, WP_Post $post ) {
	if ( $post->post_type != 'pgs_inquiry' && $post->post_type != 'make_offer' && $post->post_type != 'schedule_test_drive' && $post->post_type != 'financial_inquiry') {
        return $actions;
    }
	unset($actions['inline hide-if-no-js']);
	if(isset($actions['edit']))
	$actions['edit'] = str_replace('Edit', 'View', $actions['edit']);
	return $actions;
}

// CODE TO REMOVE METABOX FROM POST TYPE
add_action( 'add_meta_boxes', 'cdhl_my_remove_publish_metabox' );
function cdhl_my_remove_publish_metabox() {
    // Remove right sidebar metabox
	remove_meta_box( 'submitdiv', 'pgs_inquiry', 'side' );
	remove_meta_box( 'submitdiv', 'schedule_test_drive', 'side' );
	remove_meta_box( 'submitdiv', 'make_offer', 'side' );
	remove_meta_box( 'submitdiv', 'financial_inquiry', 'side' );
	
	// Remove Revolution Slider Metabox
	remove_meta_box( 'mymetabox_revslider_0', 'pgs_inquiry', 999 );
	remove_meta_box( 'mymetabox_revslider_0', 'schedule_test_drive', 999 );
	remove_meta_box( 'mymetabox_revslider_0', 'make_offer', 999 );
	remove_meta_box( 'mymetabox_revslider_0', 'financial_inquiry', 999 );
}

$form_cpts = array( 'pgs_inquiry', 'schedule_test_drive', 'make_offer', 'financial_inquiry'); // DEALER CPTS

// CODE TO REMOVE EDIT ACTION
if ( $wp_version >= 4.7 ) { 
	foreach( $form_cpts as $cpt ) {
		add_filter( 'bulk_actions-edit-'.$cpt, 'cdhl_remove_edit_action');	
	}
} else { // LOWER VERSION
	add_action('admin_footer', 'cdhl_dealer_form_admin_footer'); // REMOVE EDIT ACTION
}

// FUNCTION TO REMOVE EDIT ACTION [ FOR WP VERSION >= 4.7 ]
function cdhl_remove_edit_action($bulk_actions) { 
	global $post_type, $form_cpts;
	if( in_array( $post_type, $form_cpts ) ) {
		unset($bulk_actions['edit']);
	}
  return $bulk_actions;
}

// FUNCTION TO REMOVE EDIT ACTION FROM BULK ACTIONS
function cdhl_dealer_form_admin_footer() {
	global $post_type, $form_cpts; 
	if(in_array($post_type, $form_cpts)) {
		?>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery("select[name='action'] option:contains('Edit')").remove();
				});
			</script>
		<?php
	}
}

// FUNCTION TO ADD BACK TO POST TYPE LISTING PAGE
function cdhl_add_back_button( $post ) { 
    global $post, $form_cpts;
	
	if( !in_array( $post-> post_type, $form_cpts )  ) return;
	
	$link = admin_url( 'edit.php?post_type=' . $post-> post_type );
	$back_text = esc_html__( '&laquo; Back', 'cardealer-helper' );
	?>
	<br><br><a href="<?php echo esc_url($link)?>" class="page-title-action"><?php echo esc_html__($back_text)?></a><br>
	<?php
}
add_action( 'edit_form_top', 'cdhl_add_back_button' );
?>