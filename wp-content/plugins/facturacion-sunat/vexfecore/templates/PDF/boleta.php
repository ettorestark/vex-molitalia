<?PHP

ob_start();

//= Creación del documento .PDF ================================================

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


$pdf = new PDF('P','cm','Letter');
//$pdf->AliasNbPages();
$pdf->AddPage();
//$pdf->AddFont('IDAutomationHC39M','','IDAutomationHC39M.php');
//$pdf->AddFont('verdana','','verdana.php');
$pdf->SetAutoPageBreak(true);
$pdf->SetMargins(0, 0, 0);
$pdf->SetLineWidth(0.02);
$pdf->SetFillColor(0,0,0);



//$pdf->image( 'vexfe_logo_empresa', 1, 1.2, 6, 1.9);

$pdf->SetFont('','',6);
$pdf->setxy(1, 2.6);
$pdf->Cell(8, 0.25, "", 0, 1,'L', 0);

$pdf->setxy(1,3.1);
$pdf->Cell(8, 0.25, $documento['direccion'], 0, 1,'L', 0);


//$pdf->image("10436146677-01-F002-00000026.png",0.7, 10, 9, 3);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','',10);
$pdf->SetXY(1.2,13);
//$pdf->Cell(8, 0.25, ("Representación impresa del comprobante electrónico."), 0, 1,'C', 0);

// Cuadro principal
//$pdf->RoundedRect(12, 1, 8, 2.8, 0.2, ''); // Cuadro que rodea al RUC del emisor

$pdf->SetTextColor(170,0,0);
$pdf->SetFont('','',12);
$pdf->SetXY(12,1.5);
$pdf->Cell(8, 0.25, $documento['razonsocial'], 0, 1,'C', 0);


$pdf->SetTextColor(170,0,0);
$pdf->SetFont('','',12);
$pdf->SetXY(12,2.2);
$pdf->Cell(8, 0.25, "RUC:".$documento['ruc'], 0, 1,'C', 0);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','B',12);
$pdf->SetXY(12,2.9);
$pdf->Cell(8, 0.25, $documento['tipo_de_comprobante'], 0, 1,'C', 0);


$pdf->SetTextColor(0,0,150);
$pdf->SetFont('','',12);
$pdf->SetXY(12,3.6);
$pdf->Cell(8, 0.25,  $documento['serie'] , 0, 1,'C', 0);
//  Fin del cuadro principal


// Cuadro con los datos del receptor
// $x, $y, $w, $h, $r
$pdf->RoundedRect(1, 4.5, 19, 3.2, 0.2, '');

$alto_linea = 0.5;

$y_ini = 4.7;

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','B',10);

$pdf->SetXY(0, $y_ini);

//$this->pdf->Cell(  140 ,$alto_linea, utf8_decode('AÑO'), $border,0,'R',false);
$margin_left = 1;
$border = '';

$pdf->Cell(($margin_left+0.4), $alto_linea, "", $border, 0,'L', false);
$pdf->Cell(3.4, $alto_linea, "Señores ", $border, 0,'L', false);
$pdf->Cell(0.2, $alto_linea, ": ", $border, 0,'C', false);
$pdf->Cell(8, $alto_linea,  $documento['cliente_denominacion'], $border, 0,'L', false);
$pdf->ln();

$pdf->Cell(($margin_left+0.4), $alto_linea, "", $border, 0,'L', false);
$pdf->Cell(3.4, $alto_linea, "RUC ", $border, 0,'L', false);
$pdf->Cell(0.2, $alto_linea, ": ", $border, 0,'C', false);
$pdf->Cell(8, $alto_linea,  $documento['cliente_ruc'], $border, 0,'L', false);
$pdf->ln();

$pdf->Cell(($margin_left+0.4), $alto_linea, "", $border, 0,'L', false);
$pdf->Cell(3.4, $alto_linea, "Dirección ", $border, 0,'L', false);
$pdf->Cell(0.2, $alto_linea, ": ", $border, 0,'C', false);
$pdf->Cell(8, $alto_linea,  $documento['cliente_direccion'], $border, 0,'L', false);
$pdf->ln();

$pdf->Cell(($margin_left+0.4), $alto_linea, "", $border, 0,'L', false);
$pdf->Cell(3.4, $alto_linea, "Moneda", $border, 0,'L', false);
$pdf->Cell(0.2, $alto_linea, ": ", $border, 0,'C', false);
$pdf->Cell(8, $alto_linea,  $documento['cliente_moneda'], $border, 0,'L', false);
$pdf->ln();

