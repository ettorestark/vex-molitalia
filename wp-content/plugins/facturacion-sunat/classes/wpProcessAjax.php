<?PHP

namespace vexfacturacionelectronica;

class wpProcessAjax{

    public function __construct(){

    }

    public function init(){

        add_action( 'wp_ajax_vex_fe_enviar_factura', array(&$this,'enviar_factura') );
        add_action( 'wp_ajax_nopriv_vex_fe_enviar_factura', array(&$this,'enviar_factura') );

        add_action( 'wp_ajax_vex_fe_enviar_resumen_diario', array(&$this,'enviar_resumen_diario') );
        add_action( 'wp_ajax_nopriv_vex_fe_enviar_resumen_diario', array(&$this,'enviar_resumen_diario') );


        add_action( 'wp_ajax_vex_fe_consulta_boletas_resumen_diario', array(&$this,'consulta_resumen_diario') );
        add_action( 'wp_ajax_nopriv_vex_fe_consulta_boletas_resumen_diario', array(&$this,'consulta_resumen_diario') );


    }

    public function enviar_factura(){

        require_once(_VEXFE_MODULE_DIR_.'/inc/class-constants.php');
        require_once(_VEXFE_MODULE_DIR_.'/inc/rest.php');
        require_once(_VEXFE_MODULE_DIR_.'/inc/class-service.php');
        require_once(_VEXFE_MODULE_DIR_.'/inc/class-dao-comprobante.php');
        require_once(_VEXFE_MODULE_DIR_.'/inc/class-dao-comprobante-factura.php');
        require_once(_VEXFE_MODULE_DIR_.'/inc/class-dao-comprobante-detalle.php');
        require_once(_VEXFE_MODULE_DIR_.'/inc/class-dao-envio-factura.php');

        $dataPost = $_POST;

        $factura_id = sanitize_text_field($dataPost['comprobante_id']);

        if(!$factura_id){

        }

        $objFactura = new vexfe_dao_comprobante_factura();
        $objFacturaDetalle = new vexfe_dao_comprobante_detalle();

        $objDaoEnvio = new vex_dao_envio_factura();


        list($rsFactura) =  $objFactura->get(array('id' => $factura_id));

        $order_id = $rsFactura['ref_id'];

       // var_dump(round($rsFactura['total_gravada'],2),  round($rsFactura['total_igv'],2), (round($rsFactura['total_gravada'],2) +  round($rsFactura['total_igv'],2)) );

        $data = array(
            "emisor_ruc" => "20532710066",
            'moneda' => 'PEN',
            'descuentos' => '0',
            'gravadas' =>  round($rsFactura['total_gravada'],2),
            'serie' => $rsFactura['serie'],
            'numero' => $rsFactura['numero'],
            'tipo' => '01',
            'fecha' =>  $rsFactura['fecha_de_emision'],
            'cliente_ruc' => $rsFactura['cliente_numero_de_documento'],
            'cliente_tipo_doc' => '6',
            'cliente_razon_social' => $rsFactura['cliente_denominacion'],
            'igv' => round($rsFactura['total_igv'],2),
            'total' => (round($rsFactura['total_gravada'],2) +  round($rsFactura['total_igv'],2)),
            "items" => array(

            )
        );

        $rsFacturaDetalle = $objFacturaDetalle->get(array('comprobante_id' => $factura_id));


        $c = 1;
        foreach ($rsFacturaDetalle as $reg) {

            //var_dump( round($reg["precio_unitario"],2), round($reg["precio_unitario"] * $reg["cantidad"],2), round($rsFactura['total_igv']*1, 2) );

            $datosProducto = array(
                'item_id' => trim($c),
                'item_unidad' => 'NIU',
                'item_cantidad' => $reg['cantidad'],
                'item_venta' =>  round($reg["precio_unitario"] * $reg["cantidad"],2),
                'item_importe' => round($reg["precio_unitario"] * $reg["cantidad"],2),
                'item_descuento' => '0',
                'item_igv' =>  round($rsFactura['total_igv']*1, 2),
                'item_descripcion' => $reg['detalle'],
                'item_codigo' => 'TEST',
                'item_precio_unitario' =>  round($reg["precio_unitario"],2)
            );

            array_push($data['items'], $datosProducto);

            $c++;
        }


        $rsFacturaEnvio = $objDaoEnvio->get(array('comprobante_id' => $factura_id,
                                                  'estado_sunat' => vexfe_constants::$FACTURA_ENVIADA_OK ));

        //var_dump($rsFacturaEnvio);



        $objService = new vex_fe_service(); // API VEX

        /*
        $__data = array(
            "emisor_ruc" => "20532710066",
            'moneda' => 'PEN',
            'descuentos' => '275.85',
            'gravadas' => '318.40',
            'serie' => 'F002',
            'numero' => '18207',
            'tipo' => '01',
            'fecha' => '2017-11-17',
            'cliente_ruc' => '20257330703',
            'cliente_tipo_doc' => '6',
            'cliente_razon_social' => 'EMPRESA DE INGENIERIA Y SERVICIOS MULTIPLES SRL',
            'igv' => '35.96',
            'total' => '375.72',
            "items" => array(
                array(
                    'item_id' => '1',
                    'item_unidad' => 'NIU',
                    'item_cantidad' => '1',
                    'item_venta' => '199.76',
                    'item_importe' => '475.61',
                    'item_descuento' => '275.85',
                    'item_igv' => '35.96',
                    'item_descripcion' => 'JGOZAPATAFRENO WO1',
                    'item_codigo' => '044950K120',
                    'item_precio_unitario' => '475.61',
                ),
                array(
                    'item_id' => '2',
                    'item_unidad' => 'NIU',
                    'item_cantidad' => '1',
                    'item_venta' => '199.76',
                    'item_importe' => '475.61',
                    'item_descuento' => '275.85',
                    'item_igv' => '35.96',
                    'item_descripcion' => 'JGOZAPATAFRENO WO1',
                    'item_codigo' => '044950K120',
                    'item_precio_unitario' => '475.61',
                )
            )
        ); */


        $datosAEnviar = array("data" => json_encode($data));


        $response = json_decode($objService->enviar_factura($datosAEnviar),true);

        $result = $response['aceptado'] == 'si' ? '1' : '0';


        $upload_dir = wp_upload_dir();
        $baseDir = $upload_dir['basedir'].'/facturacion_vex';




        require_once(_VEXFE_MODULE_DIR_.'/templates/documentos/factura.php');

        // SI ya tiene un envio positivo
        if( intval($rsFactura['sunat_envios_conformes']) > 0 ){
            // Se retorna el CDR

            echo json_encode(array(
                                'result' => vexfe_constants::$FACTURA_ENVIADA_OK
                             ));

            die();
        }


        /*

        $xml = fopen($baseDir.'/facturas/'.$response['nombre'].'.zip', 'w+');
        fputs($xml, base64_decode($response['xml']));
        fclose($xml);

        //guardamos el pdf
        $pdf = fopen($baseDir.'/facturas/'.'R-'.$response['nombre'].'.pdf', 'w+');
        fputs($pdf, base64_decode($response['pdf']));
        fclose($pdf);

        //guardamos el cdr
        $cdr = fopen($baseDir.'/facturas/'.'R-'.$response['nombre'].'.zip', 'w+');
        fputs($cdr, base64_decode($response['cdr']));
        fclose($cdr);
        */

        //var_dump('Recibido', $response);

       // $datosResponse = json_decode($response, true);


        // IMPLEMENTAR Guardar los datos recibidos


        $datosEnvio = array(
            'comprobante_id' => $factura_id,
            'codigo_sunat' => $response['nombre'],
            'estado_sunat' => $result
        );

        $objDaoEnvio->registrar($datosEnvio);


        echo json_encode(array(
                            'result' => $result,
                            'intentos_fallidos' => $rsFactura['sunat_envios_fallidos']
                         ));

        die();

    }


