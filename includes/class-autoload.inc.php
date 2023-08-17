<?php 
spl_autoload_register('myAutoLoader');
spl_autoload_register('controllersLoader');

function myAutoLoader($className){
    $path = RPATH ."/classes/";
    $extension = ".class.php";
    $fullPath = $path . $className . $extension;
    
    if(file_exists($fullPath)){
        include_once $fullPath;
    }else{
        return false;
    }
}
function controllersLoader($className){
    $path = RPATH ."/controllers/";
    $extension = ".ctrl.php";
    $fullPath = $path . $className . $extension;
    
    if(file_exists($fullPath)){
        include_once $fullPath;
    }else{
        return false;
    }
}

?>