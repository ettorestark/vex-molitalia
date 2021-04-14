<?PHP
/*
    Esta clase es un Wrapper para los metodos propios de la plataforma
*/

namespace vexfacturacionelectronica;


class Database{


    public static function getInstance()
    {
       static $inst = null;

       if ($inst === null) {
           $inst = new Database();
       }

       return $inst;
    }

    public function query($sql){

        global $wpdb;

        $query_results = $wpdb->get_results( $sql, ARRAY_A  );

        return $query_results;

    }

    public function execute($sql){

        global $wpdb;

        /*
          $wpdb->prepare(
            "
            UPDATE $wpdb->posts
            SET post_parent = %d
            WHERE ID = %d
                AND post_status = %s
            ",
                7, 15, 'static'
        )
        */

        $rs = $wpdb->query($sql);

        return ($rs ? true : false);

    }

    public function insert($table, $params = array()){

        global $wpdb;

        $ok = $wpdb->insert(_VEXFE_DB_PREFIX_.$table, $params);

        return ($ok) ? $wpdb->insert_id : false;

    }

    public function delete(){

    }



    private function __construct() {}

    private function __clone() {}

    private function __sleep() {}

    private function __wakeup() {}

}