    public function consulta_resumen_diario(){


        require_once(_VEXFE_MODULE_DIR_.'/inc/class-dao-comprobante.php');
        require_once(_VEXFE_MODULE_DIR_.'/inc/class-dao-comprobante-boleta.php');

        $fecha = sanitize_text_field($_POST['fecha']);

        $objDaoBoleta = new vexfe_dao_comprobante_boleta();

        $boletas = $objDaoBoleta->get(array('fecha_de_emision' => $fecha ));

        $numero_de_comprobantes = sizeof($boletas);

        $boleta_inicio = reset($boletas);
        $boleta_fin = end($boletas);

        // var_dump($boleta_inicio['id'], $boleta_fin['id']);
        $serie_inicio = $boleta_inicio['serie'].'-'.str_pad( $boleta_inicio['numero'], 3, "0", STR_PAD_LEFT);
        $serie_fin = $boleta_fin['serie'].'-'.str_pad( $boleta_fin['numero'], 3, "0", STR_PAD_LEFT);


        ?>


            <form method="POST" action="<?PHP echo admin_url('admin-post.php'); ?>" >

                <input type="hidden" name="action" value="envio_resumen_diario">

                <input type="hidden" name="resumen_boleta_inicio" value="<?PHP echo $boleta_inicio['serie'].'-'.$boleta_inicio['numero'].'-'.$boleta_inicio['id'] ?>" />
                <input type="hidden" name="resumen_boleta_fin" value="<?PHP echo $boleta_fin['serie'].'-'.$boleta_fin['numero'].'-'.$boleta_fin['id'] ?>" />

                <div style="margin-top: 20px;">
                  En el día <?PHP echo $fecha; ?> se encontraron <?PHP echo $numero_de_comprobantes ?> boletas, del número de serie <?PHP echo $serie_inicio ?> al <?PHP echo $serie_fin; ?>.
                </div>

                <div style="margin-top: 20px;">
                    <button id="btnEnvioResumenDiario" class="button"> Realizar envío </button>
                </div>

            </form>


        <?PHP

        die();
    }


    public function enviar_resumen_diario(){

    }

}
