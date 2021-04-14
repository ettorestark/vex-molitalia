<?PHP


namespace vexfacturacionelectronica;


class bajasDAO{

    public $table = 'vexfe_bajas'; // No colocar _DB_PREFIX_ porque en los metodos CRUD se agrega automaticamente

    public $_fields = array(

        'id',
        'comprobante_id',
        'serie',
        'numero',
        'fecha_hora',
        'motivo',
        'sunat_respuesta',
        'estado'
    );


    public function registrar($params = array()){


        $ok = Db::getInstance()->insert( $this->table, $params );

        return ($ok ? Db::getInstance()->insert_ID() : false);

    }


}
