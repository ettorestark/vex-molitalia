
<?PHP

$tipo_comprobante = '1';

$serieObj = new vexfacturacionelectronica\serieDAO(); 
 

list($facturaSerie, $facturaNumero) = $serieObj->siguente_numero_serie_comprobante(array('tipo_comprobante' => \vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA ));

list($boletaSerie, $boletaNumero) = $serieObj->siguente_numero_serie_comprobante(array('tipo_comprobante' => \vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA ));


$objDaoComprobante = new vexfacturacionelectronica\comprobanteFacturaDAO();

$comprobante_table = new vexfacturacionelectronica\wpAdminTableFactura($objDaoComprobante);

function vexfe_fecha_amigable($fecha){

	list($fecha, $hora) = explode(' ', $fecha);

	$fecha_ultimo_envio = 'el '.$fecha.' a las '.$hora;

	return $fecha_ultimo_envio;
}
 

?>
 
<div style="margin-bottom: 10px;">

	<table border="0">
		<tr>
			<td width="130">	
				<b>	Serie factura actual</b> 
			</td>
			<td width="10">
				:
			</td>
			<td>
				<?PHP echo $facturaSerie.'-'.str_pad( $facturaNumero, 4, "0", STR_PAD_LEFT); ?>
			</td>
		</tr>
		<tr>
			<td>	
				<b>	Serie boleta actual </b> 
			</td>
			<td>
				:
			</td>
			<td> 
				<?PHP echo $boletaSerie.'-'.str_pad( $boletaNumero, 4, "0", STR_PAD_LEFT); ?>
			</td>
		</tr>
	</table>
 	
</div>
 
<div id="panel_activo_table">

	<?PHP
		$comprobante_table->views();
	?>

	<form id="nds-user-list-form" method="get">

		<input type="hidden" name="tab" value="<?PHP echo $_GET['tab']; ?>" />

		<?PHP


		// $this->user_list_table = new comprobante_table();


		$comprobante_table->prepare_items();

	    $comprobante_table->search_box( 'Buscar por comprobante o pedido', 'nds-user-find');

		$comprobante_table->display();

		?>

		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />

	</form>

</div>
