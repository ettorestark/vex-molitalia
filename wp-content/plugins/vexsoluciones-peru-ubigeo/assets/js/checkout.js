/* 
 * * Woocommerce Olva Courier
 * * https://www.pasarelasdepagos.com/shop/peru/woocommerce/plugin-woocommerce-olva/
 * *
 * * Copyright (c) 2019 vexsoluciones
 * * Licensed under the GPLv2+ license.
 */

/* Ubigeo Google Map */
var googleMap = {
    mapContainer: null,
    init: function(lat, lgt, mapContainer) {
        var self = this;
        this.mapContainer = mapContainer;
        this.setLat(lat);
        this.setLgt(lgt);
        self.initializeMap();
    },
    setLat: function(lat) {
        this.mapContainer.find(".geo_latitude").val(lat);
    },
    setLgt: function(lgt) {
        this.mapContainer.find(".geo_longitude").val(lgt);
    },
    initializeMap: function() {
        var maps = [];
        var mapInstance = this.mapContainer;
        if (mapInstance.length > 0) {
            var searchInput = mapInstance.find( '.search-input' );
            var mapCanvas = mapInstance.find( '.olva-location-field-map' );
            var latitude = this.mapContainer.find(".geo_latitude");
            var longitude = this.mapContainer.find(".geo_longitude");
            var latLng = new google.maps.LatLng( latitude.val(), longitude.val() );
            var zoom = 14;
            // Map
            var mapOptions = {
                    center: latLng,
                    zoom: zoom
            };
            var map = new google.maps.Map( mapCanvas[0], mapOptions );

            latitude.on('change', function() {
                    map.setCenter( new google.maps.LatLng( latitude.val(), longitude.val() ) );
            });

            longitude.on('change', function() {
                    map.setCenter( new google.maps.LatLng( latitude.val(), longitude.val() ) );
            });

            // Marker
            var markerOptions = {
                    map: map,
                    draggable: true,
                    title: 'Drag to set the exact location'
            };
            var marker = new google.maps.Marker( markerOptions );

            if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
                    marker.setPosition( latLng );
            }

            // Search
            var autocomplete = new google.maps.places.Autocomplete( searchInput[0] );
            autocomplete.bindTo( 'bounds', map );

            google.maps.event.addListener( autocomplete, 'place_changed', function() {
                    var place = autocomplete.getPlace();
                    if ( ! place.geometry ) {
                            return;
                    }

                    if ( place.geometry.viewport ) {
                            map.fitBounds( place.geometry.viewport );
                    } else {
                            map.setCenter( place.geometry.location );
                            map.setZoom( 17 );
                    }

                   marker.setPosition( place.geometry.location );

                    latitude.val( place.geometry.location.lat() );
                    longitude.val( place.geometry.location.lng() );
            });

            searchInput.keypress( function( event ) {
                    if ( 13 === event.keyCode ) {
                            event.preventDefault();
                    }
            });

            // Allow marker to be repositioned
            google.maps.event.addListener( marker, 'drag', function() {
                latitude.val( marker.getPosition().lat() );
                longitude.val( marker.getPosition().lng() );
            });

            maps.push( map );
        }
    }
};

function getGoogleMap() {
    return googleMap;
}

