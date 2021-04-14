<script type="text/javascript">
    var map;
    var markers = [];
    var infoWindow;
    var wp_pickup_location_select;
    var wp_pickup_store_select;
    var default_zoom = 5;
    var gmarkers = [];
    var wp_pickup_search_box;

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
                fullscreenControl: true,
                center: new google.maps.LatLng(default_lat_settings, default_lang_settings),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true
            });
        }
        else
        {
            map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: default_zoom,
                fullscreenControl: true,
                center: lat_lng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true
            });
        }
    
        infoWindow = new google.maps.InfoWindow();
        wp_pickup_search_box = new google.maps.places.SearchBox(document.getElementById('pac-input'));
        wp_pickup_search_btn = document.getElementById("wp_pickup_search_btn").onclick = searchLocations;
        wp_pickup_location_select = document.getElementById("wp_pickup_location_select");
        wp_pickup_store_select = document.getElementById("wp_pkpo_form_select");
        wp_pickup_location_select.onchange = function () {
            var markerNum = wp_pickup_location_select.options[wp_pickup_location_select.selectedIndex].value;
            if (markerNum != "none") {
                google.maps.event.trigger(markers[markerNum], 'click');
            }
        };
    }
    
    initialize();
    
    function closeAllInfoWindows() {
        for (var i = 0; i < infoWindow.length; i++) {
            infoWindow[i].close();
        }
    }

    function bindInfoWindow(marker, map, infowindow, html) {
        closeAllInfoWindows();
        marker.addListener('click', function () {
        infowindow.setContent(html);
        infowindow.open(map, this);
        var point_name = jQuery('.pick_point:last').attr('point_name');
        var pick_id = jQuery('.pick_point:last').attr('pick_id');
        jQuery('.pick_up_name').text(point_name);
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

    function searchLocations() {
        var address = document.getElementById("pac-input").value;
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({address: address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                searchLocationsNear(results[0].geometry.location, "near");
            } else {
                alert(address + ' not found');
            }
        });
    }

    function clearLocations() {
        infoWindow.close();
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers.length = 0;
        wp_pickup_location_select.innerHTML = "";
        wp_pickup_store_select.innerHTML = "";
        var option = document.createElement("option");
        option.value = "none";
        option.innerHTML = "See all results:";
        wp_pickup_location_select.appendChild(option);
    }

    function searchLocationsNear(center, search_type) {
        clearLocations();
        var radius = document.getElementById('wp_pickup_radius').value;
        var site_url = "<?php echo site_url() ?>";
        var searchUrl = "";
        if(search_type === "near")
            searchUrl = site_url + '/wp-admin/admin-ajax.php?action=neareststores&lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
        else
            searchUrl = site_url + '/wp-admin/admin-ajax.php?action=neareststores';
        downloadUrl(searchUrl, function (data) {
            var xml = parseXml(data);
            var markerNodes = xml.documentElement.getElementsByTagName("marker");
            if (markerNodes.length == 0) {
                map.setCenter(new google.maps.LatLng(center.lat(), center.lng()), default_zoom);
                return;
            }
           
            var bounds = new google.maps.LatLngBounds();
            var store_options = "";
            for (var i = 0; i < markerNodes.length; i++) {
                var id = markerNodes[i].getAttribute("id");
                var name = markerNodes[i].getAttribute("name");
                var address = markerNodes[i].getAttribute("address");
                var distance = parseFloat(markerNodes[i].getAttribute("distance"));
                var post_id = markerNodes[i].getAttribute("post_id");
                var wp_pkpo_opening_hours = markerNodes[i].getAttribute("wp_pkpo_opening_hours");
                var wp_pkpo_closing_hours = markerNodes[i].getAttribute("wp_pkpo_closing_hours");
                var wp_pkpo_min_time = markerNodes[i].getAttribute("wp_pkpo_min_time");
                var wp_pkpo_max_time = markerNodes[i].getAttribute("wp_pkpo_max_time");
                var wp_pkpo_working_days = markerNodes[i].getAttribute("wp_pkpo_working_days");
                var latlng = new google.maps.LatLng(
                parseFloat(markerNodes[i].getAttribute("lat")),
                parseFloat(markerNodes[i].getAttribute("lng")));
                createOption(name, distance, i);
                createMarker(latlng, name, address, post_id);
                bounds.extend(latlng);
                store_options += create_store_options(post_id, name, wp_pkpo_opening_hours, wp_pkpo_closing_hours, wp_pkpo_min_time, wp_pkpo_max_time, wp_pkpo_working_days);
            }
            map.fitBounds(bounds);
            map.setZoom(default_zoom);
            wp_pickup_location_select.style.visibility = "visible";
            wp_pickup_location_select.onchange = function () {
                var markerNum = wp_pickup_location_select.options[wp_pickup_location_select.selectedIndex].value;
                google.maps.event.trigger(markers[markerNum], 'click');
            };
        });
    }

    function createMarker(latlng, name, address, pickup_id) {
        var location_content = jQuery('#wp_pkpo_content_<?php echo $wp_pkpo_pickup->ID; ?>').html();
        if(location_content === '')
            location_content = address;
        name =name.replace(/"/g, "'");
        var html = 
                "<div id='wp_pickup_map_content' class='pick_point pickup_content iw-container' point_name='"+name+"' pick_id='"+pickup_id+"'>" +
                "<div class='iw-title'>"+name+"</div>" +
                "<div class='iw-content'>" +
                "<div class='iw-subTitle'>" + location_content + "</div>" +
                "</div>" +
                "<div class='iw-bottom-gradient'></div>" +
                '</div>';
        
       var marker_icon_url = "<?php echo WPPKPO_PLUGIN_URL; ?>/assets/images/marker.png";  
       
       <?php if(isset($wp_pkpo_marker_image) && !empty($wp_pkpo_marker_image)) { ?>
           marker_icon_url = "<?php echo $wp_pkpo_marker_image; ?>";
       <?php } ?>
           
       var icon = {
                    url: marker_icon_url, // url
                    scaledSize: new google.maps.Size(45, 45), // scaled size
                    origin: new google.maps.Point(0, 0), // origin
                    anchor: new google.maps.Point(0, 0) // anchor
                  };
                  
        var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            icon: icon
        });
        
        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
        });
        
        google.maps.event.addListener(infoWindow, 'domready', function () {

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
        
        bindInfoWindow(marker, map, infoWindow, html, latlng);
        markers.push(marker);
    }

    function createOption(name, distance, num) {
        var option = document.createElement("option");
        option.value = num;
        option.innerHTML = name;
        wp_pickup_location_select.appendChild(option);
    }

    function create_store_options(post_id, name, wp_pkpo_opening_hours, wp_pkpo_closing_hours, wp_pkpo_min_time, wp_pkpo_max_time, wp_pkpo_working_days) {
        var option = document.createElement("option");
        option.value = name;
        var time_Str = "";
        if (wp_pkpo_opening_hours)
            time_Str = " (" + wp_pkpo_opening_hours + ") To " + "(" + wp_pkpo_closing_hours + ")"
        option.innerHTML = name + time_Str;

        var post_id_att = document.createAttribute("id");
        post_id_att.value = post_id;
        option.setAttributeNode(post_id_att);

        var wp_pkpo_opening_hours_att = document.createAttribute("wp_pkpo_opening_time");
        wp_pkpo_opening_hours_att.value = wp_pkpo_opening_hours;
        option.setAttributeNode(wp_pkpo_opening_hours_att);

        var wp_pkpo_closing_hours_att = document.createAttribute("wp_pkpo_closing_time");
        wp_pkpo_closing_hours_att.value = wp_pkpo_closing_hours;
        option.setAttributeNode(wp_pkpo_closing_hours_att);

        var wp_pkpo_min_time_att = document.createAttribute("wp_pkpo_min_time");
        wp_pkpo_min_time_att.value = wp_pkpo_min_time;
        option.setAttributeNode(wp_pkpo_min_time_att);

        var wp_pkpo_max_time_att = document.createAttribute("wp_pkpo_max_time");
        wp_pkpo_max_time_att.value = wp_pkpo_max_time;
        option.setAttributeNode(wp_pkpo_max_time_att);

        var wp_pkpo_working_days_att = document.createAttribute("wp_pkpo_working_days");
        wp_pkpo_working_days_att.value = wp_pkpo_working_days;
        option.setAttributeNode(wp_pkpo_working_days_att);

        wp_pickup_store_select.appendChild(option);
    }

    function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

        request.onreadystatechange = function () {
            if (request.readyState == 4) {
                request.onreadystatechange = doNothing;
                callback(request.responseText, request.status);
            }
        };

        request.open('GET', url, true);
        request.send(null);
    }

    function parseXml(str) {
        if (window.ActiveXObject) {
            var doc = new ActiveXObject('Microsoft.XMLDOM');
            doc.loadXML(str);
            return doc;
        } else if (window.DOMParser) {
            return (new DOMParser).parseFromString(str, 'text/xml');
        }
    }

    function doNothing() {
    }
</script>