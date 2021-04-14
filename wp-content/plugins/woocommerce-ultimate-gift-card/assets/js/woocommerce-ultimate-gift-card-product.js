jQuery(document).ready(function($){

   
	$("#mwb_wgm_exclude_per_product").select2();
	$("#mwb_wgm_exclude_per_category").select2();
	$("#mwb_wgm_email_template").select2();
  
	mwb_wgm_show_and_hide_panels();
	$( 'select#product-type' ).change( function() {
		mwb_wgm_show_and_hide_panels();
	});
	
	var pricing_option = $('#mwb_wgm_pricing').val();
	mwb_wgm_show_and_hide_pricing_option(pricing_option);
	
	$( '#mwb_wgm_pricing' ).change( function() {
		
		var pricing_option = $(this).val();
		mwb_wgm_show_and_hide_pricing_option(pricing_option);
	});

	$(document).on('change','#mwb_wgm_email_template',function(){
		var template_ids = $(this).val();
		/*if(template_ids != null)
		{*/	
			jQuery('#mwb_wgm_loader').show();
			var data = {
				action:'mwb_wgm_append_default_template',
				template_ids:template_ids,	
			};
	      	
	      	$.ajax({
	  			url: mwb_wgm.ajaxurl, 
	  			type: "POST",  
	  			data: data,
	  			dataType :'json',
	  			success: function(response) 
	  			{	
	  				
		  			if(response.result == 'success')
                    {
                        var templateid = response.templateid;
                        var option = '';
                        for(key in templateid)
                        {
                            option += '<option value="'+key+'">'+templateid[key]+'</option>';
                        } 
                        //alert(option);  
                        jQuery("#mwb_wgm_email_defualt_template").html(option);
                    	jQuery("#mwb_wgm_loader").hide();
                    }
                    else if(response.result == 'no_ids')
                    {
                    	 var option = '';
                    	 option = '<option value="">'+mwb_wgm.append_option_val+'</option>';
                    	 jQuery("#mwb_wgm_email_defualt_template").html(option);
                    	jQuery("#mwb_wgm_loader").hide();
                    }
	  			}
	  		});
		//}
	});
	$( '#mwb_wgm_resend_mail_button' ).click( function() {
		$("#mwb_wgm_resend_mail_notification").html("");
		var order_id = $(this).data('id');
		$("#mwb_wgm_loader").show();
		var data = {
				      action:'mwb_wgm_resend_mail',
					  order_id:order_id,
				   };
	
		$.ajax({
			url: mwb_wgm.ajaxurl, 
			type: "POST",  
			data: data,
			dataType :'json',	
			success: function(response) 
			{
				$("#mwb_wgm_loader").hide();
				if(response.result == true)
				{
					var message = response.message;
					var html = '<b style="color:green;">'+message+'</b>'
				}	
				else
				{
					var message = response.message;
					var html = '<b style="color:red;">'+message+'</b>'
					
				}	
				$("#mwb_wgm_resend_mail_notification").html(html);
			}
		});
		
	});
	$( '#mwb_inc_money_coupon' ).click( function() {
		
		var selectedcoupons = $("#mwb_select_coupon_product").select2("val");
		var selectedprice = $("#mwb_inc_amount").val();
		
		if(!selectedcoupons.length){
			$("#mwb_wgm_resend_coupon_amount_msg").html('<b style="color:red;">Please select coupon first</b>');
			return;
		}
		if(selectedprice == ""){
			$("#mwb_wgm_resend_coupon_amount_msg").html('<b style="color:red;">Please enter valid price</b>');
			return;
		}
		

		var order_id = $(this).data('id');
		$("#mwb_wgm_resend_coupon_amount_msg").html("");
		$("#mwb_wgm_loader").show();
		var data = {
			action:'mwb_wgm_resend_coupon_amount',
			order_id:order_id,
			selectedcoupon:selectedcoupons,
			selectedprice: selectedprice
	    };
		$.ajax({
			url: mwb_wgm.ajaxurl, 
			type: "POST",  
			data: data,
			dataType :'json',	
			success: function(response) 
			{
				console.log(response);
				$("#mwb_wgm_loader").hide();
				if(response.result == true)
				{
					var message = response.message;
					var html = '<b style="color:green;">'+message+'</b>'
				}	
				else
				{
					var message = response.message;
					var html = '<b style="color:red;">'+message+'</b>'
					
				}	
				$("#mwb_wgm_resend_coupon_amount_msg").html(html);
			}
		});
		
	
	});
	function mwb_wgm_show_and_hide_pricing_option(pricing_option){
		$( '.mwb_wgm_from_price_field' ).show(); 
		$( '.mwb_wgm_to_price_field' ).show();  
		$( '.mwb_wgm_selected_price_field' ).show(); 
		$( '.mwb_wgm_default_price_field' ).show(); 
		$( '.mwb_wgm_user_price_field' ).show(); 
		
		if(pricing_option == 'mwb_wgm_selected_price')
		{
			$( '.mwb_wgm_from_price_field' ).hide(); 
			$( '.mwb_wgm_to_price_field' ).hide();  
			$( '.mwb_wgm_default_price_field' ).hide(); 
			$( '.mwb_wgm_user_price_field' ).hide();
			$('#mwb_wgm_discount').parent().hide();
		}
		if(pricing_option == 'mwb_wgm_range_price')
		{
			$( '.mwb_wgm_selected_price_field' ).hide();
			$( '.mwb_wgm_default_price_field' ).hide(); 
			$( '.mwb_wgm_user_price_field' ).hide();
			$('#mwb_wgm_discount').parent().show();
		}
		if(pricing_option == 'mwb_wgm_default_price')
		{
			$( '.mwb_wgm_from_price_field' ).hide(); 
			$( '.mwb_wgm_to_price_field' ).hide();  
			$( '.mwb_wgm_selected_price_field' ).hide(); 
			$( '.mwb_wgm_user_price_field' ).hide();
			$('#mwb_wgm_discount').parent().show();
		}
		if(pricing_option == 'mwb_wgm_user_price')
		{
			$( '.mwb_wgm_from_price_field' ).hide(); 
			$( '.mwb_wgm_to_price_field' ).hide();  
			$( '.mwb_wgm_default_price_field' ).hide(); 
			$( '.mwb_wgm_selected_price_field' ).hide();
			$('#mwb_wgm_discount').parent().show();
		}
	}
	
	function mwb_wgm_show_and_hide_panels() {
		var product_type    = $( 'select#product-type' ).val();
		var is_mwb_wgm_gift = false;
		var is_tax_enable_for_gift = mwb_wgm.is_tax_enable_for_gift;

		if(product_type == "wgm_gift_card")
		{
			is_mwb_wgm_gift = true;
		}	
		if(is_mwb_wgm_gift)
		{	
			// Hide/Show all with rules.
			var hide_classes = '.hide_if_mwb_wgm_gift, .hide_if_mwb_wgm_gift';
			var show_classes = '.show_if_mwb_wgm_gift, .show_if_mwb_wgm_gift';
	
			$.each( woocommerce_admin_meta_boxes.product_types, function( index, value ) {
				hide_classes = hide_classes + ', .hide_if_' + value;
				show_classes = show_classes + ', .show_if_' + value;
			});
	
			$( hide_classes ).show();
			$( show_classes ).hide();
	
			// Shows rules.
			if ( is_mwb_wgm_gift ) {
				$( '.show_if_mwb_wgm_gift' ).show();
			}
			
			$( '.show_if_' + product_type ).show();
	
			// Hide rules.
			if ( !is_mwb_wgm_gift ) {
				$( '.show_if_mwb_wgm_gift' ).hide();
			}
			
			$( '.hide_if_' + product_type ).hide();
	
			$( 'input#_manage_stock' ).change();
	
			// Hide empty panels/tabs after display.
			$( '.woocommerce_options_panel' ).each( function() {
				var $children = $( this ).children( '.options_group' );
	
				if ( 0 === $children.length ) {
					return;
				}
	
				var $invisble = $children.filter( function() {
					return 'none' === $( this ).css( 'display' );
				});
	
				// Hide panel.
				if ( $invisble.length === $children.length ) {
					var $id = $( this ).prop( 'id' );
					$( '.product_data_tabs' ).find( 'li a[href="#' + $id + '"]' ).parent().hide();
				}
			});
			
			$(".inventory_tab").attr("style", "display:block !important;");
			$("#inventory_product_data ._manage_stock_field").attr("style", "display:block !important;");
			$("#inventory_product_data .options_group").attr("style", "display:block !important;");
			$("#inventory_product_data ._sold_individually_field").attr("style", "display:block !important;");
			$("#general_product_data .show_if_simple.show_if_external.show_if_variabled").attr("style", "display:block !important;");
			if(is_tax_enable_for_gift == 'on')
			{
				$(document).find("#general_product_data .options_group.show_if_simple.show_if_external.show_if_variable").attr("style", "display:block !important;");
			}
		}
	}

	jQuery('#mwb_wgm_email_to_recipient').parent().css('display', 'none');
	jQuery('#mwb_wgm_download').parent().css('display', 'none');
	jQuery('#mwb_wgm_shipping').parent().css('display', 'none');
	if($('input[id="mwb_wgm_overwrite"]').is(":checked")){
		jQuery('#mwb_wgm_email_to_recipient').parent().show();
	    jQuery('#mwb_wgm_download').parent().show();
	    jQuery('#mwb_wgm_shipping').parent().show();
	}
	jQuery('#mwb_wgm_overwrite').change(function(){

		if(jQuery(this).is(":checked")) {
	       jQuery('#mwb_wgm_email_to_recipient').parent().show();
	       jQuery('#mwb_wgm_download').parent().show();
	       jQuery('#mwb_wgm_shipping').parent().show();
	   }
	   else{
	   	jQuery('#mwb_wgm_email_to_recipient').parent().hide();
	   	jQuery('#mwb_wgm_download').parent().hide();
	   	jQuery('#mwb_wgm_shipping').parent().hide();
	   }

	 });  
});