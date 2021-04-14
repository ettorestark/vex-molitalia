<?PHP

namespace vexfacturacionelectronica;

class wpProcessPost{


    public function __construct(){

    }

    public function init(){

        add_action( 'admin_post_vex_guardar_config', array(&$this,'guardar_configuracion') );
        add_action( 'admin_post_nopriv_vex_guardar_config', array(&$this,'guardar_configuracion') );


        add_action( 'admin_post_envio_resumen_diario', array(&$this,'envio_resumen_diario') );
        add_action( 'admin_post_nopriv_envio_resumen_diario', array(&$this,'envio_resumen_diario') );


    }

    public function guardar_configuracion(){
        

        $objConfig = new configDAO();
 
        $params = [ 
            'ruc' => sanitize_text_field( $_POST['ruc'] ),
            'razonsocial' => sanitize_text_field( $_POST['razon_social'] ),
            'direccionfiscal' => sanitize_text_field( $_POST['direccionfiscal'] ),
            'igv' => sanitize_text_field( $_POST['igv'] ),
            'moneda' => sanitize_text_field( $_POST['moneda'] ),
            'unidad_de_medida' => sanitize_text_field( $_POST['unidad_de_medida'] ),
            'url_envio' => sanitize_text_field( $_POST['url_envio'] ),
            'token' => sanitize_text_field( $_POST['token'] )  
        ]; 

        $objConfig->registrar($params); 

        $result = '1';

 
        $objSerie = new serieDAO();

        if( $_POST['boleta_serie'] != '' && $_POST['boleta_correlativo'] != '' ){

            $objSerie->actualizar_correlativo(['serie' => sanitize_text_field( $_POST['boleta_serie'] ),
                                               'correlativo' => sanitize_text_field( $_POST['boleta_correlativo'] ),
                                               'tipo_comprobante' => \vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA ]);
        
        }

        if( $_POST['factura_serie'] != '' && $_POST['factura_correlativo'] != '' ){

            $objSerie->actualizar_correlativo(['serie' => sanitize_text_field( $_POST['factura_serie'] ),
                                               'correlativo' => sanitize_text_field( $_POST['factura_correlativo'] ),
                                               'tipo_comprobante' => \vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA ]);
        
        }
 

        $url = admin_url('admin.php').'?page=facturacion-resume&result='.$result;


        if(isset($_FILES['logo_empresa'])){


            $uploadedfile = $_FILES['logo_empresa'];

            list($tipo, $extension) = explode('/', $_FILES['logo_empresa']['type']);

            $nuevo_logo = $_POST['ruc'].'.'.$extension;

            $upload_dir = wp_upload_dir();


            $destino = $upload_dir['basedir'].'/facturacion_vex/logos/'.$nuevo_logo;
            $source      = $_FILES['logo_empresa']['tmp_name'];

            move_uploaded_file( $source, $destino );


            $destino_url = $upload_dir['baseurl'].'/facturacion_vex/logos/'.$nuevo_logo;
            update_option( 'vexfe_logo_empresa',  $destino_url, false );
        }
 

        wp_redirect( $url, $status = 302 );

        die();

    }



    public function envio_resumen_diario(){



        require_once(_VEXFE_MODULE_DIR_.'/inc/class-dao-comprobante.php');
        require_once(_VEXFE_MODULE_DIR_.'/inc/class-dao-envio-boleta.php');

        $objDaoEnvioBoleta = new vex_dao_envio_boleta();

        $boleta_inicio = $_POST['resumen_boleta_inicio'];
        $boleta_fin =  $_POST['resumen_boleta_fin'];

        //var_dump($boleta_inicio, $boleta_fin);

        list($inicio_serie, $inicio_numero, $inicio_id ) = explode('-', $boleta_inicio);
        list($fin_serie, $fin_numero, $fin_id ) = explode('-', $boleta_fin);


        $idEnvio = $objDaoEnvioBoleta->registrar(array(

            'serie_id' => '',
            'numero_inicio' => $inicio_numero,
            'numero_fin' => $fin_numero,
            'boleta_inicio_id' => $inicio_id,
            'boleta_fin_id' => $fin_id

        ));

        $datos = $_POST['action'];

        $result = '1';

        sleep(3);

        $url = admin_url('admin.php').'?page=facturacion-comprobantes&tab=boletas&result='.$result."&envio-resumen=".$idEnvio;

        wp_redirect( $url, $status = 302 );

        die();

    }

}
