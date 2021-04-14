<?PHP
ob_start();
require_once(_VEXFE_MODULE_DIR_.'/libs/TCPDF-6.2.13/tcpdf.php');

$upload_dir   = wp_upload_dir();

$order = wc_get_order( $order_id );

if(isset($order_id)){

    $envio_pdf = 'I';
}
else
{
    $order = $_POST['order'];
    $envio_pdf = 'I';
}



$order_meta = get_post_meta($order_id);


$order_data = $order->get_data();

$order_date_created = $order_data['date_created']->date('Y-m-d H:i:s');

$detalle = $order->get_items();



$solicitar_factura = $order_meta['solicitar_factura'][0];
$tipo = ($solicitar_factura == '1') ? 'F' : 'B';

$FecEmi = $order_date_created;    // Fecha de emisión.

if($tipo == 'F'){
	// Variables correspondientes a la factura.
	$RUC = $order_meta['ruc'][0];       // RUC.
	$NomRazSoc = $order_meta['nombre_fiscal'][0]; // Nombre o Razón social.
	$Domicilio =  $order_meta['direccion_fiscal'][0]; // Domicilio.

}
else
{
	$order_billing_first_name = $order_data['billing']['first_name'];
	$order_billing_last_name = $order_data['billing']['last_name'];
	$order_billing_address_1 = $order_data['billing']['address_1'];
	$NomRazSoc = $order_billing_first_name.' '.$order_billing_last_name;
	$Domicilio = $order_billing_address_1;
}





$CodHash = md5('Dekorwebclickx');   // Código Hash.
$TipoDoc = "";   // Tipo de documento.
$TotGrav = 0;    // Total gravado.
$TotIGV = 0;     // Total IGV.
$TotMonto = 0;   // Total importe.

// Variables correspondientes a los productos o servicios de la factura.

$CodProdServ = ""; // Código.
$ProdServ = ""; // Descripción.
$Cant = 0; // Cantidad.
$UniMed = ""; // Unidad de medida.
$Precio = 0; // Precio unitario.
$Importe = 0;  // Importe.




//= Creación del documetno .PDF ================================================

class PDF extends TCPDF{

    function Header(){

    }

    function Footer(){

        $this->SetTextColor(0,0,0);
        $this->SetFont('','',12);
        $this->SetXY(18,26.2);
      //  $this->Cell(0.8, 0.25, ("Pág. ").$this->PageNo().' de {nb}', 0, 1,'L', 0);
    }
}

$upload_dir = wp_upload_dir();
$baseDir = $upload_dir['basedir'].'/facturacion_vex';

$NomArchPDF = $baseDir."/facturas/".md5($order_id).".pdf";


$pdf= new PDF('P','cm','Letter');
//$pdf->AliasNbPages();
$pdf->AddPage();
//$pdf->AddFont('IDAutomationHC39M','','IDAutomationHC39M.php');
//$pdf->AddFont('verdana','','verdana.php');
$pdf->SetAutoPageBreak(true);
$pdf->SetMargins(0, 0, 0);
$pdf->SetLineWidth(0.02);
$pdf->SetFillColor(0,0,0);


/*
    update_option( 'vexfe_ruc', sanitize_text_field( $_POST['ruc'] ), false );
        update_option( 'vexfe_razon_social', sanitize_text_field( $_POST['razon_social'] ), false );
        update_option( 'vexfe_nombre_comercial', sanitize_text_field( $_POST['nombre_comercial'] ), false );
        update_option( 'vexfe_direccion_fiscal', sanitize_text_field( $_POST['direccion_fiscal'] ), false );
        update_option( 'vexfe_departamento', sanitize_text_field( $_POST['departamento'] ), false );
        update_option( 'vexfe_provincia', sanitize_text_field( $_POST['provincia'] ), false );
        update_option( 'vexfe_distrito', sanitize_text_field( $_POST['distrito'] ), false );

        update_option( 'vexfe_usuario_sol', sanitize_text_field( $_POST['usuario_sol'] ), false );
        update_option( 'vexfe_clave_sol', sanitize_text_field( $_POST['clave_sol'] ), false );

        update_option( 'vexfe_clave_certificado', sanitize_text_field( $_POST['clave_certificado'] ), false );

*/



