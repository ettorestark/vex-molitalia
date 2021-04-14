<div class="wrap">
  
    <?PHP 
 
    $comprobante_id =  $_GET['comprobante'];


    if(!is_numeric($comprobante_id)) echo '';

    $objDaoComprobante = new vexfacturacionelectronica\comprobanteFacturaDAO();
    $objDaoComprobanteDetalle = new vexfacturacionelectronica\comprobanteDetalleDAO();
 
    list($comprobante) = $objDaoComprobante->get(array('id' => $comprobante_id));

 
    $items = $objDaoComprobanteDetalle->get(array('comprobante_id' => $comprobante_id));


    $tiposDeDocumentoCliente = $objDaoComprobante->getTiposDocumentoCliente();

    $tiposDeComprobantes = $objDaoComprobante->getTiposDeComprobantes();
 


    //var_dump($comprobante, $rsFacturaEnvio);
    //var_dump(expression)_dump($comprobante, $items);

    $respuestaServicio = $comprobante['data_respuesta'];

    $respuesta = [];

    if( trim($respuestaServicio) != '' ){

       $respuesta = json_decode($respuestaServicio, true);

    }   


    ?>


    <div>
        <h2>
            Detalle del comprobante
        </h2>
    </div>

 
    <div class="vex-db-form">
 

    <h2> <?PHP echo $tiposDeComprobantes[$comprobante['tipo_de_comprobante']]; ?>  </h2>

    <div class="fexve-cabecera">
        <a href="<?PHP echo admin_url().'admin.php?page=facturacion-comprobantes' ?>"> <- Ver todos los comprobantes  </a>
    </div>
 
    <div> 

         
    </div>

    <div class="vex-db-buttons">

        <button class="button action ">
            <a href="<?PHP echo admin_url().'post.php?post='.$comprobante['ref_id'].'&action=edit'; ?>">
                <i class="fa fa-first-order"></i> Ver pedido origen
            </a>
       </button>

        <?PHP 
            if($respuesta['enlace_del_pdf']){ 
        ?>
        <button class="button action "> 
            <a href="<?PHP echo $respuesta['enlace_del_pdf']; ?>" target="_blank">
                <i class="fa fa-print"></i> Ver Documento
            </a>
        </button>
 
        <button class="button action ">
            <a href="<?PHP echo $respuesta['enlace_del_xml']; ?>">
                <i class="fa fa-print"></i> Descargar XML
            </a>
        </button>

        <?PHP 
            } 
        ?>


 
    </div>

    <div style="border:1px solid red; padding: 10px; margin: 5px 0; display: none;">
        
        Respuesta_servicio:
        <?PHP 
            print_r($respuestaServicio);
 
        ?>
    </div>

    <table class="">
        <tr height="40">
            <td width="250">
                <span>
                    Tipo de comprobante
                </span>
            </td>
            <td width="30">
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                     <?PHP echo $tiposDeComprobantes[$comprobante['tipo_de_comprobante']]; ?>
                </span>
            </td>
        </tr>
        <tr height="40">
            <td>
                <span>
                    Número
                </span>
            </td>
            <td>
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                     <?PHP echo $comprobante['serie'].'-'.$comprobante['numero'] ?>
                </span>
            </td>
        </tr>
        <tr height="40">
            <td>
                <span>
                    Fecha
                </span>
            </td>
            <td>
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                     <?PHP echo $comprobante['fecha_de_emision']; ?>
                </span>
            </td>
        </tr>
        <tr height="40">
            <td>
                <span>
                    Cliente tipo de documento
                </span>
            </td>
            <td>
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                      <?PHP echo $tiposDeDocumentoCliente[$comprobante['cliente_tipo_de_documento']]; ?>
                </span>
            </td>
        </tr>
        <tr height="40">
            <td>
                <span>
                    Documento N°
                </span>
            </td>
            <td>
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                      <?PHP echo $comprobante['cliente_numero_de_documento']; ?>
                </span>
            </td>
        </tr>
        <tr height="40">
            <td>
                <span>
                    Denominación
                </span>
            </td>
            <td>
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                      <?PHP echo $comprobante['cliente_denominacion']; ?>
                </span>
            </td>
        </tr>

        <tr height="40">
            <td>
                <span>
                    Dirección
                </span>
            </td>
            <td>
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                      <?PHP echo $comprobante['cliente_direccion']; ?>
                </span>
            </td>
        </tr>

        <tr height="40">
            <td>
                <span>
                    Celular
                </span>
            </td>
            <td>
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                      <?PHP echo trim($comprobante['cliente_fono']) != '' ?  $comprobante['cliente_fono'] : '-----'; ?>
                </span>
            </td>
        </tr>

        <tr height="40">
            <td>
                <span>
                    Email
                </span>
            </td>
            <td>
                <span>
                    :
                </span>
            </td>
            <td>
                <span>
                     <?PHP echo trim($comprobante['cliente_email']) != '' ?  $comprobante['cliente_email'] : '-----'; ?>
                </span>
            </td>
        </tr>

    </table>


    <h3>
        Items
    </h3>

    <div>


        <table class="vexfe-table">
            <tr class="header">
                <td width="30"> # </td>
                <td width="200"> Producto </td>
                <td width="80"> Cantidad </td>
                <td width="120"> P.Unitario </td>
                <td width="100"> SubTotal </td>
            </tr>
            <?PHP
            $c = 1;
            foreach ($items as $row) { ?>

                <tr>
                    <td> <?PHP echo $c; ?> </td>
                    <td> <?PHP echo $row['detalle']; ?> </td>
                    <td align="center"> <?PHP echo number_format($row['cantidad'],2); ?> </td>
                    <td align="right"> <?PHP echo number_format($row['precio_unitario'],2); ?> </td>
                    <td align="right"> <?PHP echo number_format($row['subtotal'],2); ?> </td>
                </tr>

            <?PHP
                $c++;
            }
            ?>


        </table>


    </div>



    <div class="vexfe-resumen">

        <table>
            <tr height="30">
                <td width="120">
                    Total gravada
                </td>
                <td width="30">
                    :
                </td>
                <td>
                    <?PHP echo number_format($comprobante['total_gravada'],2); ?>
                </td>
            </tr>
            <tr height="30">
                <td>
                    IGV
                </td>
                <td>
                    :
                </td>
                <td>
                   <?PHP echo number_format($comprobante['total_igv'],2); ?>
                </td>
            </tr>
            <tr height="30">
                <td>
                    TOTAL
                </td>
                <td>
                    :
                </td>
                <td>
                    <?PHP echo number_format($comprobante['total'],2); ?>
                </td>
            </tr>
        </table>


    </div>

    </div>

</div>
