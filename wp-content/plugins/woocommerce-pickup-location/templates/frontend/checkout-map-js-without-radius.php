<script type="text/javascript">
    var gmarkers = [];
    var infoWindows = [];
    var marker;
    var default_zoom = 5;

    function initialize()
    {
        var lat_lng = {lat: 40.4637, lng: 3.7492};
        var default_lat_settings = '<?php echo $wp_pkpo_default_lat; ?>';
        var default_lang_settings = '<?php echo $wp_pkpo_default_lang; ?>';

        <?php if (isset($wp_pkpo_pickup_checkout_map_zoom) && ($wp_pkpo_pickup_checkout_map_zoom !== '')) { ?>
            default_zoom = <?php echo $wp_pkpo_pickup_checkout_map_zoom; ?>
        <?php } ?>

        if (default_lat_settings !== '' && default_lang_settings !== '')
        {
            map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: default_zoom,
                zoomControl: true,
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_BOTTOM
                },
                streetViewControl: true,
                center: new google.maps.LatLng(default_lat_settings, default_lang_settings),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true
            });
            jQuery('.gm-control-active').css('bottom','0px');
            jQuery('.gm-control-active').css('top','');
            console.log('Aqui');
        }
        else
        {
            map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: default_zoom,
                center: lat_lng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true
            });
        }

        var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));

        google.maps.event.addListener(map, 'click', function () {
             closeAllInfoWindows();
        });

        google.maps.event.addListener(searchBox, 'places_changed', function () {
            searchBox.set('map', null);
            var places = searchBox.getPlaces();

            var bounds = new google.maps.LatLngBounds();
            var i, place;
            for (i = 0; place = places[i]; i++) {
                (function (place) {
                    var marker = new google.maps.Marker({
                    });
                    marker.bindTo('map', searchBox, 'map');
                    google.maps.event.addListener(marker, 'map_changed', function () {
                        if (!this.getMap()) {
                            this.unbindAll();
                        }
                    });
                    bounds.extend(place.geometry.location);


                }(place));

            }
            map.fitBounds(bounds);
            searchBox.set('map', map);
            map.setZoom(Math.min(map.getZoom(), 12));

        });
        
        <?php if ($wp_pickup_load_pickup_later === "yes") { ?>
                    setTimeout(createMarker(), 2000);
        <?php } else { ?>
                    createMarker();
        <?php } ?>
        jQuery( ".depa" ).change(function() {
          jQuery('#pac-input').val(jQuery('.depa option:selected').text()+' Perú');
          jQuery( "#pac-input" ).select();
          jQuery( "#pac-input" ).focus();
        });
        
    }

    function createMarker() {
        var lat_lang_count = 0;
        <?php
        if (isset($wp_pkpo_pickup_points)) {
            foreach ($wp_pkpo_pickup_points as $wp_pkpo_pickup) {
                $wp_pkpo_lat = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_lat', true);
                $wp_pkpo_long = get_post_meta($wp_pkpo_pickup->ID, 'wp_pkpo_long', true);

                if (trim($wp_pkpo_lat) !== '' && trim($wp_pkpo_long) !== '') {?>
                    var location_content = jQuery('#wp_pkpo_content_<?php echo $wp_pkpo_pickup->ID; ?>').html();
                    var latLng = new google.maps.LatLng(<?php echo $wp_pkpo_lat; ?>, <?php echo $wp_pkpo_long; ?>);
                    var contentString = "<div id='wp_pickup_map_content' class='pick_point pickup_content iw-container' point_name='<?php echo $wp_pkpo_pickup->post_title; ?>' pick_id='<?php echo $wp_pkpo_pickup->ID; ?>'>" +
                            "<div class='iw-title'><?php echo $wp_pkpo_pickup->post_title; ?></div>" +
                            "<div class='iw-content'>" +
                            "<div class='iw-subTitle'>" + location_content + "</div>" +
                            "</div>" +
                            "<div class='iw-bottom-gradient'></div>" +
                            "</div>";

                    <?php if (isset($wp_pkpo_marker_image) && (trim($wp_pkpo_marker_image) != '')) { ?>

                        var icon = {
                            url: "<?php echo $wp_pkpo_marker_image; ?>", // url
                            scaledSize: new google.maps.Size(45, 45), // scaled size
                            origin: new google.maps.Point(0, 0), // origin
                            anchor: new google.maps.Point(0, 0) // anchor
                        };
                        var infowindow = new google.maps.InfoWindow({
                            maxWidth: 350
                        });
                        var marker = new google.maps.Marker({
                            position: latLng,
                            map: map,
                            icon: icon
                        });
                    <?php } else { ?>

                        var infowindow = new google.maps.InfoWindow({
                            maxWidth: 350
                        });

                        var marker = new google.maps.Marker({
                            position: latLng,
                            map: map
                        });

                        marker.setIcon('<?php echo WPPKPO_PLUGIN_URL; ?>/assets/images/marker.png');

                    <?php } ?>

                    google.maps.event.addListener(infowindow, 'domready', function () {

                        var iwOuter = jQuery('.gm-style-iw');

                        var iwBackground = iwOuter.prev();

                        iwBackground.children(':nth-child(2)').css({'display': 'none'});

                        iwBackground.children(':nth-child(4)').css({'display': 'none'});

                        iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': 'rgba(72, 181, 233, 0.6) 0px 1px 6px', 'z-index': '1'});

                        var iwCloseBtn = iwOuter.next();

                        jQuery('.gm-style-iw div:first-child').css('display', 'block');

                        iwCloseBtn.css({opacity: '1', right: '38px', padding: '7px', top: '3px', border: '7px solid #48b5e9', 'border-radius': '13px', 'box-shadow': '0 0 5px #3990B9'});

                        if (jQuery('.iw-content').height() < 140) {
                            jQuery('.iw-bottom-gradient').css({display: 'none'});
                        }

                        iwCloseBtn.mouseout(function () {
                            jQuery(this).css({opacity: '1'});
                        });
                    });


                    bindInfoWindow(marker, map, infowindow, contentString);
                    gmarkers.push(marker);
                    infoWindows.push(infowindow);
                    <?php 
                    if(isset($wp_pkpo_settings['wp_pickup_auto_select_pickup']) && $wp_pkpo_settings['wp_pickup_auto_select_pickup'] === "disable") { ?>
                      map.setCenter(latLng);
                    <?php  } else { ?>
                        google.maps.event.trigger(gmarkers[0], "click");
                    <?php }?>
                   
                    lat_lang_count++;
                    <?php
                }
            }
        }
        ?>
            google.maps.event.addListener(marker, 'click', function () {
                closeAllInfoWindows();
                infowindow.open(map, marker);
            });

            google.maps.event.addListener(map, 'click', function () {
                closeAllInfoWindows();
                infowindow.close();
            });

    }

    function closeAllInfoWindows() {
        for (var i = 0; i < infoWindows.length; i++) {
            infoWindows[i].close();
        }
    }


    function bindInfoWindow(marker, map, infowindow, html) {
        marker.addListener('click', function () {
            closeAllInfoWindows();
            infowindow.setContent(html);
            infowindow.open(map, this);
            var point_name = jQuery('.pick_point:last').attr('point_name');
            var pick_id = jQuery('.pick_point:last').attr('pick_id');
            jQuery('.pick_up_name').text(point_name);
            jQuery('.select2-selection__rendered').text(point_name);
            jQuery('#wp_pickup_id').val(pick_id);
            if (jQuery('select[name="pickup_point_name"]').length > 0)
            {
                var selectedValue = jQuery('select[name="pickup_point_name"] option[id=' + pick_id + ']').attr('value');
                var selectedHtml = jQuery('select[name="pickup_point_name"] option[id=' + pick_id + ']').html();
                jQuery('select[name="pickup_point_name"]').prop('value', selectedValue);
                jQuery('.wp_pkpo_form_select').find('span.select2-chosen').text(selectedHtml);
            }
            if (jQuery('input[name="pickup_point_name"]').length > 0)
            {
                jQuery('input[pickup_id="' + pick_id + '"]').attr('checked', 'checked');
            }
        });
    }
    
    initialize();

</script>