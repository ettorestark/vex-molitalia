<?PHP
 
spl_autoload_register(function ($clase) {

    $directorios = array(
        '',
        'classes',
        'classes/admin',
        'vexfecore',
        'vexfecore/DAO'

    );
    $classA = explode('\\', $clase);

    $class_name = $classA[ sizeof($classA) -1 ];

    foreach ($directorios as $dir) {

         $path = __DIR__.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.trim($class_name).'.php';

         if (file_exists($path)) { 
             require_once($path);
             return;
         }

    } 

});
