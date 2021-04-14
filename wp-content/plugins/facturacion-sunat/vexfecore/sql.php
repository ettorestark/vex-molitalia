<?PHP

namespace vexfacturacionelectronica;


class sql{

    public function getIni(){

    }

    public function reset(){

        $sql = "DELETE FROM ps_vexfe_comprobantes;
                DELETE FROM ps_vexfe_comprobante_detalle;
                DELETE FROM ps_vexfe_log; ";

    }
}
