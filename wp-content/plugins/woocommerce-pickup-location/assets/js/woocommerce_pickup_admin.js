var gmarkers = [];

function initMap() {

    if (document.getElementById('pickup_map'))
    {
        var lat_lng = {
            lat: 20.5937, 
            lng: 75.8577
        };
        var default_zoom = 3;
        var default_lat_settings = document.getElementById('wp_pkpo_settings_lat').value;
        var default_lang_settings = document.getElementById('wp_pkpo_settings_lang').value;
        var default_zoom_settings = document.getElementById('wp_pkpo_pickup_admin_map_zoom').value;
               
        if(jQuery.trim(default_zoom_settings)!=='')
            default_zoom = parseInt(default_zoom_settings);

        if (default_lat_settings !== '' && default_lang_settings !== '')
        {
            map = new google.maps.Map(document.getElementById('pickup_map'), {
                zoom: default_zoom,
                center: new google.maps.LatLng(default_lat_settings, default_lang_settings),
                mapTypeId: google.maps.MapTypeId.TERRAIN
            });
        }
        else
        {
            map = new google.maps.Map(document.getElementById('pickup_map'), {
                zoom: default_zoom,
                center: lat_lng,
                mapTypeId: google.maps.MapTypeId.TERRAIN
            });
        }
        var wp_pickup_search_box = new google.maps.places.SearchBox(document.getElementById('pac-input'));
        google.maps.event.addListener(wp_pickup_search_box, 'places_changed', function () {
            wp_pickup_search_box.set('map', null);
            var places = wp_pickup_search_box.getPlaces();

            var bounds = new google.maps.LatLngBounds();
            var i, place;
            for (i = 0; place = places[i]; i++) {
                (function (place) {
                    var marker = new google.maps.Marker({
                    });
                    marker.bindTo('map', wp_pickup_search_box, 'map');
                    google.maps.event.addListener(marker, 'map_changed', function () {
                        if (!this.getMap()) {
                            this.unbindAll();
                        }
                    });
                    bounds.extend(place.geometry.location);


                }(place));

            }
            map.fitBounds(bounds);
            wp_pickup_search_box.set('map', map);
            map.setZoom(Math.min(map.getZoom(), 12));

        });
        var wp_pkpo_lat = jQuery.trim(jQuery('input[name="wp_pkpo_lat"]').val());
        var wp_pkpo_long = jQuery.trim(jQuery('input[name="wp_pkpo_long"]').val());

        if (wp_pkpo_lat !== '' && wp_pkpo_long !== '')
        {
            var latlng = new google.maps.LatLng(wp_pkpo_lat, wp_pkpo_long);

            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: 'Select Pickup locations!'
            });

            gmarkers.push(marker);
        }

        // This event listener will call addMarker() when the map is clicked.  
        map.addListener('click', function (event) {
            jQuery('input[name="wp_pkpo_lat"]').val(event.latLng.lat());
            jQuery('input[name="wp_pkpo_long"]').val(event.latLng.lng());
            removeMarkers();
            var marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
                title: 'Select Pickup locations!'
            });

            gmarkers.push(marker);

        });
    }
}

function removeMarkers() {
    for (i = 0; i < gmarkers.length; i++) {
        gmarkers[i].setMap(null);
    }
}


jQuery(document).ready(function () {

    initMap();

    jQuery.wppkpo_actions = {
        init: function () {

            jQuery(document).on('click', '.wp_pkpo_add', function () {
                var clone_data = jQuery('.wp_pkpo_clone_row:first').clone();
                clone_data.find('input').val('');
                jQuery('#wp_pkpo_pickup_point_details').append(clone_data);
            });

            jQuery(document).on('click', '.wp_pkpo_delete_image', function () {
                jQuery(this).parents('tr.wp_pkpo_clone_row').remove();
            });
            try {
                if (wp_pkpo_pickup_js_var.wp_pkpo_timepicker_formate === '24')
                {
                    jQuery('#wp_pkpo_opening_time').timepicker({
                        'timeFormat': 'H:i:s'
                    });

                    jQuery('#wp_pkpo_closing_time').timepicker({
                        'timeFormat': 'H:i:s'
                    });
                }
                else
                {
                    jQuery('#wp_pkpo_opening_time').timepicker({
                        'timeFormat': 'h:i A'
                    });

                    jQuery('#wp_pkpo_closing_time').timepicker({
                        'timeFormat': 'h:i A'
                    });
                }
            }
            catch (err) {

            }
        }
    }
    
    jQuery.wppkpo_actions.init();

});