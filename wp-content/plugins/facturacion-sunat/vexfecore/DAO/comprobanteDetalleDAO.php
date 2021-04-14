<?PHP

namespace vexfacturacionelectronica;

class comprobanteDetalleDAO{

    public $table = 'vexfe_comprobante_detalle';

    public $_fields = array(

        'id',
        'comprobante_id',
        'tipo_de_comprobante',
        'detalle',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'sys_estado'
    );

    public function registrar($params = array()){


        $ok = Database::getInstance()->insert( $this->table, $params );

        return ($ok ? $ok : false);
    }

    public function get($params = array()){

        $sql = "SELECT * FROM "._VEXFE_DB_PREFIX_."vexfe_comprobante_detalle WHERE true ";

        if($params['id'] != ''){
            $sql.=" AND id = ".$params['id'];
        }

        if($params['comprobante_id'] != ''){
            $sql.=" AND comprobante_id = ".$params['comprobante_id'];
        }


        $results = Database::getInstance()->query($sql);
        return $results;

    }

    public function map(){

    }

    public function actualizar(){

    }

}
