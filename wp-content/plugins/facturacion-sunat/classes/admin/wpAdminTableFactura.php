<?PHP

namespace vexfacturacionelectronica;


class wpAdminTableFactura extends wpAdminTableComprobante{

	protected function get_views() {


		$objDaoFactura = new comprobanteFacturaDao();

 		$resumen = $objDaoFactura->getResumenTotales();

 		$total = $resumen['total'];

 		$url = admin_url("admin.php?page=facturacion-comprobantes&");

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

	    $status_links = array(
	        "all"       => __("<a href='".$url."' ".($tipo == '' ?  "style='font-weight:bold;'": '')." >Ver todos (".$total.") </a>",'my-plugin-slug'),
	        "published" => __("<a href='".$url."&view=facturas' ".($tipo ==  \vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA ?  "style='font-weight:bold;'": '')." > Facturas (".$resumen[\vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA].")</a>",'my-plugin-slug'),
	        "trashed"   => __("<a href='".$url."&view=boletas' ".($tipo == \vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA ?  "style='font-weight:bold;'": '')." > Boletas (".$resumen[\vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA].")</a>",'my-plugin-slug')
	    );
	    return $status_links;
	}


	public function get_columns() {

		$table_columns = array(
			'cb'		=> '<input type="checkbox" />', // to display the checkbox.
			'num'	=>   '#',
			'codigo'	=>  'Código',
			'fecha' =>  'Fecha',
			'cliente'		=> 'Razón social',
			'descripcion' => 'Descripción',
			'monto' => 'Monto' 
		);

		return $table_columns;

	}


	public function column_cliente($item){


  		 return "<div> RUC: <b> ".$item['cliente_numero_de_documento']." </b> <br/>  Denominación: <b>".$item['cliente_denominacion']." </b> <br/> Dirección: <b> ".$item['cliente_direccion']." </b> <br/>  </div>  ";

	} 

}
