<?PHP

namespace vexfacturacionelectronica;

/*
    Esta clase es la que sabe como se deben realizar las operaciones
*/

class vexfeCore{ 

    public static function generarComprobante( $paramsCliente, $paramsPedido, $paramsDetalle ){

        // Buscamos sí ya existe un comprobante para el pedido 

        // Sí existe entonces obtenemos el estado 

        // Sí no ha sido enviado y hay algún problema entonces 

        // Verificamos si el número de seríe es el actual 

        // si NO es el actual entonces eliminamos el comprobante y creamos otro 

        // Sí es el actual entonces solo enviamos el comprobante 
         



        $objConfig = new \vexfacturacionelectronica\configDAO();
        $objSerie = new \vexfacturacionelectronica\serieDAO();
        $objServiceApi = new \vexfacturacionelectronica\serviceFeAPI();

        $objComprobanteDao = new \vexfacturacionelectronica\comprobanteDAO();
        $objComprobanteDetalleDao = new \vexfacturacionelectronica\comprobanteDetalleDAO();


        $configParametros = array();

        $configParametros = $objConfig->get(); 

        if($configParametros['igv'] == '') $configParametros['igv'] = 18;
        if($configParametros['moneda'] == '') $configParametros['moneda'] = 'PEN';
        if($configParametros['unidad_de_medida'] == '') $configParametros['unidad_de_medida'] = 'UNIDAD';
 
        list($serie, $numero) = $objSerie->siguente_numero_serie_comprobante(array('tipo_comprobante' => trim($paramsPedido['tipo_de_comprobante']) ));

        $infoExisteComprobante = $objComprobanteDao->get(['ref_id' => $paramsPedido['ref_id'] ]);

        if( count($infoExisteComprobante) > 0 ){

            list($dataComprobante) = $infoExisteComprobante;

            $dataEstado = $infoExisteComprobante['data_respuesta'];
 
 
        }

      //  var_dump($paramsCliente, $paramsPedido, $paramsDetalle); 

 
        $paramsComprobante = array(
                 'serie' => $serie,
                 'numero' => $numero,
                 'tipo_de_comprobante' => ( trim($paramsPedido['tipo_de_comprobante']) != '' ?  trim($paramsPedido['tipo_de_comprobante']) : '' ),
                 'cliente_email' =>  ( trim($paramsCliente['cliente_email']) != '' ?  trim($paramsCliente['cliente_email']) : '' ),
                 'cliente_tipo_de_documento' => ( trim($paramsCliente['cliente_tipo_de_documento']) != '' ?  trim($paramsCliente['cliente_tipo_de_documento']) : '' ),
                 'cliente_numero_de_documento' => ( trim($paramsCliente['cliente_numero_de_documento']) != '' ?  trim($paramsCliente['cliente_numero_de_documento']) : '' ),
                 'cliente_denominacion' => ( trim($paramsCliente['cliente_denominacion']) != '' ?  trim($paramsCliente['cliente_denominacion']) : '' ),
                 'cliente_direccion' => ( trim($paramsCliente['cliente_direccion']) != '' ?  trim($paramsCliente['cliente_direccion']) : '' ),
                 'ref_tipo' => $paramsPedido['ref_tipo'],
                 'ref_id' =>  $paramsPedido['ref_id'],
                 'fecha_de_emision' => $paramsPedido['fecha_de_emision'], // fecha actual
                 'moneda' => $configParametros['moneda'],
                 'porcentaje_de_igv' => $configParametros['igv'],
                 'total_gravada' => $paramsPedido['total_gravada'],
                 'total_igv' => $paramsPedido['total_igv'],
                 'total' => $paramsPedido['total']
        );
         

        $comprobante_id =  $objComprobanteDao->registrar($paramsComprobante);
 
        foreach ($paramsDetalle as $regDetalle) {


            if($regDetalle['precio_referencial'] == ''){

                $impuestoU = round( $regDetalle['precio_referencial'] *  $configParametros['igv'], 2 );
                $precio_referencial = round($regDetalle['precio_referencial'],2) + $impuestoU;
            }
            else{
                $precio_referencial = $regDetalle['precio_referencial'];
            }


            if($regDetalle['impuesto'] == ''){

                $impuestoST = round( $regDetalle['subtotal'] *  $configParametros['igv'], 2 );

            }
            else{
                $impuestoST = $regDetalle['impuesto'];
            }


            if(($regDetalle['precio_unitario']*1) > 0){

                $params = array('comprobante_id' => $comprobante_id,
                                'detalle' => $regDetalle['detalle'],
                                'cantidad' => ($regDetalle['cantidad'] != '' ? $regDetalle['cantidad'] : '1' ),
                                'precio_unitario' => $regDetalle['precio_unitario'],
                                'precio_referencial' => $precio_referencial,
                                'unidad_medida' => ( $regDetalle['unidad_medida'] == '' ?  $configParametros['unidad_de_medida'] : $regDetalle['unidad_medida']),
                                'subtotal' =>  $regDetalle['subtotal'],
                                'impuesto' =>  $impuestoST,
                                'product_id' =>  $regDetalle['product_id']
                                );

                $objComprobanteDetalleDao->registrar($params);
    
            }

        }

        $objSerie->aumentar_serie_comprobante(array('tipo_comprobante' => trim($paramsPedido['tipo_de_comprobante']) ));
 
        $result = $objServiceApi->enviar_comprobante($comprobante_id, trim($paramsPedido['tipo_de_comprobante']) );

        if($result){

            return $comprobante_id;
        }
        else
        {

           return false;
        }


    }

