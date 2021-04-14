<?PHP


namespace vexfacturacionelectronica;


class configDAO{

    public $table = 'vexfe_config'; // No colocar _VEXFE_DB_PREFIX_ porque en los metodos CRUD se agrega automaticamente

    public $_fields = array(

        'id',  
        'ruc',
        'razonsocial',
        'url_envio',
        'token',
        'igv',
        'moneda',
        'unidad_de_medida',
        'estado' 
    );


    public function registrar($params = array()){

        $sql = "UPDATE "._VEXFE_DB_PREFIX_.$this->table." SET estado = 0 ";
        Database::getInstance()->execute($sql);

        $ok = Database::getInstance()->insert( $this->table, $params );

        return ($ok ? $ok : false);

    }

    public function get(){

        $sql = "SELECT * FROM "._VEXFE_DB_PREFIX_.$this->table." WHERE estado = 1 ";

        $results = Database::getInstance()->query($sql);

        return $results[0];

    }  

}
