//var authApi = '3909defe7a80b99cd46086b869ee3f7482732903';
var latInit = myScript.latInit;
var lngInit = myScript.longInit;


function toKm(meters){
    return (meters/1000).toFixed(2);
}

function initMap() {
                //google.maps.event.addDomListener(window, "load", initialize);
                var kmToCalculateCost;
                var directionsDisplay = new google.maps.DirectionsRenderer({
                    suppressMarkers: true
                });
                var directionsService = new google.maps.DirectionsService();


                var latlng = new google.maps.LatLng(latInit, lngInit)
                var  map = new google.maps.Map(document.getElementById('map'), {
                  center: latlng,
                  zoom: 12
                });
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(latInit, lngInit),
                    map: map,
                    title: 'Locacion de la tienda'
                });

                 var input = document.getElementById("billing_address_1");
                var autocomplete = new google.maps.places.Autocomplete(input);
                var marker2 = new google.maps.Marker({
                        //position: new google.maps.LatLng(place.geometry["location"].lat(), place.geometry["location"].lng() ),
                        map: map,                        
                        draggable:true,
                        animation: google.maps.Animation.DROP
                        //title: 'Direccion de envio'
                    });
                var markers = [marker, marker2];
                var bounds = new google.maps.LatLngBounds();                

                directionsDisplay.setMap(map);

                autocomplete.addListener("place_changed", function () {
                    var place = autocomplete.getPlace();
                    // place variable will have all the information you are looking for.                    
                    
                    marker2.setPosition(place.geometry.location);
                    //marker2.setVisible(true);

                    kmToCalculateCost = toKm(google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(latInit, lngInit), new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng())));
                    jQuery("#chaskiKm").text(kmToCalculateCost+' km');


                    for (var i = 0; i < markers.length; i++) {
                     bounds.extend(markers[i].getPosition());
                    }

                    map.fitBounds(bounds);       

                    function calculateRoute(){
                        var request = {
                            origin: latlng,
                            destination: place.geometry.location,
                            travelMode: 'DRIVING'
                        };
                        directionsService.route(request, function(result, status){
                            if(status == "OK"){
                                directionsDisplay.setDirections(result);
                            }
                        });

                        jQuery.ajax({
                            type:"POST", 
                            url: myScript.chazkiPlugin + "ajax.php",
                            data:{
                                funcion: "calcularPrecio",
                                tipoEnvio: jQuery("input[type=radio][name='chaskiType']:checked").val(),
                                kilometros: kmToCalculateCost,
                                latlngDest: place.geometry.location.lat()+','+place.geometry.location.lng()
                                }, 
                            success:function(datos){
                                console.log(datos);
                                jQuery('#latlngchazki').val(datos);
                                jQuery('body').trigger('update_checkout');
                             },
                             error: function (xhr, ajaxOptions, thrownError) {
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                              }
                            
                        });
                    }
                    calculateRoute();
                    
                });


                google.maps.event.addListener(marker2, 'dragend', function() 
                {                       var bounds = new google.maps.LatLngBounds();
                    for (var i = 0; i < markers.length; i++) {
                     bounds.extend(markers[i].getPosition());
                    }
                    map.fitBounds(bounds);
                    geocodePosition(marker2.getPosition());

                    kmToCalculateCost = toKm(google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(latInit, lngInit), new google.maps.LatLng(marker2.getPosition().lat(), marker2.getPosition().lng())));
                    jQuery("#chaskiKm").text(toKm(google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(latInit, lngInit), new google.maps.LatLng(marker2.getPosition().lat(), marker2.getPosition().lng()))));

                    function calculateRoute(){
                        var request = {
                            origin: latlng,
                            destination: marker2.getPosition(),
                            travelMode: 'DRIVING'
                        };
                        directionsService.route(request, function(result, status){
                            if(status == "OK"){
                                directionsDisplay.setDirections(result);
                            }
                        });
                        jQuery.ajax({
                            type:"POST", 

                            url: myScript.chazkiPlugin + "ajax.php",
                            data:{
                                funcion: "calcularPrecio",
                                tipoEnvio: jQuery("input[type=radio][name='chaskiType']:checked").val(),
                                kilometros: kmToCalculateCost,
                                latlngDest: marker2.getPosition().lat()+','+marker2.getPosition().lng()
                                }, 
                            success:function(datos){
                                console.log(datos);
                                jQuery('#latlngchazki').val(datos);
                                jQuery('body').trigger('update_checkout');
                             },
                             error: function (xhr, ajaxOptions, thrownError) {
                                console.log(xhr.status);
                                console.log(thrownError);
                                console.log(xhr.responseText);
                              }
                            });
                    }

                    calculateRoute();
                });

                jQuery("input[type=radio][name='chaskiType']").change(function(){
                    try{
                        function calculateRoute(){
                           jQuery.ajax({
                                type:"POST", 
                                url: myScript.chazkiPlugin + "ajax.php",
                                data:{
                                    funcion: "calcularPrecio",
                                    tipoEnvio: jQuery("input[type=radio][name='chaskiType']:checked").val(),
                                    kilometros: kmToCalculateCost,
                                    latlngDest: marker2.getPosition().lat()+','+marker2.getPosition().lng()
                                    }, 
                                success:function(datos){ 
                                     console.log(datos);
                                     jQuery('#latlngchazki').val(datos);
                                     jQuery('body').trigger('update_checkout');
                                 }
                                });
                        }
                        calculateRoute();
                        //var elmnt = document.getElementById("shipping_method_0_urbaner");
                        //                elmnt.scrollIntoView();
                    }
                    catch(err) {
                        alert("Escriba su dirección de entrega y seleccione el punto en el mapa");
                    }                    
                });
                jQuery("input[type=radio][name='type']").change(function(){
                    try {
                        
                          function calculateRoute(){
                           jQuery.ajax({
                                type:"POST", 
                                url: myScript.chazkiPlugin + "get_price_urbaner.php",
                                data:{
                                    funcion: "calculatePrice",
                                    latlngOrigin: latInit+','+lngInit,
                                    latlngDestination: marker2.getPosition().lat()+','+marker2.getPosition().lng(),
                                    vehicle_id: jQuery("input[name='package_type.id']:checked").val(),
                                    return: 'false',
                                    authorization:"token "+authApi,
                                    type: jQuery("input[name='type']:checked").val()
                                    }, 
                                success:function(datos){ 
                                     console.log(datos);
                                     
                                     if(datos.error){    
                                        //alert(datos.error);
                                        jQuery("#type1").prop("disabled", true);
                                        jQuery("#type3").prop("checked", true);
                                        calculateRoute();
                                     }
                                     else{                                        
                                        jQuery("#type1").prop("disabled", false);
                                     }
                                     jQuery('body').trigger('update_checkout');
                                 }
                                });
                        }
                        calculateRoute();
                    }
                    catch(err) {
                      alert("Escriba su dirección de entrega y seleccione el punto en el mapa");
                    }
                    
                });

                //Poner Nombre direccion en el campo de Direccion
                function geocodePosition(pos) 
                {
                   geocoder = new google.maps.Geocoder();
                   geocoder.geocode
                    ({
                        latLng: pos
                    }, 
                        function(results, status) 
                        {
                            if (status == google.maps.GeocoderStatus.OK) 
                            {
                                jQuery("#billing_address_1").val(results[0].formatted_address);
                            } 
                        }
                    );
                }


                

                

            }




