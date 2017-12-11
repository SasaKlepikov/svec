/**
 * Backend JS for cd_datepicker - parameter
 */
(function ($) {

    vc.atts.cd_datepicker = {
        render: function ( param, value ) {
            return value;
        },
        init: function ( param, $field ) {
			var pickerOpts = {
				dateFormat: 'yy-mm-dd',							
				buttonText: '<i class="fa fa-calendar"></i>',					
			};
            $field.find( '.wpb_vc_param_value' ).datepicker(pickerOpts);
        }
    };

})(window.jQuery);