$pdf->Cell(($margin_left+0.4), $alto_linea, "", $border, 0,'L', false);
$pdf->Cell(3.4, $alto_linea, "Fecha de emisión", $border, 0,'L', false);
$pdf->Cell(0.2, $alto_linea, ": ", $border, 0,'C', false);
$pdf->Cell(8, $alto_linea, $documento['fecha_de_emision'], $border, 0,'L', false);


$pdf->ln();
$pdf->ln();

$estructura_detalle = array(
    'codigo' => array(2,'C'),
    'descripcion' => array(8,'L'),
    'unidad' => array(3,'C'),
    'cantidad' => array(2,'C'),
    'precio_unitario' => array(2,'C'),
    'subtotal' => array(2,'C')
);

$pdf->Cell($margin_left, ($alto_linea*2), "", "", 0,'L', false);
$border = 'LTRB';

$pdf->Cell($estructura_detalle['codigo'][0], ($alto_linea*2), "Código", $border, 0,'C', false);
$pdf->Cell($estructura_detalle['descripcion'][0], ($alto_linea*2), "Descripción", $border, 0,'C', false);
$pdf->Cell($estructura_detalle['unidad'][0], ($alto_linea*2), "Unidad", $border, 0,'C', false);
$pdf->Cell($estructura_detalle['cantidad'][0], ($alto_linea*2), "Cantidad", $border, 0,'C', false);
//$pdf->MultiCell(2, ($alto_linea*2), "Precio unitario", $border, 0,'L', false);
$pdf->SetFillColor(255, 255, 127);
//$w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false
$pdf->MultiCell($estructura_detalle['precio_unitario'][0], ($alto_linea*2), "Precio unitario", 1, 'C', 0, 0, '', '', true);

$pdf->Cell($estructura_detalle['subtotal'][0], ($alto_linea*2), "Subtotal", $border, 0,'C', false);

$pdf->ln();




$reseth = true;
$autopadding = true;

$w = 2;

$mc_padding = array(
    'T' => 0.01,
    'R' => 0.1000125,
    'B' => 0.01,
    'L' => 0.1000125
);


$pdf->SetFont('','',10);

foreach ($documento_detalle as $item) {

    $height_min = 0;

    $pdf->Cell($margin_left, $alto_linea, "", "", 0,'L', false);

    foreach ($item as $key => $value) {

        $altura = $pdf->getStringHeight($estructura_detalle[$key][0], $value, $reseth, $autopadding, $mc_padding, $border);
        if($height_min < $altura) $height_min = $altura;
    }


    foreach ($item as $key => $value) {

        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false) {
        $pdf->MultiCell($estructura_detalle[$key][0], $height_min,  $value, 1, $estructura_detalle[$key][1], $fill = false, $ln = 0, $x='', $y='', $reseth=true );
    }


    $pdf->ln();
}






// Total
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','',10);

$pdf->ln();

$Y = 10;
$border='TBRL';


$pdf->Cell( 11.8, $alto_linea, "", "", 0,'R', false);
$pdf->Cell( 5.6, $alto_linea, "Operación gravada:", $border, 0,'R', false);
$pdf->Cell( 2.5, $alto_linea,  number_format($documento['operacion_gravada'],2), $border, 0,'R', false);
$pdf->ln();

$pdf->Cell( 11.8, $alto_linea, "", "", 0,'R', false);
$pdf->Cell( 5.6, $alto_linea, "I.G.V:", $border, 0,'R', false);
$pdf->Cell( 2.5, $alto_linea,  number_format($documento['igv'],2), $border, 0,'R', false);
$pdf->ln();

$pdf->Cell( 11.8, $alto_linea, "", "", 0,'R', false);
$pdf->Cell( 5.6, $alto_linea, "Importe Total:", $border, 0,'R', false);
$pdf->Cell( 2.5, $alto_linea,  number_format($documento['total'],2), $border, 0,'R', false);
$pdf->ln();




$pdf->line(1, 26.5, 20.5, 26.5);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('','',9);

$pdf->SetXY(1,27);

$pdf->MultiCell(19.5, 0.35, ("Representación Impresa del comprobante Electrónico Código Hash: ".$documento['hash_documento']."
Autorizado para ser Emisor electrónico mediante la Resolución de Intendencia N° 0180050002185/SUNAT
Para consultar el comprobante ingresar a : ".$documento['url_consulta_comprobante']), 0, 'C');

//==============================================================================

//$pdf->Output($NomArchPDF, 'F'); // Se graba el documento .PDF en el disco duro o unidad de estado sólido.
//chmod ($NomArchPDF,0777);  // Se dan permisos de lectura y escritura.
//var_dump($NomArchPDF);

$pdf->Output($path_archivo, 'F' ); // Se muestra el documento .PDF en el navegador.



