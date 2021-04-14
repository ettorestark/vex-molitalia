
<?PHP

$tipo_comprobante = '2';

$serieObj = new vexfacturacionelectronica\serieDao();

//$enviosObj = new vexfacturacionelectronica\envioBoletaDao();

list($serie,$numero) = $serieObj->siguente_numero_serie_comprobante(array('tipo_comprobante' => $tipo_comprobante));

$objDaoComprobante = new vexfacturacionelectronica\comprobanteBoletaDao();

$comprobante_table = new vexfacturacionelectronica\wpAdminTableBoleta($objDaoComprobante);

$ultimoEnvio = array();
?>

<h2>
	Boletas generadas
</h2>

<div style="margin-bottom: 10px;">
	<b>	Serie actual: </b>

	<?PHP echo $serie.'-'.$numero; ?>

	<b>	último envio: </b><?PHP echo $ultimoEnvio['fecha_hora'] == '' ? '-----' : vexfe_fecha_amigable($ultimoEnvio['fecha_hora']); ?>
</div>


<div id="panel_activo_table">


	<?PHP
		$comprobante_table->views();
	?>


	<form id="nds-user-list-form" method="get">

		<input type="hidden" name="tab" value="<?PHP echo $_GET['tab']; ?>" />

		<?PHP


		$comprobante_table->prepare_items();

	    $comprobante_table->search_box( 'Buscar por comprobante o pedido', 'nds-user-find');
		$comprobante_table->display();

		?>

		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />

	</form>

</div>

<div style="margin-top: 20px;">

	<?PHP

 	$url = admin_url("admin.php");


	?>

		<input type="hidden" name="page" value="<?PHP echo $_GET['page']; ?>" />
		<input type="hidden" name="tab" value="<?PHP echo $_GET['tab']; ?>" />

		<span> Generar resumen diario: </span>

		<input type="date" name="resumen_diario_fecha" value="<?PHP echo $_GET['resumen_diario_fecha']; ?>">

		<button id="btnGenerarResumenDiario" class="button action"> Visualizar	</button>


		<div id="vexfeResultadoVisualizarComprobante">

		</div>

</div>
