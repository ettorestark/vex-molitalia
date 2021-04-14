<?PHP

/*
Plugin Name: Vexsoluciones Facturación Electrónica
Description: Facturación electrónica con Nubefact
Version: 2.0
Author: VexSoluciones - Facturación
Author URI: https://www.vexsoluciones.com
*/
 

if ( ! defined( 'ABSPATH' ) ) exit;
  
if (!defined('VEX_FEC_COMPROBANTE_FACTURA'))
    define( 'VEX_FEC_COMPROBANTE_FACTURA', '1'  );

if (!defined('VEX_FEC_COMPROBANTE_BOLETA'))
    define( 'VEX_FEC_COMPROBANTE_BOLETA', '2'  );
 
require_once __DIR__.'/autoload.php';
require_once __DIR__.'/config.php';


if(! class_exists('vexfe_facturacion_electronica')){
 
	class vexfe_facturacion_electronica{

		public $client;

		public function __construct($client){ 

	 	 	// Iniciamos el Admin del plugin
			new vexfacturacionelectronica\wpAdmin();


			if($client){

 				$client->ready();
			}
 
		}
 
	} 
	/*
		Por defecto usamos woocommerce
	*/
	new vexfe_facturacion_electronica( new vexfacturacionelectronica\wpFacturacionWoocommerce() );

}
 

add_action('init', 'vex_global_init' );

function vex_global_init(){

    flush_rewrite_rules();
    add_rewrite_rule( 'comprobantes_sunat/([0-9]+)/?$', 'index.php?vex-modulo=facturacion&vex-page=ver_factura&order_id=$matches[1]', 'top' );
    add_filter( 'query_vars', 'vex_global_query_vars' );
}


function vex_global_query_vars($query_vars){

    $query_vars[] = 'vex-modulo';
    $query_vars[] = 'vex-page';
    $query_vars[] = 'order_id';
    return $query_vars;

}

 
add_filter( 'template_include', 'vex_global_manage_template');

function vex_global_manage_template($template){

    global $wp_query;

    $query_modulo = get_query_var('vex-modulo');
    $query_page = get_query_var('vex-page');

    if( $query_modulo == 'facturacion' && $query_page == 'ver_factura' ){

        $order_id = get_query_var('order_id');
   
        return plugin_dir_path( __FILE__ ).'templates/documentos/factura.php';
    }

    return $template;

}
 


function vexfe_install_module_facturacion(){

    global $wpdb; 
    
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    $sql="";

    $charset_collate = $wpdb->get_charset_collate(); 

    $table = $wpdb->prefix . 'vexfe_comprobantes';
    $sql= " DROP TABLE IF EXISTS `".$table."`;";  
    $wpdb->query($sql);

    $sql= " 
       CREATE TABLE $table (
       `id` int(11) NOT NULL AUTO_INCREMENT,
       `ref_tipo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `ref_id` int(11) DEFAULT NULL,
       `tipo_de_comprobante` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `serie` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `numero` int(11) DEFAULT NULL,
       `cliente_tipo_de_documento` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `cliente_numero_de_documento` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `cliente_denominacion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `cliente_direccion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `cliente_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `cliente_fono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `fecha_de_emision` date DEFAULT NULL,
       `fecha_de_vencimiento` date DEFAULT NULL,
       `moneda` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
       `porcentaje_de_igv` double DEFAULT NULL,
       `total_gravada` double DEFAULT NULL,
       `total_igv` double DEFAULT NULL,
       `total` double DEFAULT NULL,
       `data_respuesta` text COLLATE utf8mb4_unicode_ci,
       `sunat_estado` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
       `sys_estado` smallint(6) DEFAULT '1',
       PRIMARY KEY (`id`,`sunat_estado`)
    ) $charset_collate;";
    $wpdb->query($sql);


     $table = $wpdb->prefix . 'vexfe_comprobante_detalle';
       
     $sql= " DROP TABLE IF EXISTS `".$table."`;"; 
     $wpdb->query($sql);
     $sql= "CREATE TABLE $table (
       `id` int(11) NOT NULL AUTO_INCREMENT,
       `comprobante_id` int(11) NOT NULL,
       `detalle` varchar(100) DEFAULT NULL,
       `unidad_medida` varchar(40) DEFAULT NULL,
       `cantidad` double DEFAULT '0',
       `precio_unitario` double DEFAULT '0',
       `precio_referencial` double DEFAULT '0', 
       `subtotal` double DEFAULT '0',
       `impuesto` double DEFAULT '0',
       `sys_estado` smallint(6) DEFAULT '1',
       `product_id` int(11) DEFAULT '0',
       PRIMARY KEY (`id`)
     ) $charset_collate;";
     $wpdb->query($sql);
 

 
     $table = $wpdb->prefix . 'vexfe_config'; 

     $sql= " DROP TABLE IF EXISTS `".$table."`;"; 
     $wpdb->query($sql);
     $sql= "CREATE TABLE $table (
       `id` int(11) NOT NULL AUTO_INCREMENT,
       `razonsocial` varchar(50) NOT NULL,
       `direccionfiscal` varchar(50) NOT NULL,
       `ruc` varchar(20) NOT NULL,
       `url_envio` varchar(100) NOT NULL,
       `token` varchar(100) NOT NULL,
       `igv` float NOT NULL DEFAULT '18',
       `moneda` varchar(20) NOT NULL DEFAULT 'PEN',
       `unidad_de_medida` varchar(30) NOT NULL DEFAULT 'NIU',
       `estado` int(1) NOT NULL DEFAULT '1',
       PRIMARY KEY (`id`)
     ) $charset_collate;";
     $wpdb->query($sql);



    $table = $wpdb->prefix . 'vexfe_serie';
       
    $sql= " DROP TABLE IF EXISTS `".$table."`;"; 
    $wpdb->query($sql);
    $sql= "CREATE TABLE $table (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `tipo_de_comprobante` int(11) DEFAULT NULL,
        `serie_tipo` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `serie_numero` int(11) DEFAULT NULL,
        `num_desde` int(11) DEFAULT NULL,
        `num_hasta` int(11) DEFAULT NULL,
        `correlativo` int(11) DEFAULT NULL,
        `estado` int(11) DEFAULT '1',
        PRIMARY KEY (`id`)
     ) $charset_collate;";
    $wpdb->query($sql);


    $sql=" INSERT INTO `".$table."` (`tipo_de_comprobante`, `serie_tipo`, `serie_numero`, `num_desde`, `num_hasta`, `correlativo`, `estado`) 
            VALUES (1, 'F', 1, 1, 10000, 1, 1),
                  (2, 'B', 1, 1, 10000, 1, 1);
         "; 
    $wpdb->query($sql); 

}
 
 
register_activation_hook( __FILE__, 'vexfe_install_module_facturacion' ); 