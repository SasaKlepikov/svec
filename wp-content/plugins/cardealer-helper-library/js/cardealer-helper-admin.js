/* Code for dialog on reduct dealer forms */
jQuery( function() {
	if( jQuery( ".variable-content" ).length ) {
		jQuery( ".variable-content" ).dialog({
		  autoOpen: false,
		  show: {
			effect: "blind",
			duration: 1000
		  },
		  hide: {
			effect: "explode",
			duration: 1000
		  }
		});
		jQuery( ".cd_dialog" ).on( "click", function( event ) {
			event.preventDefault();		
			var dialog = jQuery(this).attr('data-id');
			jQuery( '#' + dialog ).dialog( "open" );
			/* Add pgs_dialog class to parent div of dialog to differenciate from other dialogs for close button issue */
			if ( jQuery('.ui-dialog').attr('aria-describedby') == dialog ) jQuery('.ui-dialog').addClass('pgs_dialog');
		});
	}
});