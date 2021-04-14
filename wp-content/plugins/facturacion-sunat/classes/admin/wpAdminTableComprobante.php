<?PHP

namespace vexfacturacionelectronica;


class wpAdminTableComprobante extends \WP_List_Table{


	public $objDAO;

	public function __construct($objDAO){

		$this->objDAO = $objDAO;

		parent::__construct();

	}

	public function get_bulk_actions() {

		  $actions = [
		//    'bulk-delete' => 'Delete'
		  ];

		  return $actions;
	}


	public function prepare_items() {

		global $wpdb;

		/*
		// check if a search was performed.
		$user_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

		$this->_column_headers =  $this->get_columns();

		// var_dump($this->_column_headers);

		// check and process any actions such as bulk actions.
		$this->handle_table_actions();
		// fetch the table data
		$table_data = $this->fetch_table_data();
		// filter the data in case of a search
		if( $user_search_key ) {
			$table_data = $this->filter_table_data( $table_data, $user_search_key );
		}

		$this->items = $table_data; */



		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->fetch_table_data();

 		$perPage = 5;
 		$currentPage = $this->get_pagenum();


 		$i = $currentPage;
		foreach ($data as $k => $reg) {
			$data[$k]['num'] = $i;
			$i++;
		}

		//usort( $data, array( &$this, 'sort_data' ) );

		$totalItems = count($data);

		$this->set_pagination_args( array(
		    'total_items' => $totalItems,
		    'per_page'    => $perPage
		) );

		$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

		//var_dump($currentPage, $data);

		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $data;

		// rest of the code
	}


	function column_name( $item ) {
		$title = '<strong>' . $item['display_name'] . '</strong>';
		return $title;
	}


	public function fetch_table_data() {

	     global $wpdb;

		 $pagina = trim($_GET['paged']);

	     $wpdb_table = $wpdb->prefix . 'users';
	     $orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'user_registered';
	     $order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'ASC';

	     $view = trim($_GET['view']);
	     $tipo = '';

	     if($view != ''){	

	     	if($view == 'facturas'){
	     		$tipo = \vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA;
	     	}

	     	if($view == 'boletas'){
	     		$tipo = \vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA;
	     	}

	     }

	     $params = array('tipo_de_comprobante' => $tipo,
	     			 	 'codigo' => trim($_GET['s']),
	     				 'fecha_de_emision' => trim($_GET['fecha_de_emision']),
	     				 'documento_del_cliente' => trim($_GET['documento_del_cliente']) );

   		 $query_results = $this->objDAO->get($params);

	     return $query_results;
   }


	public function get_columns() {

		$table_columns = array(
			'cb'		=> '<input type="checkbox" />', // to display the checkbox.
			'num'	=>   '#',
			'codigo'	=>  'Código',
			'fecha' =>  'Fecha',
			'cliente'		=> 'Cliente',
			'cliente_documento' => 'Cliente Documento',
			'descripcion' => 'Descripción',
			'monto' => 'Monto',
			'estado' => 'Estado'
		);

		return $table_columns;

	}

	public function column_cb( $item ) {
		return sprintf(
		'<label class="screen-reader-text" for="user_' . $item['id'] . '">' . sprintf( __( 'Select %s' ), $item['user_login'] ) . '</label>'
		. "<input type='checkbox' name='users[]' id='user_{$item['id']}' value='{$item['id']}' />"
		);
	}


	public function column_codigo( $item ){

		$codigo = $item['serie'].'-'.str_pad( $item['numero'], 4, "0", STR_PAD_LEFT);

		return "<a href='".admin_url()."admin.php?page=detalle-comprobante&comprobante=".$item['id']."'> ".$codigo." </a>";

	}

	public function get_hidden_columns()
	{
	    return array();
	}


	public function get_sortable_columns()
	{
	    return array('tipo' => array('tipo', false));
	}


	public function column_default( $item, $column_name )
	{


        $order = wc_get_order( $item['ref_id'] );
        $order_data = $order->get_data();



	    switch( $column_name ) {

	        case 'num':
	        	return $item['num'];

	        case 'codigo':
	        	return  $item['serie'].'-'.str_pad( $item['numero'], 4, "0", STR_PAD_LEFT);

	        case 'fecha':
	        	return $item['fecha_de_emision'];

	        case 'cliente':
	        	return $item['cliente_denominacion'];

	        case 'cliente_documento':
	        	return $item['cliente_numero_de_documento'];

	        case 'descripcion':
	        	return 'Pedido: '.$item['ref_id'].' <br/> '.$order_data['payment_method_title'];

	        case 'monto':
	            return $item[ 'total' ];

	        default:
	        	return '';
	    }
	}


	// filter the table data based on the search key
	public function filter_table_data( $table_data, $search_key ) {

		$filtered_table_data = array_values( array_filter( $table_data, function( $row ) use( $search_key ) {

			foreach( $row as $row_val ) {
				if( stripos( $row_val, $search_key ) !== false ) {
					return true;
				}
			}

		} ) );

		return $filtered_table_data;
	}


	public function no_items(){

		 echo 'No hay comprobantes registrados';
	}


	public function extra_tablenav( $which ) {


	   if ( $which == "top" ){

	   	?>

	   		<label for="vexfe_fecha_de_emision"> Fecha de emisión: </label>
			<input id="vexfe_fecha_de_emision" name="fecha_de_emision" type="date" value="<?PHP echo trim($_GET['fecha_de_emision']) ?>" />


	   		<label for="vexfe_documento_del_cliente"> Documento del cliente: </label>
			<input id="vexfe_documento_del_cliente" name="documento_del_cliente" type="text" value="<?PHP echo trim($_GET['documento_del_cliente']) ?>" />

	        <button id="vexFebtnFiltrar" name="aplicar_filtro_fecha_cliente" class="button"> Aplicar </button>

	   	<?PHP

	   }

	   if ( $which == "bottom" ){

	   }
	}


}