$pdf->image(get_option('vexfe_logo_empresa'), 1, 1.2, 6, 1.9);

$pdf->SetFont('','',6);
$pdf->setxy(1, 2.6);
$pdf->Cell(8, 0.25, "", 0, 1,'L', 0);

$pdf->setxy(1,3.1);
$pdf->Cell(8, 0.25, get_option('vexfe_direccion_fiscal'), 0, 1,'L', 0);


//$pdf->image("10436146677-01-F002-00000026.png",0.7, 10, 9, 3);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','',10);
$pdf->SetXY(1.2,13);
//$pdf->Cell(8, 0.25, ("Representación impresa del comprobante electrónico."), 0, 1,'C', 0);

$pdf->RoundedRect(12, 1, 8, 2.5, 0.2, '');

$pdf->SetTextColor(170,0,0);
$pdf->SetFont('','',14);
$pdf->SetXY(12,1.5);
$pdf->Cell(8, 0.25, "RUC:".get_option('vexfe_ruc'), 0, 1,'C', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','B',14);
$pdf->SetXY(12,2.2);
$pdf->Cell(8, 0.25, ($tipo == 'F' ? "FACTURA ELECTRÓNICA" : "BOLETA DE VENTA"), 0, 1,'C', 0);


$pdf->SetTextColor(0,0,150);
$pdf->SetFont('','',14);
$pdf->SetXY(12,2.9);
$pdf->Cell(8, 0.25, $tipo."002-".substr($order_id, 2, 3), 0, 1,'C', 0);


$pdf->RoundedRect(1, 4, 19, 3.2, 0.2, '');

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','B',10);

$pdf->SetXY(1.1,4.2);
$pdf->Cell(1, 0.35, ("Señores: "), 0, 1,'L', 0);

$pdf->SetXY(1.1,4.2+0.6);
$pdf->Cell(1, 0.35, ("Dirección: "), 0, 1,'L', 0);

if($tipo == 'F'){

	$pdf->SetXY(1.1,4.2+(0.6*2));
	$pdf->Cell(1, 0.35, ("RUC: "), 0, 1,'L', 0);

	$pdf->SetXY(1.1,4.2+(0.6*3));
	$pdf->Cell(1, 0.35, ("Fecha de emisión: "), 0, 1,'L', 0);


	$pdf->SetXY(1.1,4.2+(0.6*4));
	$pdf->Cell(1, 0.35, ("Moneda: "), 0, 1,'L', 0);

}
else{

	$pdf->SetXY(1.1,4.2+(0.6*2));
	$pdf->Cell(1, 0.35, ("Fecha de emisión: "), 0, 1,'L', 0);

	$pdf->SetXY(1.1,4.2+(0.6*3));
	$pdf->Cell(1, 0.35, ("Moneda: "), 0, 1,'L', 0);

}



$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','',10);

$pdf->SetXY(4.7,4.2);
$pdf->Cell(1, 0.35, ($NomRazSoc), 0, 1,'L', 0);

$pdf->SetXY(4.7,4.2+0.6);
$pdf->Cell(1, 0.35, ($Domicilio), 0, 1,'L', 0);

if($tipo == 'F'){

	$pdf->SetXY(4.7,4.2+(0.6*2));
	$pdf->Cell(1, 0.35, ($RUC), 0, 1,'L', 0);

	$pdf->SetXY(4.7,4.2+(0.6*3));
	$pdf->Cell(1, 0.35, ($FecEmi), 0, 1,'L', 0);

	$pdf->SetXY(4.7,4.2+(0.6*4));
	$pdf->Cell(1, 0.35, ("SOL"), 0, 1,'L', 0);

}
else
{

	$pdf->SetXY(4.7,4.2+(0.6*2));
	$pdf->Cell(1, 0.35, ($FecEmi), 0, 1,'L', 0);

	$pdf->SetXY(4.7,4.2+(0.6*3));
	$pdf->Cell(1, 0.35, ("SOL"), 0, 1,'L', 0);

}

$X = 0;
$Y = 0;

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','',10);

$pdf->SetXY($X+1,$Y+8);
$pdf->Cell(2.5, 0.5, ("Código"), 1, 1,'L', 0);

$pdf->SetXY($X+3.5,$Y+8);
$pdf->Cell(6.65, 0.5, ("Descripción"), 1, 1,'L', 0);

$pdf->SetXY($X+10.15,$Y+8);
$pdf->Cell(2, 0.5, ("Cantidad"), 1, 1,'C', 0);

$pdf->SetXY($X+12.15,$Y+8);
$pdf->Cell(2.65, 0.5, ("Unidad"), 1, 1,'C', 0);

$pdf->SetXY($X+14.8,$Y+8);
$pdf->Cell(2.7, 0.5, ("Precio unitario"), 1, 1,'R', 0);

$pdf->SetXY($X+17.5,$Y+8);
$pdf->Cell(2.5, 0.5, ("Precio venta"), 1, 1,'R', 0);


$total = 0;
$Y+=8;
foreach ($detalle as $item) {

	$item_id = $item->get_id();

	$item_data = $item->get_data();

    $product_name = $item_data['name'];
    $product_id = $item_data['product_id'];
    $variation_id = $item_data['variation_id'];
    $quantity = $item_data['quantity'];
    $tax_class = $item_data['tax_class'];
    $line_subtotal = $item_data['subtotal'];
    $line_subtotal_tax = $item_data['subtotal_tax'];
    $line_total = $item_data['total'];
    $line_total_tax = $item_data['total_tax'];

    $UniMed = 'Paquete';

	$Y = $Y + 0.8;

	$pdf->SetXY($X+1,$Y);
	$pdf->Cell(2.5, 0.8, ($product_id), 1, 1,'L', 0);
	$pdf->SetXY($X+3.5,$Y);
	$pdf->Cell(6.65, 0.8, substr($product_name, 0, 30), 1, 1,'L', 0);

	$pdf->SetXY($X+10.15,$Y);
	$pdf->Cell(2, 0.8, ($quantity), 1, 1,'C', 0);

	$pdf->SetXY($X+12.15,$Y);
	$pdf->Cell(2.65, 0.8, ($UniMed), 1, 1,'L', 0);



	$pdf->SetXY($X+14.8,$Y);
	$pdf->Cell(2.7, 0.8, number_format($line_subtotal,2), 1, 1,'R', 0);

	$pdf->SetXY($X+17.5,$Y);
	$pdf->Cell(2.5, 0.8, number_format($line_subtotal,2), 1, 1,'R', 0);

	$total+=$line_subtotal;

}

$Y+=2;

$sinIgv = $total/118 * 100;
$igv = $total - $sinIgv;

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','',10);

$pdf->SetXY(9.9,$Y);
$pdf->Cell(7.6, 0.5, ("Total Valor de Venta: "), 1, 1,'R', 0);

$pdf->SetXY(17.5,$Y);
$pdf->Cell(2.5, 0.5, number_format($sinIgv,2), 1, 1,'R', 0);

$pdf->SetXY(9.9,$Y+0.5);
$pdf->Cell(7.6, 0.5, ("IGV:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,$Y+0.5);
$pdf->Cell(2.5, 0.5, number_format($igv,2), 1, 1,'R', 0);

$pdf->SetXY(9.9,$Y+(0.5*2));
$pdf->Cell(7.6, 0.5, ("Importe Total:"), 1, 1,'R', 0);

$pdf->SetXY(17.5,$Y+(0.5*2));
$pdf->Cell(2.5, 0.5, number_format($total,2), 1, 1,'R', 0);

$pdf->line(1, 24.8, 20.5, 24.8);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','',9);
$pdf->SetXY(1,25);
$pdf->MultiCell(19.5, 0.35, ("Representación Impresa del comprobante Electrónico Código Hash: $CodHash
Autorizado para ser Emisor electrónico mediante la Resolución de Intendencia N° 0180050002185/SUNAT
Para consultar el comprobante ingresar a : https://portal.vexsoluciones.pe/appefacturacion"), 0, 'C');

//==============================================================================

//$pdf->Output($NomArchPDF, 'F'); // Se graba el documento .PDF en el disco duro o unidad de estado sólido.
//chmod ($NomArchPDF,0777);  // Se dan permisos de lectura y escritura.
//var_dump($NomArchPDF);

$pdf->Output($NomArchPDF, $envio_pdf ); // Se muestra el documento .PDF en el navegador.



