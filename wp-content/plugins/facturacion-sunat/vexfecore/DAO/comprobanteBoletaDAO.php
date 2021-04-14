<?PHP


namespace vexfacturacionelectronica;


class comprobanteBoletaDAO extends comprobanteDAO{

    public $tipo_de_documento = '2';

    public function get($params = array()){


        $sql = "SELECT comprobante.*
                FROM "._VEXFE_DB_PREFIX_."vexfe_comprobantes comprobante
                WHERE TRUE ";

        $sql.=" AND tipo_de_comprobante = ".$this->tipo_de_documento;

        if($params['id'] != ''){
            $sql.=" AND id = ".$params['id'];
        }

        $results = Database::getInstance()->query($sql);

        return $results;

    }


}
