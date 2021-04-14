<?PHP

namespace vexfacturacionelectronica;

class comprobanteDAO{

    public $tipo_de_comprobante = '';

    public $table = 'vexfe_comprobantes';

    private $tipos_de_documentos = array(

        '1' => 'FACTURA',
        '2' => 'BOLETA',
        '3' => 'NOTA DE CRÉDITO',
        '4' => 'NOTA DE DÉBITO'

    );

    private $tipos_de_comprobantes = array(

        '2' => 'BOLETA',
        '1' => 'FACTURA'

    );

    private $tipo_de_documento_cliente = array(

       // '-'  => 'VARIOS',
        '6'  => 'RUC',
        '1'  => 'DNI',
        '4'  => 'CARNET DE EXTRANJERÍA',
        '7'  => 'PASAPORTE',
        'A'  => 'CÉDULA DIPLOMÁTICA DE IDENTIDAD',
        '0'  => 'NO DOMICILIADO, SIN RUC (EXPORTACIÓN)'

    );

    public function getTiposDocumentoCliente(){

        return $this->tipo_de_documento_cliente;

    }

    public function getTiposDeDocumentos(){

        return $this->tipos_de_documentos;

    }

    public function getTiposDeComprobantes(){

        return $this->tipos_de_comprobantes;

    }


    public $_fields = array(

        'ref_tipo',
        'ref_id',
        'tipo_de_comprobante',
        'serie',
        'numero',
        'cliente_tipo_de_documento',
        'cliente_numero_de_documento',
        'cliente_denominacion',
        'cliente_direccion',
        'fecha_de_emision',
        'moneda',
        'porcentaje_de_igv',
        'total_gravada',
        'total_igv',
        'total'
    );

    public function registrar($params = array()){

        $ok = Database::getInstance()->insert( $this->table, $params );

        return ($ok ? $ok : false);
    }

    public function get($params = array()){
 
        $sql = "SELECT comprobante.*
                FROM "._VEXFE_DB_PREFIX_."vexfe_comprobantes comprobante
                WHERE TRUE ";

        if($params['tipo_de_comprobante'] != ''){
            $sql.=" AND tipo_de_comprobante = ".$params['tipo_de_comprobante'];
        }

        if($params['id'] != ''){
            $sql.=" AND id = ".$params['id'];
        }

        if($params['ref_id'] != ''){
            $sql.=" AND ref_id = ".$params['ref_id'];
        }

        $results = Database::getInstance()->query($sql);
        return $results;

    }

    public function map(){

    }

    public function actualizar(){

    }

    public function actualizar_respuesta_sunat( $params = array() ){


        $sql = "UPDATE "._VEXFE_DB_PREFIX_."vexfe_comprobantes
                SET sunat_respuesta_envio = '".$params['sunat_respuesta_envio']."'
                WHERE id = '".$params['comprobante_id']."' ";


        $row = Database::getInstance()->execute($sql);
        return $row;
    }
    
    public function actualizar_respuesta_data( $params = array() ){


        $sql = "UPDATE "._VEXFE_DB_PREFIX_."vexfe_comprobantes
                SET data_respuesta = '".$params['data']."'
                WHERE id = '".$params['comprobante_id']."' ";


        $row =  Database::getInstance()->execute($sql);
        return $row;
    }

    public function generar_comprobante_impreso($comprobante_id){

        require_once _VEXFE_MODULE_DIR_.'/libs/TCPDF-6.2.13/tcpdf.php';

        $datosEnvio = array();


        $objComprobante = new comprobanteFacturaDAO();
        list($infoComprobante) = $objComprobante->get(array('id' => $comprobante_id ));

        $objDetalleComprobante = new comprobanteDetalleDAO();
        $detalleComprobante = $objDetalleComprobante->get(array('comprobante_id' => $comprobante_id ));

        $objConfig = new configDAO();
        $objConfigInfo = $objConfig->get();

        $logo_name = $objConfigInfo['logo'];
        $certificado_name = $objConfigInfo['certificado'];

        $logo_url = '';

        $codigo =  $infoComprobante['serie'].'-'.$infoComprobante['numero'];

        $documento = array(
                           'tipo_de_comprobante' => 'FACTURA',
                           'razonsocial' => $objConfigInfo['razonsocial'],
                           'ruc' => $objConfigInfo['ruc'],
                           'direccion' => $objConfigInfo['direccion'],
                           'serie' => $codigo,
                           'cliente_denominacion' => $infoComprobante['cliente_denominacion'],
                           'cliente_ruc' => $infoComprobante['cliente_numero_de_documento'],
                           'cliente_direccion' =>  $infoComprobante['cliente_direccion'],
                           'cliente_moneda' =>  $infoComprobante['moneda'],
                           'fecha_de_emision' =>  $infoComprobante['fecha_de_emision'],
                           'operacion_gravada' => $infoComprobante['total_gravada'],
                           'igv' => $infoComprobante['total_igv'],
                           'total' => $infoComprobante['total'],
                           'url_consulta_comprobante' => 'https://portal.vexsoluciones.pe/appefacturacion',
                           'hash_documento' => md5('2132173') );


        $documento_detalle = array();
        $cod = 1;
        foreach ($detalleComprobante as $reg) {

            $row = array(
                'codigo' => $cod,
                'descripcion' => $reg['detalle'],
                'unidad' => $reg['unidad'],
                'cantidad' => $reg['cantidad'],
                'precio_unitario' => $reg['precio_unitario'],
                'subtotal' => $reg['subtotal']
            );

            array_push($documento_detalle, $row );
            $cod++;
        }


        $pdf_nombre = $objConfigInfo['ruc'].'-'.$codigo.'.pdf';

        $path_archivo = _VEXFE_DIR_DOCUMENTOS_.DIRECTORY_SEPARATOR.$pdf_nombre;
        $path_logo = _VEXFE_DIR_CONFIG_FILES_.DIRECTORY_SEPARATOR.$logo_name;

        require_once _VEXFE_MODULE_DIR_.'/views/documentos/factura.php';

    }


    public function getResumenTotales($params = array()){

        return array();
    }

    public function eliminar($comprobante_id = ''){

        $sql = "DELETE FROM wp_vexfe_comprobante_detalle WHERE comprobante_id = '".$comprobante_id."'";
        Database::getInstance()->execute($sql); 

        $sql = "DELETE FROM wp_vexfe_comprobantes WHERE id = '".$comprobante_id."'";
        Database::getInstance()->execute($sql); 

    }

}
