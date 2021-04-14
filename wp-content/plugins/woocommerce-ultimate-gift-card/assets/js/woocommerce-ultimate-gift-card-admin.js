jQuery(document).ready(function($){
	$("#mwb_wgm_product_setting_exclude_product").select2();
	$("#mwb_wgm_product_setting_exclude_category").select2();
	
	 $('#mwb_wgm_mailcolor').wpColorPicker();
	
	var imageurl = $("#mwb_wgm_other_setting_upload_logo").val();
	if(imageurl != null && imageurl != "")
	{	
		$("#mwb_wgm_other_setting_upload_image").attr("src",imageurl);
	    $("#mwb_wgm_other_setting_remove_logo").show();
	    
	}
    var imageurl = $("#mwb_wgm_other_setting_background_logo_value").val();
	if(imageurl != null && imageurl != "")
	{
    	$("#mwb_wgm_other_setting_background_logo_image").attr("src",imageurl);
    	$("#mwb_wgm_other_setting_remove_background").show();
	}   
    
  jQuery('.mwb_wgm_other_setting_upload_logo').click(function(){
    var imageurl = $("#mwb_wgm_other_setting_upload_logo").val();

        tb_show('', 'media-upload.php?TB_iframe=true');

        window.send_to_editor = function(html)
        {
           var imageurl = jQuery(html).attr('href');
          
           if(typeof imageurl == 'undefined')
           {
             imageurl = jQuery(html).attr('src');
           }
           var last_index = imageurl.lastIndexOf('/');
            var url_last_part = imageurl.substr(last_index+1);
            if( url_last_part == '' ){
              
              imageurl = jQuery(html).children("img").attr("src");  
            }   
           $("#mwb_wgm_other_setting_upload_logo").val(imageurl);
           $("#mwb_wgm_other_setting_upload_image").attr("src",imageurl);
           $("#mwb_wgm_other_setting_remove_logo").show();
           tb_remove();
        };
        return false;
  });
	
	jQuery('.mwb_wgm_other_setting_background_logo').click(function(){
		var imageurl = $("#mwb_wgm_other_setting_background_logo_value").val();
        tb_show('', 'media-upload.php?TB_iframe=true');
        window.send_to_editor = function(html)
        {
           var imageurl = jQuery(html).attr('href');
           if(typeof imageurl == 'undefined')
           {
        	   imageurl = jQuery(html).attr('src');
           }	  
           $("#mwb_wgm_other_setting_background_logo_value").val(imageurl);
           $("#mwb_wgm_other_setting_background_logo_image").attr("src",imageurl);
           $("#mwb_wgm_other_setting_remove_background").show();
           tb_remove();
        };
        return false;
	});
  if($('#mwb_wgm_general_setting_downloable_enable').prop("checked") == true){
    $('.mwb_name_field').show();
  }
  $('#mwb_wgm_general_setting_downloable_enable').change(function(){
      
      if($(this).prop("checked") == true){
        $('.mwb_name_field').show();
      }
      else{
        $('.mwb_name_field').hide();
      }
  });
	
	jQuery( document.body ).trigger( 'init_tooltips' );
	
	jQuery(".mwb_wgm_other_setting_remove_logo_span").click(function(){
		jQuery("#mwb_wgm_other_setting_remove_logo").hide();
		jQuery("#mwb_wgm_other_setting_upload_logo").val("");
	});
	
	jQuery(".mwb_wgm_other_setting_remove_background_span").click(function(){
		jQuery("#mwb_wgm_other_setting_remove_background").hide();
		jQuery("#mwb_wgm_other_setting_background_logo_value").val("");
		
	});
	
	jQuery("#mwb_wgm_manage_template").click(function(){
		jQuery("#mwb_wgm_manage_template_wrapper").slideToggle();
	});
	
	jQuery("#mwb_wgm_mail_setting").click(function(){
		jQuery("#mwb_wgm_mail_setting_wrapper").slideToggle();
	});

  jQuery("#mwb_wgm_coupon_mail_setting").click(function(){
    jQuery("#mwb_wgm_coupon_mail_setting_wrapper").slideToggle();
  });
	
	jQuery("#mwb_wgm_general_setting_giftcard_payment").select2();
	
	jQuery("#mwb_wgm_offline_gift_preview").click(function(){
		var error = true;
		var to_mail = jQuery("#mwb_wgm_offline_gift_to").val().trim();
		var from_mail = jQuery("#mwb_wgm_offline_gift_from").val().trim();
		var price = jQuery("#mwb_wgm_offline_gift_amount").val().trim();
		var message = jQuery("#mwb_wgm_offline_gift_message").val().trim();
		var product_id = jQuery("#mwb_wgm_offline_gift_template").val();
    var gift_manual_code = jQuery("#mwb_wgm_offline_gift_coupon_manual").val();
		
		
		if(price == null || price == "")
    {
    	error = false;
    	jQuery("#mwb_wgm_offline_gift_amount").addClass("mwb_wgm_error");
    }
		else
		{
			jQuery("#mwb_wgm_offline_gift_amount").removeClass("mwb_wgm_error");
		}	
        
        if(to_mail == null || to_mail == "")
        {
        	error = false;
        	jQuery("#mwb_wgm_offline_gift_to").addClass("mwb_wgm_error");
        }
        else
    		{
    			jQuery("#mwb_wgm_offline_gift_to").removeClass("mwb_wgm_error");
    		}
        if(from_mail == null || from_mail == "")
        {
        	error = false;
        	jQuery("#mwb_wgm_offline_gift_from").addClass("mwb_wgm_error");
        }
        else
    		{
    			jQuery("#mwb_wgm_offline_gift_from").removeClass("mwb_wgm_error");
    		}
        if(message == null || message == "")
        {
        	error = false;
        	jQuery("#mwb_wgm_offline_gift_message").addClass("mwb_wgm_error");
        	
        }
        else
        {
        	jQuery("#mwb_wgm_offline_gift_message").removeClass("mwb_wgm_error");
        }	
        
        if(product_id == null || product_id == "")
        {
        	error = false;
        	jQuery("#mwb_wgm_offline_gift_template").addClass("mwb_wgm_error");
        	
        }
        else
        {
        	jQuery("#mwb_wgm_offline_gift_template").removeClass("mwb_wgm_error");
        }	
        var send_date = $("#mwb_wgm_offline_gift_schedule").val();
        if(error)
        {
        	var data = {
				      action:'mwb_wgm_preview_mail',
  					  price:price,
  					  to:to_mail,
  					  from:from_mail,
  					  message:message,
  					  product_id:product_id,
              send_date:send_date,
              gift_manual_code:gift_manual_code
				   };
      	
      	$.ajax({
  			url: mwb_wgm.ajaxurl, 
  			type: "POST",  
  			data: data,
  			success: function(response) 
  			{
  				
  				$("#mwg_wgm_preview_email").show();
  				tb_show("", response);
  			}
  		});
        }	
        
		
	});
	
	jQuery(".mwb_wgm_offline_resend_mail").click(function(){
		
		jQuery("#mwb_wgm_loader").show();
		var id = jQuery(this).data("id");
		var current = jQuery(this);
		var data = {
			      action:'mwb_wgm_offline_resend_mail',
			      id:id,
				};
		$.ajax({
  			url: mwb_wgm.ajaxurl, 
  			type: "POST",  
  			data: data,
        dataType: 'json',
  			success: function(response) 
  			{
  				jQuery("#mwb_wgm_loader").hide();
          if(response.result == true)
          {
            var message = response.message;
            var html = '<b style="color:green;">'+message+'</b>';
          } 
          else
          {
            var message = response.message;
            var html = '<b style="color:red;">'+message+'</b>';
            
          }
          current.next().html(html);

  			}
  		});
	});
	jQuery("#mwb_wgm_offline_gift_save").click(function(e){
		
		var error = true;
		var to_mail = jQuery("#mwb_wgm_offline_gift_to").val();
		var from_mail = jQuery("#mwb_wgm_offline_gift_from").val();
		var price = jQuery("#mwb_wgm_offline_gift_amount").val();
		var message = jQuery("#mwb_wgm_offline_gift_message").val();
		var product_id = jQuery("#mwb_wgm_offline_gift_template").val();
		if(price == null || price == ""){
      	error = false;
      	jQuery("#mwb_wgm_offline_gift_amount").addClass("mwb_wgm_error");
    }
		else{
			jQuery("#mwb_wgm_offline_gift_amount").removeClass("mwb_wgm_error");
		}	
    if(to_mail == null || to_mail == ""){
    	error = false;
    	jQuery("#mwb_wgm_offline_gift_to").addClass("mwb_wgm_error");
    }
    else{
			jQuery("#mwb_wgm_offline_gift_to").removeClass("mwb_wgm_error");
		}
    if(from_mail == null || from_mail == ""){
    	error = false;
    	jQuery("#mwb_wgm_offline_gift_from").addClass("mwb_wgm_error");
    }
    else{
			jQuery("#mwb_wgm_offline_gift_from").removeClass("mwb_wgm_error");
		}
    if(message == null || message == ""){
    	error = false;
    	jQuery("#mwb_wgm_offline_gift_message").addClass("mwb_wgm_error");
    	
    }
    else
    {
    	jQuery("#mwb_wgm_offline_gift_message").removeClass("mwb_wgm_error");
    }	   
    if(product_id == null || product_id == ""){
    	error = false;
    	jQuery("#mwb_wgm_offline_gift_template").addClass("mwb_wgm_error");
    	
    }
    else{
    	jQuery("#mwb_wgm_offline_gift_template").removeClass("mwb_wgm_error");
    }
    /*if(mwb_manual_code !== null){

    }*/
    if(!error){
    	e.preventDefault();
    }	
    });
  $('#mwb_wgm_offline_gift_schedule').datepicker({
     dateFormat : mwb_wgm.dateformat,
      minDate: 0
  }).datepicker("setDate", "0");
  /*$('#TB_closeWindowButton').click(function(){

  });*/
 
 $("#TB_closeWindowButton").click(function() {
    alert();
});
  jQuery('#mwb_wgm_offline_gift_coupon_manual').on('change',function(){
   var mwb_manual_code = jQuery("#mwb_wgm_offline_gift_coupon_manual").val();
   var html_err = '<span style="color:red;">Gift Coupon Code already exist! Try another</span>';
   var html_succ = '<span style="color:green;">Valid Code</span>';
   if(mwb_manual_code !== null){
     jQuery.ajax({
           url:mwb_wgm.ajaxurl,
           type:"POST",
           dataType :'json',
           data:{
             action:'mwb_wgm_check_manual_code_exist',
             mwb_manual_code:mwb_manual_code
           },success : function(response){
             if(response.result == 'invalid'){
               $("#mwb_wgm_invalid_code_notice").html(html_err);
             }
             else if(response.result == 'valid'){
               $("#mwb_wgm_invalid_code_notice").html(html_succ);
             }
           }
         });
   }
 });
});
//WP-POINTER
jQuery(document).ready(function(){
  var mwb_wgm_pointer_1 = mwb_wgm.pointer;
  if(mwb_wgm_pointer_1.pointers_generate_1){
    
    jQuery.each(mwb_wgm_pointer_1.pointers_generate_first, function( i ) {
      show_wp_pointer_gen( i );
      return false;
    });
  }

  function show_wp_pointer_gen( id ){
    var pointer = mwb_wgm.pointer.pointers_generate_first[ id ];
    var options = jQuery.extend( pointer.options, {
      close: function() {
        if ( pointer.next ) {
          show_wp_pointer_gen( pointer.next );
        }

        else
        {
          jQuery.ajax({
            url:mwb_wgm.ajaxurl,
            type:"POST",
            data:{
              action:'mwb_wgm_wp_pointer_end_section',
              mwb_wgm_finish:'finish'
            },success : function(response){

            }
          });
        }
      }
    } );

    var this_pointer = jQuery(pointer.target ).pointer( options );
    this_pointer.pointer( 'open' );

    jQuery( 'a.close' ).html('<a class="button-primary" href="#">Next</a>');
    }
});
