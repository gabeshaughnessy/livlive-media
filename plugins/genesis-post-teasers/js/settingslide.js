jQuery(document).ready(function($) {
	
	jQuery("#gPostTeaz-settings-box input:radio:checked").live('load change', function() {
		
		if ( jQuery(this).val() == 'stylesheet' ) {
			jQuery("#gPostTeaz-settings-box .css-opts").hide('fast');
		}
		
		if ( jQuery(this).val() != 'stylesheet' ) {
			jQuery("#gPostTeaz-settings-box .css-opts").show('fast');
		}
		
	});
	
	jQuery("#gPostTeaz-settings-box .css-opts input[type='checkbox']").live('load change', function() {
		
		if ( jQuery(this).is(':checked')) {
			jQuery("#gPostTeaz-settings-box .custom-css-opts").show('fast');
		} else {	
			jQuery("#gPostTeaz-settings-box .custom-css-opts").hide('fast');
		}
		
	});
	
	jQuery("#gPostTeaz-settings-box .enable-thumbnail input[type='checkbox']").live('load change', function() {
		
		if ( jQuery(this).is(':checked')) {
			jQuery("#gPostTeaz-settings-box .select-image-size").show('fast');
		} else {	
			jQuery("#gPostTeaz-settings-box .select-image-size").hide('fast');
		}
		
	});
	
	jQuery("#gPostTeaz-settings-box .enable-feat-thumbnail input[type='checkbox']").live('load change', function() {
		
		if ( jQuery(this).is(':checked')) {
			jQuery("#gPostTeaz-settings-box .select-feat-image-size").show('fast');
		} else {	
			jQuery("#gPostTeaz-settings-box .select-feat-image-size").hide('fast');
		}
		
	});
	
	
});

function genesis_confirm( text ) {
	var answer = confirm( text );
	
	if( answer ) { return true; }
	else { return false; }
}