jQuery(document).ready(function($) {
    if (typeof google === 'undefined') {
        let script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3&key=' + wp.googleApiKey + '&sensor=false&callback=initializeMap&libraries=places';
        document.body.appendChild(script);
    }
    /* Olva Shipping */
    var olvaShipping = {
        init: function() {
            var self = this;
            /* Hide/Show Fields */
            $(document.body).on("update_checkout", function() {
                olvaShipping.hideFieldsDependingCountry();
            });
            $("#billing_country, #shipping_country").on("change", function() {
                olvaShipping.hideFieldsDependingCountry();
            });
            this.hideFieldsDependingCountry();
            // hide glovo fields
            $(document.body).on("update_checkout", function() {
                self.hideGlovoFields();
            });
            window.onload = function() {
                self.hideGlovoFields();
            };
            this.hideGlovoFields();
            /* start select2 */
            $("#billing_state, #shipping_state").each(function() {
                $(this).change(function() {
                    self.configureDependingSelectsValues("#"+$(this).attr("id"), this);
                });
                self.configureDependingSelectsValues("#"+$(this).attr("id"), this);
            });
            $.each(wp.checkoutFields, function(fieldName) {
                var fieldSelector = "#" + fieldName;
                if(this.type === "select") {
                    var select = $(fieldSelector)
                        .on("change", function() {
                            self.configureDependingSelectsValues(fieldSelector, this);
                        });
                    if(typeof select.select2 === "function")
                        select.select2();
                    self.configureDependingSelectsValues(fieldSelector, select[0]);
                }
            });
        },
        configureDependingSelectsValues: function(fieldSelector, select) {
            var map = $("#ubigeo_map_field");
            /* Provinces Dynamic Charge */
            if($(select).attr("id").includes("shipping_state") || $(select).attr("id").includes("billing_state")) {
                olvaShipping.chargeUbigeoOptions(wp.provinces, select, "_ubigeo_province");
            } else if($(fieldSelector).attr("id").includes("_ubigeo_province"))
                olvaShipping.chargeUbigeoOptions(wp.districts, select, "_ubigeo_district");
            else if($(fieldSelector).attr("id").includes("_ubigeo_district")) {
                ;
            }
            $(document.body).trigger("update_checkout");  
        },
        chargeUbigeoOptions: function(ubigeos, select, selectSibbling) {
            var ubigeoCode = $(select).val();
            var provinceOrDistrict = "Distrito";
            if(selectSibbling === "_ubigeo_province") {
                ubigeoCode = this.getUbigeoCode(ubigeoCode);
                provinceOrDistrict = "Provincia";
            }
            if(ubigeoCode === 0)
                return;
            var options = '<option value="">Seleccione una '+provinceOrDistrict+'...</option>';
            $.each(ubigeos[ubigeoCode], function() {
                options += '<option value="'+this["id_ubigeo"]+'">'+this["nombre_ubigeo"]+'</option>';
            });
            var field = this.getUbigeoSelect(select, selectSibbling);
            field.html(options);
            if(typeof field.select2 === "function")
                field.select2();
        },
        hideFieldsDependingCountry: function() {
            /* hide/show depending selected country */
            var countries = $("#billing_country, #shipping_country");
            countries.each(function() {
                var billingOrShipping = $(this).attr("id").includes("billing") ? "billing" : "shipping";
                var visibility = wp.country == $(this).val();
                olvaShipping.setFieldsVisible(visibility, billingOrShipping);
                
            });
        },
        setFieldsVisible: function(visibility, billingOrShipping) { // Hide/Show fields depending country and selected shipping method 
            $.each(wp.checkoutFields, function(fieldName) {
                if(fieldName.includes(billingOrShipping)) {
                    var object = $("#" + fieldName + "_field");
                    if(this.type === "select") {
                        if(object.children().find("option").length > 1)
                            object.show();
                        else
                            object.hide();
                    } else {
                        if(visibility) {
                            object.show();
                        } else
                            object.hide();
                    }
                }
            });
        },
        getUbigeoCode: function(key) {
            var code = 0;
            $.each(wp.departaments, function() {
                if(this.woo_state == key) {
                    code = this.id_ubigeo;
                    return false;
                }
            });
            return code;
        },
        getUbigeoSelect(select, restOfSelector) {
            var billingOrShipping = $(select).attr("id").includes("billing") ? "billing" : "shipping";
            return $("#" + billingOrShipping + restOfSelector);
        },
        hideGlovoFields: function() {
            var fields = $(".urbaner-map-template, label[for='delivery-time'], label[for='asap'], #basic_example_1, input[name='timepickerGlovo'], small[for='delivery-time']");
            if($("#shipping_method_0_vex_soluciones_olva").is(":checked")) {
                fields.hide();
                console.log("campos glovo ocultos");
            } else {
                fields.show();
                console.log("campos glovo mostrados");
            }
            
        }
    };
    olvaShipping.init();
    olvaShipping.hideGlovoFields();
});
