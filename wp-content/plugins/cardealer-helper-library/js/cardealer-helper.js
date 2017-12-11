jQuery(document).ready(function() {
    /* ---------------------------------------------
     Promocode
     --------------------------------------------- */
	jQuery('.promocode-btn').on('click',function(){                
        var form_id=jQuery(this).attr('data-fid');		
		var form=jQuery("#"+form_id);
        var promocode_action = jQuery("#"+form_id+' .promocode_action').val();
        var promocode_nonce = jQuery("#"+form_id+' .promocode_nonce').val();
        var promocode = jQuery("#"+form_id+' .promocode').val();
        if(promocode == 'Promocode'){
            promocode = '';    
        }
        jQuery.ajax({
            type:"POST", 
			url: cardealer_js.ajaxurl,
			data:{action:promocode_action,promocode_nonce:promocode_nonce,promocode:promocode},
            dataType:'json',
			beforeSend: function() {    
                jQuery('#'+form_id+' .spinimg').html('<span class="cd-loader"></span>');
            },
			success: function(responce){
				jQuery('#'+form_id+' .promocode-msg').show();
				jQuery('#'+form_id+' .promocode-msg').removeClass('error_msg');
				jQuery('#'+form_id+' .spinimg').html('');
                if(responce.status == 'success'){                    
                    form_data = '<input type="hidden" name="promocode" value="asasas' + promocode + '" />';
                    jQuery('<form>', {                        
                        "html": form_data,
                        "action": responce.url,
                        "method":'POST'
                    }).appendTo(document.body).submit();                                        
				} else {
				    jQuery('#'+form_id+' .promocode-msg').html(responce.msg);
				}
                
			},
			error: function(responce){
				jQuery('#'+form_id+' .promocode-msg').addClass('error_msg');
                jQuery('#'+form_id+' .promocode-msg').html(responce.msg);
				jQuery('#'+form_id+' .promocode-msg').html(msg);
				jQuery('#'+form_id+' .promocode-msg').show();
			}           
		});
        return false;        	
	});
    
    jQuery(document).on('click','.geo-closed',function(){        
        var minutes = 30;     
        var tMinutes = new Date(new Date().getTime() + minutes * 60 * 1000);
        cookies.set( 'geo_closed' , true, { expires: tMinutes });
        jQuery('.geo-bar').hide();   
    });
    
});

// Location sharing for geo fencing
if(parseInt(cdhl_obj.geofenc) > 0){    
    var msg = '';
    var geo_lat = cookies.get('geo_lat');
    var geo_lng = cookies.get('geo_lng');    
    if(!cookies.get('geo_lat')){        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else { 
            msg = "Geolocation is not supported by this browser.";
        }
    }
}

function showPosition(position) {
    var minutes = 30;     
    var tMinutes = new Date(new Date().getTime() + minutes * 60 * 1000);
    
    if(!cookies.get('geo_lat')){
        cookies.set( 'geo_lat' , position.coords.latitude, { expires: tMinutes });
        cookies.set( 'geo_lng' , position.coords.longitude, { expires: tMinutes });
        cookies.set( 'geo_closed' , true, { expires: tMinutes });        
    }
    
    var data = {
    	'action': 'findGeolocation',
        'lat': position.coords.latitude,
        'lng': position.coords.longitude
    }; 
    
    var html = ''; 
    jQuery.ajax({
        type: "POST",
        url: cardealer_js.ajaxurl,
        data: data,
        dataType: 'json',
        success: function(response) {
            if(response.status == 'success'){
                cookies.set( 'geo_msg' , response.content, { expires: tMinutes });
                html += '<div class="geo-bar">';
                    html += '<span class="geo-closed">&times;</span>';
                    html += '<div class="container">';
                        html += '<div class="row">';
                            html += '<div class="col-lg-12 col-sm-12">';
                                html += '<marquee class="geo-fencing" behavior="scroll" direction="left" scrollamount="5" style="width:100%; height:100%; vertical-align:middle; cursor:pointer;" onmouseover="javascript:this.setAttribute(\'scrollamount\',\'0\');" onmouseout="javascript:this.setAttribute(\'scrollamount\',\'5\');"><div class="geo-content">'+response.content+'</div></marquee>';       
                            html += '</div>';
                        html += '</div>';
                     html += '</div>';
                html += '</div>';
                jQuery('#page').prepend(html);
                jQuery('.geo-bar').show();    
            }
        }
    });      
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            msg = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            msg = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            msg = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            msg = "An unknown error occurred."
            break;
    }
}

// Promocode Print Shortcode
jQuery(document).on("click", ".pgs_print_btn", function () {
	var contents = jQuery('#'+jQuery(this).data('print_id')).html();
	var frame1 = jQuery('<iframe />');
	frame1[0].name = "frame1";
	frame1.css({ "position": "absolute", "top": "-1000000px" });
	jQuery("body").append(frame1);
	var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
	frameDoc.document.open();
	//Create a new HTML document.
	frameDoc.document.write('<html><head><title>Promocode</title>');
	frameDoc.document.write('</head><body>');
	//Append the DIV contents.
	frameDoc.document.write(contents);
	frameDoc.document.write('</body></html>');
	frameDoc.document.close();
	setTimeout(function () {
		window.frames["frame1"].focus();
		window.frames["frame1"].print();
		frame1.remove();
	}, 500);
});