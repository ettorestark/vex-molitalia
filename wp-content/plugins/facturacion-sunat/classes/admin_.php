<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}




if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class PedidoList extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( array(
			'singular' => __( 'Pedido', 'sp' ),
			'plural'   => __( 'Pedidos', 'sp' ),
			'ajax'     => false
		) );

	}


	public static function get_pedidos( $per_page = 5, $page_number = 1 ) {		
		global $wpdb;

		$page_number--;

		$sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'shop_order' LIMIT $page_number, $per_page";

		return $wpdb->get_results( $sql, 'ARRAY_A' );
	}


	public static function delete_customer( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}customers",
			array( 'ID' => $id ),
			array( '%d' )
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function count_pedidos() {
		global $wpdb;

		$sql = "SELECT count(ID) FROM {$wpdb->prefix}posts WHERE post_type = 'shop_order' LIMIT 1";

		$count = $wpdb->get_var( $sql );

		return $count;
	}


	public function no_items() {
		echo 'No hay pedidos';
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {

		$order = new WC_Order( $item['ID'] );

		$solicitar_factura = get_post_meta( $item['ID'], 'solicitar_factura', true );

		switch ( $column_name ) {
			case 'id':
				return $item['ID'];
				
			case 'estado':

				if( $solicitar_factura != '1' )
				{
					return 'Sin factura';
				}
				

				return wc_get_order_status_name( $order->status );
				
			case 'cliente':
				$user = get_user_by( 'ID', $item['post_author'] );
				return "{$user->__get( 'first_name' )} {$user->__get( 'last_name' )}";

			case 'empresa':

				if( $solicitar_factura )
				{
					return get_post_meta( $item['ID'], 'nombre_fiscal', true );
				}

				return '';

			case 'fecha';
				return get_the_date( null, $item['ID'] );

			case 'total':
				return wc_price( get_post_meta( $item['ID'], '_order_total', true ) );

			case 'acciones':
				return '';

			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	function get_columns() {
		$columns = array(
			// 'cb'      => '<input type="checkbox" />',
			'id'    => 'ID',
			'estado' => 'Estado',
			'cliente' => 'Cliente',
			'empresa' => 'Empresa',
			'fecha' => 'Fecha',
			'total' => 'Total',
			'acciones' => 'Acciones'
		);

		return $columns;
	}
	
	public function get_sortable_columns() {
		$sortable_columns = array(
			// 'name' => array( 'name', true ),
			// 'city' => array( 'city', false )
		);

		return array();
	}


	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {
		$title = '<strong>' . $item['display_name'] . '</strong>';
		return $title;
	}



	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => 'Delete'
		);

		return array();
	}


	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'pedidos_per_page', 20 );
		$current_page = $this->get_pagenum();
		$count_pedidos  = self::count_pedidos();

		$this->set_pagination_args( array(
			'total_items' => $count_pedidos, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		) );

		$this->items = self::get_pedidos( $per_page, $current_page );

	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_customer( absint( $_GET['customer'] ) );

						// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
						// add_query_arg() return the current url
						wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
			 || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_customer( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
				// add_query_arg() return the current url
				wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}

}


class ListaFactura_Plugin {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $pedidos_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_menu_page(
			'Facturas',
			'Facturas',
			'manage_options',
			'facturas',
			array( $this, 'plugin_settings_page' ),
			'',
			5
		);

