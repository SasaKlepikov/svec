jQuery(document).ready( function(){ 
	
    /*
    * Code for select checkboxes by default on car post type for export thirdparty
    */
    jQuery('select#bulk-action-selector-top').change( function(){
		if( jQuery(this).val() == 'export_autotrader' || jQuery(this).val() == 'export_car_com' || jQuery(this).val() == 'export_cars' ) {
			if( jQuery('#cb-select-all-1').prop("checked") != true )
				jQuery('#cb-select-all-1').trigger('click');
		} else {
			if( jQuery('#cb-select-all-1').prop("checked") == true )
				jQuery('#cb-select-all-1').trigger('click');
		}
	});
    
    /*
    * Code for set cars as featured car
    */
    jQuery(document).on("click", ".do_featured", function (e) {
        e.preventDefault();
        var post_id = jQuery(this).attr('data-id');
        var feature = jQuery(this).attr('data-feature');
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {action: 'cdhl_do_featured', post_id:post_id, feature:feature },
            success: function (response) {
                location.reload();
            }
        });
    });
    
    /*
    * Code for download PDF Brochure admin side
    */
    jQuery(document).on("click", ".download-pdf", function (e) {
        e.preventDefault();        
        // We'll pass this variable to the PHP function example_ajax_request        
        var id = jQuery(this).attr('id');
        var pdf_template_title = jQuery( "#casr_pdf_styles option:selected" ).text();
        
        jQuery('#loader-'+id).show();
        jQuery('#downloadlink-'+id).hide();
        // This does the ajax request
        jQuery.ajax({
			
            url:ajaxurl,
            type:'post',            
            data: {
                'action':'doPdf',
                'pdf_template_title': pdf_template_title,
                'id' : id
            },            
            success:function(data) {
                // This outputs the result of the ajax request
                jQuery('#loader-'+id).hide();
                jQuery('#downloadlink-'+id).show();
                jQuery('#downloadlink-'+id+' a').remove();
                jQuery('#downloadlink-'+id).append("<a href='"+data+"' target='_blank' download>Download PDF</b>");  
				//console.log(data);
				jQuery('html, body').animate({ scrollTop: 0 }, 'slow');
				if (!jQuery('#pdf-notice').length > 0){
					jQuery( ".page-title-action" ).after(cars_pdf_message.pdf_message);
				}
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });        
    });
    
    
    jQuery(document).on( 'change', '.casr_pdf_styles', function(){
        var id = jQuery(this).attr('data-id');        
        jQuery('#downloadlink-'+id).hide();
    });
    
    /* Code tobe use for export to third party */
    jQuery(document).on( 'change', '#bulk-action-selector-top', function(){
		(jQuery(this).val() == 'export_autotrader' || jQuery(this).val() == 'export_car_com' ) ? jQuery('#ftp_now').css( 'display', 'block' ) : jQuery('#ftp_now').css( 'display', 'none' );
	});
    
    /*Code Of Export Log List*/    
    if(document.getElementById('export-log')){
        jQuery('#export-log').DataTable({
        	processing: true,
        	serverSide: true,
        	responsive: true,
        	'bLengthChange': false,
        	'iDisplayLength': 20,
        	'bFilter': false,
        	"bSort" : false,
        	ajax:  jQuery.fn.dataTable.pipeline({
        		url: ajaxurl + '?action=get_export_log',
        		pages: 4 // number of pages to cache
        	})
        });    
    }   
	/*Code Of Export Log List*/    
    if(document.getElementById('export-log')){
        jQuery('#export-log').DataTable({
			destroy: true,
        	processing: true,
        	serverSide: true,
        	responsive: true,
        	'bLengthChange': false,
        	'iDisplayLength': 20,
        	'bFilter': false,
        	"bSort" : false,
        	ajax:  jQuery.fn.dataTable.pipeline({
        		url: ajaxurl + '?action=get_export_log',
        		pages: 4 // number of pages to cache
        	})
        });    
    }
	
	/*Code Of Import Log List*/    
    if(document.getElementById('import-log')){
        jQuery('#import-log').DataTable({
			destroy: true,
        	processing: true,
        	serverSide: true,
        	responsive: true,
        	'bLengthChange': false,
        	'iDisplayLength': 20,
        	'bFilter': false,
        	"bSort" : false,
        	ajax:  jQuery.fn.dataTable.pipeline({
        		url: ajaxurl + '?action=get_import_log',
        		pages: 4 // number of pages to cache
        	})
        });    
    }
	
	/*Code Of Export Cars List*/    
    if(document.getElementById('export-cars-list')){
        jQuery('#export-cars-list').DataTable({
			destroy: true,
        	processing: true,
        	serverSide: true,
        	responsive: true,
        	'bLengthChange': false,
        	'iDisplayLength': 20,
        	'bFilter': false,
        	"bSort" : false,
        	ajax:  jQuery.fn.dataTable.pipeline({
        		url: ajaxurl + '?action=get_export_car_list',
        		pages: 5 // number of pages to cache
        	})
        });    
    }
	
});