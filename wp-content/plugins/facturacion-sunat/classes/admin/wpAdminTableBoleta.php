<?PHP

namespace vexfacturacionelectronica;


class wpAdminTableBoleta extends wpAdminTableComprobante{


	public function get_bulk_actions() {

		  $actions = [
	 		 'bulk-envio' => 'Generar resumen diario'
		  ];

		  return $actions;
	}


	protected function get_views() {



		$objDaoBoleta = new comprobanteBoletaDao();

 		list($rs) = $objDaoBoleta->getResumenTotales();

 		$total = $rs['total_boletas'] == '' ? 0 : $rs['total_boletas'];
 		$enviadas = $rs['enviadas'] == '' ? 0 : $rs['enviadas'];


 		$pendientes = $total - $enviadas;

 		$url = admin_url("admin.php?page=facturacion-comprobantes&tab=boletas");

	    $status_links = array(
	        "all"       => __("<a href='".$url."'>Ver todos (".$total.") </a>",'my-plugin-slug'),
	        "published" => __("<a href='".$url."&view=enviados'>Enviados (".$enviadas.")</a>",'my-plugin-slug'),
	        "trashed"   => __("<a href='".$url."&view=pendientes'>Pendientes (".$pendientes.")</a>",'my-plugin-slug')
	    );
	    return $status_links;
	}


	public function get_columns() {

		$table_columns = array(
			'cb'		=> '<input type="checkbox" />', // to display the checkbox.
			'num'	=>   '#',
			'codigo'	=>  'Código',
			'fecha' =>  'Fecha',
			'cliente'		=> 'Cliente',
			'descripcion' => 'Descripción',
			'monto' => 'Monto',
			'estado' => 'Estado'
		);

		return $table_columns;

	}

	public function column_cliente($item){


	   return "<div> DNI: <b> ".$item['cliente_numero_de_documento']." </b> <br/>  Nombre completo: <b>".$item['cliente_denominacion']." </b> <br/> Dirección: <b> ".$item['cliente_direccion']." </b> <br/>  </div>  ";

	}

	public function column_estado($item){


		if(  intval($item['enviado']) == '1' )
		{

			return 'Enviado';

		}
		else
		{

			return 'Pendiente';
		}

	}

}