		add_action( "load-$hook", array( $this, 'screen_option' ) );

	}


	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		global $GLOBALS;

		
		?>
		<div class="wrap">

		<?php 
			echo $GLOBALS['facturacion_message'];

			if( $this->validate_facturacion_plugin() == false )
			{
				return;
			}

		 ?>
			<nav class="nav-tab-wrapper">
				<a href="?page=facturas&tab=mis-facturas" class="nav-tab <?php echo empty( $_GET['tab'] ) || @$_GET['tab'] == 'mis-facturas' ? 'nav-tab-active' : '' ?>">Mis facturas</a>
				<a href="?page=facturas&tab=configuracion" class="nav-tab <?php echo @$_GET['tab'] == 'configuracion' ? 'nav-tab-active' : '' ?>">Configuración</a>
			</nav>
			<h2>Facturas</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<!-- <form method="post"> -->
								<?php
								if( @$_GET['tab'] == 'configuracion' )
								{
									$this->show_config_page();
								}
								else
								{
									$this->pedidos_obj->prepare_items();
									$this->pedidos_obj->display();
								}
								 ?>
							<!-- </form> -->
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	private function show_config_page()
	{

		$countries_obj   = new WC_Countries();

		$departamentos = $countries_obj->get_states( 'PE' );

		print_r( $departamentos );

	?>
		<form action="" name='configuracion-facturacion' method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">RUC</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_ruc" value="<?php echo get_option( 'empresa_ruc', '' ) ?>" required="required" maxlength="11"></td>
					</tr>
					<tr>
						<th scope="row">Usuario SOL</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_usuario" value="<?php echo get_option( 'empresa_usuario', '' ) ?>" required="required" maxlength="8"></td>
					</tr>
					<tr>
						<th scope="row">Clave SOL</th>
						<td class="forminp forminp-text"><input type="password" name="empresa_clave" value="<?php echo get_option( 'empresa_clave', '' ) ?>" required="required"></td>
					</tr>
					<tr>
						<th scope="row">Razón Social</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_razon_social" value="<?php echo get_option( 'empresa_razon_social', '' ) ?>" required="required"></td>
					</tr>
					<tr>
						<th scope="row">Nombre Comercial</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_nombre_comercial" value="<?php echo get_option( 'empresa_nombre_comercial', '' ) ?>"></td>
					</tr>
					<tr>
						<th scope="row">Departamento</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_departamento" value="<?php echo get_option( 'empresa_departamento', '' ) ?>"></td>
					</tr>
					<tr>
						<th scope="row">Provincia</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_provincia" value="<?php echo get_option( 'empresa_provincia', '' ) ?>"></td>
					</tr>
					<tr>
						<th scope="row">Distrito</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_distrito" value="<?php echo get_option( 'empresa_distrito', '' ) ?>"></td>
					</tr>
					<tr>
						<th scope="row">Ubigeo</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_ubigeo" value="<?php echo get_option( 'empresa_ubigeo', '' ) ?>"></td>
					</tr>
					<tr>
						<th scope="row">Dirección</th>
						<td class="forminp forminp-text"><input type="text" name="empresa_direccion" value="<?php echo get_option( 'empresa_direccion', '' ) ?>"></td>
					</tr>
					
				</tbody>
			</table>
			<input name="guardar-configuracion-facturacion" class="button-primary woocommerce-save-button" value="Guardar los cambios" type="submit">
		</form>

	<?php
	}

	private function validate_facturacion_plugin()
	{
		$license = get_option( 'woocommerce_facturacion_license', '' );
		$activated = get_option( 'woocommerce_facturacion_activated', '0' );


		if( !empty( $license ) && $activated )
		{
			return true;			
		}

		
	?>
		<p>Please enter the license key for this product to activate it. You were given a license key when you purchased this item.</p>
    	<form action="" method="post">
	        <table class="form-table">
	            <tr>
	                <th style="width:100px;"><label for="woocommerce_facturacion_code">License Key</label></th>
	                <td ><input class="regular-text" type="text" id="woocommerce_facturacion_code" name="woocommerce_facturacion_code"  value="<?php echo get_option('woocommerce_facturacion_license'); ?>" ></td>
	            </tr>
	        </table>
	        <p class="submit">
	            <input type="submit" name="activate_license" value="Activate" class="button-primary" />
	        </p>
	    </form>

	<?php

	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = array(
			'label'   => 'Pedidos',
			'default' => 5,
			'option'  => 'pedidos_per_page'
		);

		add_screen_option( $option, $args );

		$this->pedidos_obj = new PedidoList();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


add_action( 'plugins_loaded', function () {
	ListaFactura_Plugin::get_instance();
} );




add_action( 'init', 'save_facturacion_config' );

function save_facturacion_config()
{
	if( !is_admin() ) return;

	if( !isset( $_POST['guardar-configuracion-facturacion'] ) ) return;

	update_option( 'empresa_ruc', $_POST['empresa_ruc'] );
	update_option( 'empresa_usuario', $_POST['empresa_usuario'] );
	update_option( 'empresa_clave', $_POST['empresa_clave'] );
	update_option( 'empresa_razon_social', $_POST['empresa_razon_social'] );
	update_option( 'empresa_nombre_comercial', $_POST['empresa_nombre_comercial'] );
	update_option( 'empresa_departamento', $_POST['empresa_departamento'] );
	update_option( 'empresa_provincia', $_POST['empresa_provincia'] );
	update_option( 'empresa_distrito', $_POST['empresa_distrito'] );
	update_option( 'empresa_ubigeo', $_POST['empresa_ubigeo'] );
	update_option( 'empresa_direccion', $_POST['empresa_direccion'] );
}