    /*
        Este método genera los comprobantes, hay que considerar que en caso de fallo el numero de serie se pierde
    */

    public static function generarComprobante_vexfecore( $paramsCliente, $paramsPedido, $paramsDetalle ){


        $objConfig = new configDAO();

        $configInfo = $objConfig->get();

        $configParametros = array();

        $configParametros = $objConfig->get();

        if($configParametros['igv'] == '') $configParametros['igv'] = 18;
        if($configParametros['moneda'] == '') $configParametros['moneda'] = 'PEN';
        if($configParametros['unidad_de_medida'] == '') $configParametros['unidad_de_medida'] = 'UNIDAD';


        $objSerie = new serieDAO();

        list($serie, $numero) = $objSerie->siguente_numero_serie_comprobante(array('tipo_comprobante' => trim($paramsPedido['tipo_de_comprobante']) ));


        $paramsComprobante = array(
                 'serie' => $serie,
                 'numero' => $numero,
                 'tipo_de_comprobante' => ( trim($paramsPedido['tipo_de_comprobante']) != '' ?  trim($paramsPedido['tipo_de_comprobante']) : '' ),
                 'cliente_email' =>  ( trim($paramsCliente['cliente_email']) != '' ?  trim($paramsCliente['cliente_email']) : '' ),
                 'cliente_tipo_de_documento' => ( trim($paramsCliente['cliente_tipo_de_documento']) != '' ?  trim($paramsCliente['cliente_tipo_de_documento']) : '' ),
                 'cliente_numero_de_documento' => ( trim($paramsCliente['cliente_numero_de_documento']) != '' ?  trim($paramsCliente['cliente_numero_de_documento']) : '' ),
                 'cliente_denominacion' => ( trim($paramsCliente['cliente_denominacion']) != '' ?  trim($paramsCliente['cliente_denominacion']) : '' ),
                 'cliente_direccion' => ( trim($paramsCliente['cliente_direccion']) != '' ?  trim($paramsCliente['cliente_direccion']) : '' ),
                 'ref_tipo' => $paramsPedido['ref_tipo'],
                 'ref_id' =>  $paramsPedido['ref_id'],
                 'fecha_de_emision' => $paramsPedido['fecha_de_emision'], // fecha actual
                 'moneda' => $configParametros['moneda'],
                 'porcentaje_de_igv' => $configParametros['igv'],
                 'total_gravada' => $paramsPedido['total_gravada'],
                 'total_igv' => $paramsPedido['total_igv'],
                 'total' => $paramsPedido['total']
        );

        $objComprobanteDao = new comprobanteDAO();
        $objComprobanteDetalleDao = new comprobanteDetalleDAO();


        $comprobante_id =  $objComprobanteDao->registrar($paramsComprobante);

        foreach ($paramsDetalle as $regDetalle) {


            if($regDetalle['precio_referencial'] != ''){

                $impuestoU = round( $regDetalle['precio_referencial'] *  $configParametros['igv'], 2 );
                $precio_referencial = round($regDetalle['precio_referencial'],2) + $impuestoU;
            }
            else{
                $precio_referencial = $regDetalle['precio_referencial'];
            }


            if($regDetalle['impuesto'] != ''){

                $impuestoST = round( $regDetalle['subtotal'] *  $configParametros['igv'], 2 );

            }
            else{
                $impuestoST = $regDetalle['impuesto'];
            }

            $params = array('comprobante_id' => $comprobante_id,
                            'detalle' => $regDetalle['detalle'],
                            'cantidad' => ($regDetalle['cantidad'] != '' ? $regDetalle['cantidad'] : '1' ),
                            'precio_unitario' => $regDetalle['precio_unitario'],
                            'precio_referencial' => $precio_referencial,
                            'unidad_medida' => ( $regDetalle['unidad_medida'] == '' ?  $configParametros['unidad_de_medida'] : $regDetalle['unidad_medida']),
                            'subtotal' =>  $regDetalle['subtotal'],
                            'impuesto' =>  $impuestoST,
                            'product_id' =>  $regDetalle['product_id']
                            );

            $objComprobanteDetalleDao->registrar($params);


        }

        $objSerie->aumentar_serie_comprobante(array('tipo_comprobante' => trim($paramsPedido['tipo_de_comprobante']) ));


        $objServiceApi = new serviceFeAPI();
        $result = $objServiceApi->enviar_comprobante($comprobante_id, trim($paramsPedido['tipo_de_comprobante']) );

        return true;

    }

}
