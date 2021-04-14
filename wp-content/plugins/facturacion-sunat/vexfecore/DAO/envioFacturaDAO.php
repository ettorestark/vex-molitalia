<?PHP

namespace vexfacturacionelectronica;


class envioFacturaDAO{


    public $table = 'vexfe_envios_facturas';


    public $_fields = array(

        'id',
        'comprobante_id',
        'fecha_hora',
        'codigo_sunat',
        'sunat_estado',
        'resumen_respuesta'
    );


    public function registrar($params = array()){

        $ok = Database::getInstance()->insert( $this->table, $params );

        return ($ok ? $ok : false);
    }


    public function get($params = array()){


        $sql = "SELECT * FROM "._VEXFE_DB_PREFIX_."vexfe_envios_facturas WHERE true ";


        if( trim($params['comprobante_id']) != ''){
            $sql.=" AND comprobante_id = ".$params['comprobante_id'];
        }

        if( trim($params['estado_sunat']) != ''){
            $sql.=" AND estado_sunat = ".$params['estado_sunat'];
        }

        if( trim($params['id']) != ''){
            $sql.=" AND id = ".$params['id'];
        }

        if($params['get_ultmo'] === true){
            $sql.=" ORDER BY fecha_hora DESC LIMIT 1";
        }

        $query_results = Database::getInstance()->query( $sql );

        return $query_results;

    }

   //  public function_exists(function_name)

}
