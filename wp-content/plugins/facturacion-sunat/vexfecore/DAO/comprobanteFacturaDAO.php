<?PHP

namespace vexfacturacionelectronica;

class comprobanteFacturaDAO extends comprobanteDAO{

    public $tipo_de_comprobante = '1';

    public function get($params = array()){
 
    
        $sql = "SELECT comprobante.*
                FROM "._VEXFE_DB_PREFIX_."vexfe_comprobantes comprobante
                WHERE TRUE ";


        $sql.=" AND tipo_de_comprobante = ".$this->tipo_de_comprobante;

        if($params['id'] != ''){
            $sql.=" AND id = ".$params['id'];
        }
 

        if(trim($params['codigo']) != ''){

            if( is_numeric(trim($params['codigo'])) ){

                $sql.=" AND (ref_id = ".trim($params['codigo'])." OR numero = ".trim($params['codigo'])." )";
            }
            else{

            }
 
        }


        if(trim($params['documento_del_cliente']) != ''){

            $sql.=" AND cliente_numero_de_documento = '".trim($params['documento_del_cliente'])."'";

        }

 
        if(trim($params['fecha_de_emision']) != ''){

            $sql.=" AND fecha_de_emision = '".trim($params['fecha_de_emision'])."'";

        }

        if(trim($params['tipo_de_comprobante']) != ''){

            $sql.=" AND tipo_de_comprobante = '".trim($params['tipo_de_comprobante'])."'";

        }
 
        $sql.=" ORDER BY comprobante.fecha_de_emision ASC ";


        $results = Database::getInstance()->query($sql);

        return $results;

    }


    public function getResumenTotales($params = array()){
 

        $sql ="SELECT tipo_de_comprobante, count(*) as total
               FROM wp_vexfe_comprobantes 
               WHERE sys_estado = 1
               GROUP BY tipo_de_comprobante 
               ORDER BY tipo_de_comprobante ";

        $results = Database::getInstance()->query($sql);

        $data = [];

        foreach ($results as $reg) {

            $data[trim($reg['tipo_de_comprobante'])] = $reg['total'];

        } 

        $sql ="SELECT count(*) as total
               FROM wp_vexfe_comprobantes 
               WHERE sys_estado = 1 ";

        list($resultsTotal) = Database::getInstance()->query($sql);
        $total = $resultsTotal['total'];
 
        $comprobantes_resumen = [
            'total' => $total,
            trim(\vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA) => $data[trim(\vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA)] != '' ? $data[trim(\vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_FACTURA)] : 0,
            trim(\vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA) => $data[trim(\vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA)] != '' ? $data[trim(\vexfacturacionelectronica\constants::$TIPOCOMPROBANTE_BOLETA)] : 0 
        ];
 
        return $comprobantes_resumen; 

    }


}
