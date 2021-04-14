<?PHP

namespace vexfacturacionelectronica;


class logDAO{

    public $table = 'vexfe_log';

    public $_fields = array(

        'id',
        'tipo_objeto',
        'objeto_id',
        'descripcion_operacion',
        'descripcion_respuesta'
    );

    public function registrar($params = array()){

        $ok = Database::getInstance()->insert( $this->table, $params );

        return $ok;
    }

    public function get($params = array()){


         $sql = "SELECT *
                 FROM "._VEXFE_DB_PREFIX_."vexfe_log
                 WHERE TRUE ";

        if($params['comprobante_id'] != ''){
            $sql.=" AND objeto_id = ".$params['comprobante_id'];
        }

        $results = Database::getInstance()->query($sql);
        return $results;

    }

    public function map(){

    }

    public function actualizar(){

    }

}
