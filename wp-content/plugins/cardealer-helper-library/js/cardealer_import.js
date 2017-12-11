jQuery(document).ready(function(){
    
    jQuery(document).on("click", ".cdhl_save_current_mapping", function (e) {
        e.preventDefault();
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {action: 'cdhl_save_current_mapping', form: jQuery("#cdhl_csv_import").serialize(), nonce: jQuery("#cdhl_csv_import").data("nonce") },
            beforeSend: function(){
                jQuery('.cdhl-loader-img').html('<img src="'+cdhl.cdhl_url+'images/loader.gif" alt="loading.." width="25px" height="25px"/>');            
            },
            success: function (response) {
                jQuery('.cdhl-loader-img').html('');
                jQuery('.res-msg').css('color','green');
                jQuery('.res-msg').show();
                jQuery('.res-msg').html('Mapping saved successfully').delay(5000).fadeOut('slow');
            }
        });
    });
    
    /* File Import JS */
    if (jQuery("#cdhl_csv_items, .cdhl_cars_attributes").length) {
        jQuery("#cdhl_csv_items, .cdhl_cars_attributes").sortable({
            connectWith: ".cdhl_connected_sortable",
            placeholder: "ui-state-highlight",
            //forcePlaceholderSize: true,
            revert: true,
            start: function (e, ui) {
                ui.placeholder.height(ui.item.height());
            },
            receive: function (event, ui) {
                var $this = jQuery(this);
                if ($this.data("limit") == 1 && $this.children('li').length > 1 && $this.attr('id') != "cdhl_csv_items") {
                    alert(jQuery("#cdhl_csv_import").data("one-per"));
                    jQuery(ui.sender).sortable('cancel');
                }
                // set val
                var name = $this.data('name');
                ui.item.find('input[type="hidden"]').val(name);
            }
        });
    }
    
    jQuery("#cdhl-car-title").chosen({no_results_text: "Oops, nothing found!",width: "35%"}); 
    
    jQuery(".cdhl_submit_csv").click(function () {          
        
        jQuery('.cdhl-loader-img').html('<img src="'+cdhl.cdhl_url+'images/loader.gif" alt="loading.." width="25px" height="25px"/>');
        if(jQuery("#cdhl_vin_not_in_csv").is(":checked")){            
            var dop_sel = jQuery('select[name="vin_not_in_csv_in_db"] option:selected').val();
            if(dop_sel == 'delete'){
                if(!confirm("Are you sure? All data will be move to trash!")){
                  return false;  
                } else {
                    jQuery('.cdhl-loader-img').html('<img src="'+cdhl.cdhl_url+'images/loader.gif" alt="loading.." width="25px" height="25px"/>');
                    jQuery("#cdhl_csv_import").submit();
                }
            }
        }
    });    
    
    jQuery(document).on("click", "#cdhl_new_vin", function(){    
        var item = jQuery(this);    
        if(item.is(":checked")){
            jQuery(".cdhl_new_vin").show();
        } else {
            jQuery(".cdhl_new_vin").hide();
        }
    });
    
    jQuery(document).on("click", "#cdhl_vin_not_in_csv", function(){    
        var item = jQuery(this);    
        if(item.is(":checked")){
            jQuery(".cdhl_vin_not_in_csv").show();
        } else {
            jQuery(".cdhl_vin_not_in_csv").hide();
        }
    });   
        
    jQuery(document).on("click", "#cdhl_duplicate_check_vin", function(){    
        var item = jQuery(this);    
        if(item.is(":checked")){
            jQuery(".cdhl_overwrite_existing_car_images").show();
        } else {
            jQuery(".cdhl_overwrite_existing_car_images").hide();
        }
    });
    
    
    jQuery( function() {
        jQuery( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
        jQuery( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    });
	
	jQuery(document).ready( function get_log_data(){
		var nonce = jQuery('.import_log_area').attr('data-log_nonce');
		if( jQuery('.import_log_area').length ){
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				dataType : 'json',
				data: { action: 'show_import_log', nonce: nonce },
				beforeSend: function(){
					jQuery('.import_log_area span.log_loader').html('<img src="'+cdhl.cdhl_url+'images/loader.gif" alt="loading.." width="25px" height="25px"/>');            
				},
				success: function (response) {
					if( response.log_status != '0'){ 
						jQuery('.import_log_area div.log_data').html( response.log_error );
					} else { 
						jQuery('.import_log_area div.log_data').html(response.imported_cars);
						if( response.import_status != 1 ) { 
							setTimeout( get_log_data, 5000 );
						} else {
							jQuery('.import_log_area span.log_loader').html('');
							jQuery('.cdhl_import-notice').html('');							
							jQuery('.import_log_area div.log_data').append('<br>Congratulations! Data Import Completed.');
						}
					}
				}
			});
		}
		
	});
});

function cdhl_getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}