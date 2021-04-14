<?PHP

namespace vexfacturacionelectronica;

class wpAdmin{

	public function install(){

		$sql ="
			CREATE TABLE IF NOT EXISTS `wp_vexfe_comprobantes` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `wp_tipo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
			  `moneda` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `porcentaje_de_igv` double DEFAULT NULL,
			  `total_gravada` double DEFAULT NULL,
			  `total_igv` double DEFAULT NULL,
			  `total` double DEFAULT NULL,
			  `wp_data` text COLLATE utf8mb4_unicode_ci,
			  `sunat_estado` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
			  `sys_estado` smallint(6) DEFAULT '1',
			  PRIMARY KEY (`id`,`sunat_estado`)
			) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


			CREATE TABLE IF NOT EXISTS `wp_vexfe_comprobante_detalle` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `comprobante_id` int(11) NOT NULL,
			  `detalle` varchar(100) DEFAULT NULL,
			  `cantidad` double DEFAULT '0',
			  `precio_unitario` double DEFAULT '0',
			  `subtotal` double DEFAULT NULL,
			  `sys_estado` smallint(6) DEFAULT '1',
			  `product_id` int(11) DEFAULT '0',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;



			CREATE TABLE IF NOT EXISTS `wp_vexfe_envios_boletas` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `serie_id` int(11) DEFAULT NULL,
			  `numero_inicio` int(11) DEFAULT NULL,
			  `numero_fin` int(11) DEFAULT NULL,
			  `boleta_inicio_id` int(11) DEFAULT NULL,
			  `boleta_fin_id` int(11) DEFAULT NULL,
			  `fecha` date DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;



			CREATE TABLE IF NOT EXISTS `wp_vexfe_envios_facturas` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `comprobante_id` int(11) NOT NULL,
			  `fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `codigo_sunat` varchar(100) DEFAULT NULL,
			  `estado_sunat` smallint(6) DEFAULT '0',
			  `resumen_respuesta` varchar(250) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4;



			CREATE TABLE IF NOT EXISTS `wp_vexfe_serie` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `tipo_de_comprobante` int(11) DEFAULT NULL,
			  `serie_tipo` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
			  `serie_numero` int(11) DEFAULT NULL,
			  `num_desde` int(11) DEFAULT NULL,
			  `num_hasta` int(11) DEFAULT NULL,
			  `correlativo` int(11) DEFAULT NULL,
			  `estado` int(11) DEFAULT '1',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

			--
			-- Volcado de datos para la tabla `wp_vexfe_serie`
			--

			INSERT INTO `wp_vexfe_serie` (`id`, `tipo_de_comprobante`, `serie_tipo`, `serie_numero`, `num_desde`, `num_hasta`, `correlativo`, `estado`) VALUES
			(3, 1, 'F', 7, 1, 10000, 10, 1),
			(4, 2, 'B', 1, 1, 10000, 5, 1);
 
		 	";


	}


	public function __construct(){ 

			add_action( 'admin_menu', array( &$this, 'config_menu') );
			add_action( 'admin_enqueue_scripts', array( &$this, 'load_assets') );

		    $ajax_fe = new wpProcessAjax();
			$ajax_fe->init();

		    $post_fe = new wpProcessPost();
			$post_fe->init();

			/*
			add_action( 'wp_enqueue_scripts', 'vex_register_assets_proyecto' );

			function vex_register_assets_proyecto(){

			      $baseUploadDir = wp_upload_dir();

			      $data = array(
			          'project_web_url' => site_url(),
			          'icons_url' => $baseUploadDir['baseurl'].'/icons/'
			      );

			      wp_localize_script( '', 'vex_urls', $data );

			}  */

	}

	public function load_assets(){


		wp_register_style( 'font-awsome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '1.0.0' );
		wp_enqueue_style( 'font-awsome' );


		wp_register_style('vexfe-custom_wp_admin_css', plugins_url().'/facturacion-sunat/css/vexfe_facturacion.css', false, '1.0.0' );
		wp_enqueue_style( 'vexfe-custom_wp_admin_css' );

		wp_enqueue_script('chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.js');

		wp_enqueue_script('vexfe-adminjs',  plugins_url().'/facturacion-sunat/js/admin.js');

		wp_enqueue_script('bootstrap-js',  plugins_url().'/facturacion-sunat/js/bootstrap.min.js');

		// Localize the script with new data
		$translation_array = array(
			'url' => site_url()
		);
		wp_localize_script( 'vexfe-adminjs', 'vexfe_vars', $translation_array );



	}

	public function config_menu() {

		add_menu_page( 'Módulo de gestión de facturación electrónica',
					   'Facturación electrónica',
					   'manage_options',
					   'facturacion-resume',
					    array( &$this, 'facturacion_resume_config'),
					    'dashicons-tickets',
					    6  );
 
			add_submenu_page( 'facturacion-resume',
							  'Comprobantes',
							  'Comprobantes',
							  'manage_options',
							  'facturacion-comprobantes',
							   array( &$this, 'ver_comprobantes') );


			add_submenu_page(
			         null            // -> Set to null - will hide menu link
			       , 'Detalle del comprobante'    // -> Page Title
			       , 'Detalle comprobante'    // -> Title that would otherwise appear in the menu
			       , 'manage_options' // -> Capability level
			       , 'detalle-comprobante'   // -> Still accessible via admin.php?page=menu_handle
		       , array(&$this, 'ver_detalle_comprobante') // -> To render the page
			   );


		//add_submenu_page( 'myplugin/myplugin-admin-page.php', 'My Sub Level Menu Example', 'Sub Level Menu', 'manage_options', 'myplugin/myplugin-admin-sub-page.php', 'myplguin_admin_sub_page' );

	}

	public function facturacion_resume_config(){

		include_once(_VEXFE_MODULE_DIR_.'/templates/template-config.php');
	}


	public function ver_comprobantes(){


		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}
  		// Se carga el template

		include_once(_VEXFE_MODULE_DIR_.'/templates/template_comprobantes.php');


	}

	public function ver_detalle_comprobante(){ 

		if(isset($_GET['comprobante']) && is_numeric($_GET['comprobante'])){

			$comprobante_id = $_GET['comprobante'];
		 
			$objComprobanteDao = new comprobanteDAO();
			list($comprobante_info) = $objComprobanteDao->get(array('id' => $comprobante_id)); 		
	 
			include_once(_VEXFE_MODULE_DIR_.'/templates/template_view_factura.php');
 
		}
 
	} 

	public function load_user_list_table_screen_options() {
		
		$arguments = array(
			'label'		=>	__( 'Users Per Page', $this->plugin_text_domain ),
			'default'	=>	5,
			'option'	=>	'users_per_page'
		);
		add_screen_option( 'per_page', $arguments );
		/*
		 * Instantiate the User List Table. Creating an instance here will allow the core WP_List_Table class to automatically
		 * load the table columns in the screen options panel
		 */
		$this->user_list_table = new User_List_Table( $this->plugin_text_domain );
	}
	/*
	 * Display the User List Table
	 * Callback for the add_users_page() in the add_plugin_admin_menu() method of this class.
	 */
	public function load_user_list_table(){
		// query, filter, and sort the data
		$this->user_list_table->prepare_items();
		// render the List Table
		include_once( 'views/partials-wp-list-table-demo-display.php' );
	}


}
