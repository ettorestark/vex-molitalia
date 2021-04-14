<?PHP


namespace vexfacturacionelectronica;


class serieDAO{

    public $table = 'vexfe_serie';
  
    public function actualizar_correlativo($params = array()){
     
        if( trim($params['serie']) == '' || !is_numeric($params['serie']) ||
            trim($params['correlativo']) == '' || !is_numeric($params['correlativo']) || 
            trim($params['tipo_comprobante']) == '' || !is_numeric($params['tipo_comprobante']) ){

            return false;

        }

        $sql = "UPDATE "._VEXFE_DB_PREFIX_.$this->table." 
                SET serie_numero = '".$params['serie']."', correlativo = '".$params['correlativo']."' 
                WHERE tipo_de_comprobante = '".$params['tipo_comprobante']."' ";
           

        $ok = Database::getInstance()->execute( $sql );

        return ($ok ? $ok : false);

    }


    public function get($params = array()){

        $sql ="SELECT *
               FROM "._VEXFE_DB_PREFIX_.$this->table."
               WHERE tipo_de_comprobante = '".$params['tipo_comprobante']."' AND estado = 1; ";
               
        list($row) = Database::getInstance()->query($sql);
        
        return $row;
    }

    public function siguente_numero_serie_comprobante($params = array()){

        $sql ="SELECT CONCAT( serie_tipo, LPAD(serie_numero,3,0) ) as serie, correlativo
               FROM "._VEXFE_DB_PREFIX_.$this->table."
               WHERE tipo_de_comprobante = '".$params['tipo_comprobante']."' AND estado = 1; ";
        // FOR UPDATE;
 
        $rows = Database::getInstance()->query($sql);
        return array($rows[0]['serie'],$rows[0]['correlativo']);

    }

    public function aumentar_serie_comprobante( $params = array() ){

        $sql = "UPDATE "._VEXFE_DB_PREFIX_.$this->table."
                SET correlativo = correlativo + 1
                WHERE tipo_de_comprobante = '".$params['tipo_comprobante']."' AND estado = 1 ";

        $rs = Database::getInstance()->execute($sql);
        return $rs;

    }
  
}