function mostrarChazki(latlang1, latlan2)
                {   
                    
                    //explode
                    try{
                            for (var i = 0; i < jQuery('[id^=map]').length; i++) {
                            id = jQuery('[id^=map]').eq(i).attr('id');
                            latlngArray = jQuery('[id^=latlangChazki]').eq(i).val().split(',');
                            latlngInit = new google.maps.LatLng(latlngArray[0], latlngArray[1]);


                            latlngDestArray = jQuery('[id^=latlngDest]').eq(i).val().split(',');                            
                            latlngDest = new google.maps.LatLng(latlngDestArray[0], latlngDestArray[1]);
                            var image = {
                                url: 'https://api.migeodelivery.com/apidoc/img/icons/scooter.png'
                                /* This marker is 20 pixels wide by 32 pixels high.
                                size: new google.maps.Size(20, 32),
                                // The origin for this image is (0, 0).
                                origin: new google.maps.Point(0, 0),
                                // The anchor for this image is the base of the flagpole at (0, 32).
                                anchor: new google.maps.Point(0, 32)*/
                              };
                            var  map = new google.maps.Map(document.getElementById(id), {
                              center: latlngInit,
                              zoom: 12
                            });
                            
                            var marker = new google.maps.Marker({
                                //Indice 0 lat Indice 1 Long
                                position: new google.maps.LatLng(latlngArray[0], latlngArray[1]),
                                map: map,
                                icon: image,
                                title: 'Locacion del CHAZKI'
                            });
                            posInit = new google.maps.LatLng(latlngArray[0], latlngArray[1] );
                            posDest = new google.maps.LatLng(latlngDestArray[0], latlngDestArray[1] );
                            var marker2 = new google.maps.Marker({
                                    //position: new google.maps.LatLng(place.geometry["location"].lat(), place.geometry["location"].lng() ),
                                    map: map,                        
                                    draggable:false,
                                    label:'TU',
                                    animation: google.maps.Animation.DROP
                                    //title: 'Direccion de envio'
                                });
                            marker2.setPosition(posDest);

                            var directionsService = new google.maps.DirectionsService();
                            var directionsDisplay = new google.maps.DirectionsRenderer({
                                suppressMarkers: true
                            });

                            var request = {
                            origin: posInit,
                            destination: posDest,
                            travelMode: 'DRIVING'
                            };
                            directionsService.route(request, function(result, status){
                                if(status == "OK"){
                                    directionsDisplay.setDirections(result);                                    
                            } 
                            else{
                                        console.log(status);
                                    }
                            });                 

                            console.log(latlngArray[0]);
                        }
                    }

                    catch(e){

                    }
                    /*var latlngInit = new google.maps.LatLng(latInit, lngInit)
                    
                    

                    var input = document.getElementById("billing_address_1");
                    var autocomplete = new google.maps.places.Autocomplete(input);
                    var marker2 = new google.maps.Marker({
                            //position: new google.maps.LatLng(place.geometry["location"].lat(), place.geometry["location"].lng() ),
                            map: map,                        
                            draggable:true,
                            animation: google.maps.Animation.DROP
                        });*/
                }

jQuery( document ).ready(function() {
    console.log('Documento cargado');
    mostrarChazki(12,12);
});