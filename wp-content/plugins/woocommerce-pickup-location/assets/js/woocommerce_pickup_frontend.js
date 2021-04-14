jQuery(document).ready(function($) {
    jQuery.wppkpo_actions = {
        init: function() {
            setTimeout(function() {
                if (jQuery('select[name="pickup_point_name"]').length > 0)
                    //jQuery("#wp_pkpo_form_select").select2();

                jQuery('.pickup_point_date_time').datepicker({
                    dateFormat: wp_pkpo_date_formate,
                    changeMonth: true,
                    changeYear: true
                }, $.datepicker.regional[wp_pkpo_datepicker_lang]);

                if (wp_pkpo_timepicker_formate === '24')
                    jQuery('input[name="pickup_point_time"]').timepicker({
                        'timeFormat': 'H:i:s'
                    });
                else
                    jQuery('input[name="pickup_point_time"]').timepicker({
                        'timeFormat': 'h:i A'
                    });

            }, 2000);

            jQuery(document).on('click', '.pickup_point_name_radio', function() {
                jQuery('.pickup_point_date_time').val('');
                jQuery('.pickup_point_time').val('');
                if (wp_pkpo_map_status === 'enable') {
                    closeAllInfoWindows();
                    var wp_pkpo_index = jQuery(this).attr('loop_index');
                    if (wp_pkpo_index) {
                        google.maps.event.trigger(gmarkers[wp_pkpo_index], "click");
                        var laLatLng = new google.maps.LatLng(gmarkers[wp_pkpo_index].getPosition().lat(), gmarkers[wp_pkpo_index].getPosition().lng());
                        map.panTo(laLatLng);
                        map.setZoom(12);
                    }
                }
                 jQuery('#wp_pickup_id').val(jQuery('.pickup_point_name_radio:checked').attr('pickup_id'));
                 jQuery('th.pick_up_name').text(jQuery('.pickup_point_name_radio:checked').attr('wp_pkpo_title'));
            });

            jQuery(document).on('change', '.pickup_point_name_select', function() {
                jQuery('.pickup_point_date_time').val('');
                jQuery('.pickup_point_time').val('');
                if (wp_pkpo_map_status === 'enable') {
                    closeAllInfoWindows();
                    var wp_pkpo_index = jQuery('.pickup_point_name_select :selected').attr('loop_index');
                    if (wp_pkpo_index) {
                        google.maps.event.trigger(gmarkers[wp_pkpo_index], "click");
                        var laLatLng = new google.maps.LatLng(gmarkers[wp_pkpo_index].getPosition().lat(), gmarkers[wp_pkpo_index].getPosition().lng());
                        map.panTo(laLatLng);
                        map.setZoom(12);
                    }
                }
                jQuery('#wp_pickup_id').val(jQuery('.pickup_point_name_select :selected').attr('id'));
                jQuery('th.pick_up_name').text(jQuery('.pickup_point_name_select :selected').text());
            });

            jQuery(document).on('change', '.pickup_point_date_time', function() {
                var off_days, min_time, max_time, time_selected = "";
                if (jQuery('select[name="pickup_point_name"]').length > 0){
                    off_days = jQuery('.pickup_point_name_select :selected').attr('wp_pkpo_working_days');
                    min_time = jQuery('.pickup_point_name_select :selected').attr('wp_pkpo_min_time');
                    max_time = jQuery('.pickup_point_name_select :selected').attr('wp_pkpo_max_time');
                }
                if (jQuery('input[name="pickup_point_name"]').length > 0){
                    off_days = jQuery('input[name="pickup_point_name"]:checked').attr('wp_pkpo_working_days');
                    min_time = jQuery('input[name="pickup_point_name"]:checked').attr('wp_pkpo_min_time');
                    max_time = jQuery('input[name="pickup_point_name"]:checked').attr('wp_pkpo_max_time');
                }
                if (jQuery('input[name="pickup_point_time"]').length > 0){
                    time_selected = jQuery('input[name="pickup_point_time"]').val();
                }

                $.ajax({
                    method: 'POST',
                    url: admin_url,
                    data: {
                        action: 'check_valid_date',
                        off_days: off_days,
                        min_time: min_time,
                        max_time: max_time,
                        time_selected: time_selected,
                        date_selected: jQuery(this).val()
                    },
                    success: function(data) {
                        var output = JSON.parse(data);
                        if (output.result === 'false') {
                            alert(output.error_message);
                            jQuery('.pickup_point_date_time').val('');
                        }
                    }
                });

            });
            
            jQuery(document).on('click', '#place_order', function (evt) {
               
                if (jQuery('select[name="pickup_point_name"]').length > 0 && jQuery('select[name="pickup_point_name"]').val() == -1) {
                    alert(wp_pickup_pickup_msg);
                    evt.preventDefault();
                    return false;
                }
                else if (jQuery('input[name="pickup_point_name"]').length > 0 && jQuery("input[name='pickup_point_name']:checked").length<=0){
                    alert(wp_pickup_pickup_msg);
                    evt.preventDefault();
                    return false;
                }
                else if (jQuery('input[name="pickup_point_date_time"]').val().length <= 0){
                    alert(wp_pickup_date_msg);
                    evt.preventDefault();
                    return false;
                }
                else if (jQuery('input[name="pickup_point_time"]').val().length <= 0 ){
                    alert(wp_pickup_time_msg);
                    evt.preventDefault();
                    return false;
                }
            });

            jQuery(document).on('change', '.pickup_point_time', function() {
                var open_time, close_time , min_time, max_time,date_selected = "";
                if (jQuery('select[name="pickup_point_name"]').length > 0) {
                    open_time = jQuery('.pickup_point_name_select :selected').attr('wp_pkpo_opening_time');
                    close_time = jQuery('.pickup_point_name_select :selected').attr('wp_pkpo_closing_time');
                    min_time = jQuery('.pickup_point_name_select :selected').attr('wp_pkpo_min_time');
                    max_time = jQuery('.pickup_point_name_select :selected').attr('wp_pkpo_max_time');
                }
                if (jQuery('input[name="pickup_point_name"]').length > 0) {
                    open_time = jQuery('input[name="pickup_point_name"]:checked').attr('wp_pkpo_opening_time');
                    close_time = jQuery('input[name="pickup_point_name"]:checked').attr('wp_pkpo_closing_time');
                    min_time = jQuery('input[name="pickup_point_name"]:checked').attr('wp_pkpo_min_time');
                    max_time = jQuery('input[name="pickup_point_name"]:checked').attr('wp_pkpo_max_time');
                }
                if (jQuery('input[name="pickup_point_date_time"]').length > 0){
                    date_selected = jQuery('input[name="pickup_point_date_time"]').val();
                }

                $.ajax({
                    method: 'POST',
                    url: admin_url,
                    data: {
                        action: 'check_valid_time',
                        open_time: open_time,
                        close_time: close_time,
                        min_time: min_time,
                        max_time: max_time,
                        date_selected: date_selected,
                        time_selected: jQuery(this).val()                       
                    },
                    success: function(data) {
                        var output = JSON.parse(data);
                        if (output.result === 'false') {
                            alert(output.error_message);
                            jQuery('.pickup_point_time').val('');
                        }
                    }
                });

            });
        }
    }

    jQuery.wppkpo_actions.init();
});