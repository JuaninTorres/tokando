<?php
session_start();
if(isset($_SESSION['auth']))
{
    require_once('funciones_comunes.php');
    $locutor = getLocutorOnline();
    echo json_encode(array(
        'contenido' => $locutor['contenido'],
        'jscall' => $locutor['jscall'],
        ));
}
?>