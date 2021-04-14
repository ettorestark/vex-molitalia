
<div id="map" class="map" style="height: 500px;width: 500px">
  
</div>

<?php
require 'Polyline.php';
$encoded = array(
  'r~niAbvbuMx@md@uSuMiVlUoDsCo[iQfNa[md@kZaGzFuGd@}Jk@U?_G`BiEbOgIjH~CtFaE~GVnDqB`JsSpMsGtDg@dAaBtAy@l@gR}\\}AkD}BiEy@eBeAwBwAuCuAsCjThc@lMlUoC|EeEjDwDvCvChD?CeBtAoDjC|BpES~CaBtAoBgA{FzSeHtRaKhHoP{NqAkRnFkPahApAcTwQsaBgbEadAawBtI}j@tSwEnHnB|EnG|IdWsD`T|PBbX_IfMI|HcBxa@e]td@mj@|a@ef@nDdDcDhI`QtF~_AnNqC|UfG|_@`I_ALj@`Jpe@`AAhKxe@vR{BaNjU|AbQ|Q~D`V}UbDpD_Bdt@`CbCrGj@bM~KdAi@xFtDzEd@fDvF~KmJzKfOvm@uWzq@dMd[hQcRnj@cTdn@sU~s@',
  'lavhAd`buMzAz@hDeI_FmC');

$points = array();
for ($i=0; $i < count($encoded); $i++) { 
    array_push($points, Polyline::decode($encoded[$i]));
}

var_dump($points);
echo '<pre>';
$ar = array_chunk($points, 2);
var_dump($ar);
echo '</pre>';
?>

<script>

      // This example creates a 2-pixel-wide red polyline showing the path of
      // the first trans-Pacific flight between Oakland, CA, and Brisbane,
      // Australia which was made by Charles Kingsford Smith.
      var areasLima = <?php echo json_encode($encoded) ?>;
      var arrayPuntos = <?php echo json_encode($ar) ?>;
      function initMap() {
        var latlng = new google.maps.LatLng(-12.0587408, -77.0680588);
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: latlng,
          mapTypeId: 'roadmap'
        });
        //console.log(arrayPuntos);

        function dibujarPolylines(){

            var flightPlanCoordinates = [
            ];

            for (var i = 0; i < arrayPuntos.length; i++) {
              console.log(arrayPuntos[i]);
              flightPlanCoordinates.push({ lat: arrayPuntos[i][0], lng: arrayPuntos[i][1] });
            }
            flightPlanCoordinates.push({ lat: arrayPuntos[0][0], lng: arrayPuntos[0][1] });

            console.log(flightPlanCoordinates);
            var flightPath = new google.maps.Polyline({
              path: flightPlanCoordinates,
              geodesic: true,
              strokeColor: '#FF0000',
              strokeOpacity: 1.0,
              strokeWeight: 2
            });

            flightPath.setMap(map);

        }

        dibujarPolylines();
        
      }
    </script>

 <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDycZSwKf1axWn936tPCMf36HqSwGMp-pg&sensor=false&callback=initMap">
    </script>