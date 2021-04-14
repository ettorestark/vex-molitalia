<?PHP 


$objConfig = new vexfacturacionelectronica\configDAO();
  
$configInfo = $objConfig->get();

 
$objSerie = new vexfacturacionelectronica\serieDAO();
  
$serieFacturaInfo = $objSerie->get(['tipo_comprobante' => vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA ]);

$serieBoletaInfo = $objSerie->get(['tipo_comprobante' => vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA ]);


$infoSerieActual = $objSerie->siguente_numero_serie_comprobante(['tipo_comprobante' => vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA ]);
$serieFacturaActual = $infoSerieActual[0].'-'.str_pad($infoSerieActual[1], 4, "0",STR_PAD_LEFT); 

$infoSerieActual = $objSerie->siguente_numero_serie_comprobante(['tipo_comprobante' => vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA ]);
$serieBoletaActual = $infoSerieActual[0].'-'.str_pad($infoSerieActual[1], 4, "0",STR_PAD_LEFT); 
  
?>

<div class="wrap">
    <h2> Facturación electrónica Nubefact - Configuración </h2>
</div>

<?PHP

if( isset($_GET['result']) && $_GET['result'] == '1'){

?>

<div class="alert alert-success">
  <strong>Éxito!</strong> parámetros actualizados
</div>

<?PHP
}
?>


<?PHP

if(isset($_GET['result']) && $_GET['result'] == '0'){

?>

<div class="alert alert-danger">
  <strong> No se pudieron actualizar los datos </strong>  <?PHP echo (isset($_GET['mensaje']) && trim($_GET['mensaje']) !=  '') ? ' - '.sanitize_text_field($_GET['mensaje']) : ' '; ?>
</div>

<?PHP
}
?>
  
<div>

    <form method="post" action="<?PHP echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">

        <input type="hidden" name="action" value="vex_guardar_config">

        <div>
            <h2> Datos de la empresa </h2>
        </div>

        <table> 

            <tr>
                <td width="140">
                    <span> RUC </span>
                </td>
                <td width="10">
                    :
                </td>
                <td>
                    <input type="text" name="ruc" value="<?PHP echo $configInfo['ruc']; ?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <span> Razón social </span>
                </td>
                <td>
                    :
                </td>
                <td>
                    <input type="text" name="razon_social" value="<?PHP echo $configInfo['razonsocial']; ?>" style="width: 350px;" />
                </td>
            </tr>
            <tr>
                <td>
                    <span> Dirección fiscal </span>
                </td>
                <td>
                    :
                </td>
                <td>
                    <input type="text" name="direccionfiscal" value="<?PHP echo $configInfo['direccionfiscal']; ?>" style="width: 350px;" />
                </td>
            </tr>
           
        </table>

        <div>
            <h2>
                Parámetros generales
            </h2>
        </div>

        <table>
            <tr>
                <td>
                    <span> Valor del IGV </span>
                </td>
                <td>
                    :
                </td>
                <td>
                    <input type="text" name="igv" value="<?PHP echo $configInfo['igv']; ?>"  />
                </td>
            </tr>
            <tr>
                <td>
                    <span> Moneda </span>
                </td>
                <td>
                    :
                </td>
                <td>
                    <input type="text" name="moneda" value="<?PHP echo $configInfo['moneda']; ?>"  />
                </td>
            </tr> 
            <tr>
                <td>
                    <span> Unidad de medida por defecto </span>
                </td>
                <td>
                    :
                </td>
                <td>
                    <input type="text" name="unidad_de_medida" value="<?PHP echo $configInfo['unidad_de_medida']; ?>"  />
                </td>
            </tr> 
            <tr>
                <td>
                    <span> Boleta Serie </span>
                </td>
                <td>
                    :
                </td>
                <td>
                    B<input type="text" name="boleta_serie" value="<?PHP echo $serieBoletaInfo['serie_numero']; ?>" style="width:50px;" />-<input type="text" name="boleta_correlativo" value="<?PHP echo $serieBoletaInfo['correlativo']; ?>" style="width:50px;" /> <?PHP echo $serieBoletaActual; ?>
                </td>
            </tr> 
            <tr>
                <td>
                    <span> Factura serie</span>
                </td>
                <td>
                    :
                </td>
                <td>
                    F<input type="text" name="factura_serie" value="<?PHP echo $serieFacturaInfo['serie_numero']; ?>" style="width:50px;" />-<input type="text" name="factura_correlativo" value="<?PHP echo $serieFacturaInfo['correlativo']; ?>" style="width:50px;" /> <?PHP echo $serieFacturaActual; ?>
                </td>
            </tr> 

        </table>

        <div>
            <h2>
                Parámetros de conexión
            </h2>
        </div>

        <table>
            <tr>
                <td>
                    <span> URL de Envío </span>
                </td>
                <td>
                    :
                </td>
                <td>
                    <input type="text" name="url_envio" value="<?PHP echo $configInfo['url_envio']; ?>" style="width: 350px;"   />
                </td>
            </tr>
            <tr>
                <td>
                    <span> TOKEN </span>
                </td>
                <td>
                    :
                </td>
                <td>
                    <input type="text" name="token" value="<?PHP echo $configInfo['token']; ?>"  style="width: 350px;" />
                </td>
            </tr> 

        </table>

        <div style="margin-top:20px;">

            <input type="submit" value="Guardar datos">
        </div>

    </form>

</div>
