<?php

//ini_set('display_errors', 1); 
//error_reporting(E_ALL);

require(dirname(__FILE__,4).'/wp-load.php');
//require(dirname(__FILE__,4).'/__wp__/wp-blog-header.php');

if( isset($_POST['funcion']) ) {
  if($_POST['funcion'] == "calcularPrecio"){
    calcularPrecio( 
        $_POST['tipoEnvio'],
        $_POST['kilometros'],
        $_POST['latlngDest']
      );
  }
  else{
    if ($_POST['funcion'] == "motorizado") {
      # code...
    }
  }
} else {
  //die("Solicitud no válida.");
  //$woocommerce;
  //
  //var_dump(scandir(dirname(__FILE__,4).'/__wp__/wp-blog-header.php'));
  var_dump(WC()->session->get( 'shipping_calculated_cost_chazki'));
  //$woocommerce_insta = $woocommerce->session->set( 'shipping_calculated_cost', $shipping_cost );
}

/* Tipo Envio 
      1 Express
      2 Regular
      3 Programado
*/




function calcularPrecio( $tipoEnvio, $kilometros, $latlngDest) {
  //if($tipoEnvio == 1){
      if($kilometros<=4.5){
                WC()->session->set( 'shipping_calculated_cost_chazki_express', 7.2 );            
            }else{
                $distanciaaux = 1.13*ceil($kilometros-4.5);
               WC()->session->set( 'shipping_calculated_cost_chazki_express', 7.2+$distanciaaux );    
            }
  //}
  //else//if ($tipoEnvio == 2)
  //{
	  
      if($kilometros<=5){
                WC()->session->set( 'shipping_calculated_cost_chazki_regular', 7.2 );      
            }elseif($kilometros<=10){
                WC()->session->set( 'shipping_calculated_cost_chazki_regular', 9 );       
            }elseif($kilometros<=20){
                WC()->session->set( 'shipping_calculated_cost_chazki_regular', 11.7 );   
            }elseif($kilometros<=30){
                WC()->session->set( 'shipping_calculated_cost_chazki_regular', 14.4 );     
            }else{
                $distanciaaux = 1.13*ceil($kilometros-30);
                WC()->session->set( 'shipping_calculated_cost_chazki_regular', 14.4+$distanciaaux );   
            }
  //}
  /*else{
    //Tipo envio es 3
    if($kilometros<=5){
                WC()->session->set( 'shipping_calculated_cost_chazki', 9 );   
            }elseif($kilometros<=10){
                WC()->session->set( 'shipping_calculated_cost_chazki', 11.25 );   
            }elseif($kilometros<=20){
                WC()->session->set( 'shipping_calculated_cost_chazki', 14.63 );      
            }elseif($kilometros<=30){
                WC()->session->set( 'shipping_calculated_cost_chazki', 18 );         
            }else{
                $distanciaaux = 1.13*ceil($kilometros-30);
                WC()->session->set( 'shipping_calculated_cost_chazki', 18+$distanciaaux );   
            }
  }*/

  
        

  //$respuesta = ["Respuestas",$idEx,$idNext,$responseprecios["prices"][$idEx]["price"],$responseprecios["prices"][$idNext]["price"], $errorq];
  header('Content-type: application/json; charset=utf-8');
    //$respuesta = ["llave","asds"];
  /**/
  //echo WC()->session->get( 'latlngchazki');
  echo json_encode( $latlngDest , JSON_FORCE_OBJECT);
    //echo json_encode(custom_shipping_costs($rates), JSON_FORCE_OBJECT);
  
}



function motorizado( $tipoEnvio, $kilometros) {
  

  
        

  //$respuesta = ["Respuestas",$idEx,$idNext,$responseprecios["prices"][$idEx]["price"],$responseprecios["prices"][$idNext]["price"], $errorq];
  header('Content-type: application/json; charset=utf-8');
    //$respuesta = ["llave","asds"];
  /**/
  echo WC()->session->get( 'shipping_calculated_cost_chazki');
  //echo WC()->session->get( 'shipping_calculated_cost_chazki');//json_encode( $respuesta, JSON_FORCE_OBJECT);
    //echo json_encode(custom_shipping_costs($rates), JSON_FORCE_OBJECT);
  
}


exit();
