<?php
session_start();
if(isset($_SESSION['auth']))
{
    session_regenerate_id();
    session_destroy();

    echo json_encode(array('msg'=>'Ud. ha terminado su sesion'));
}
?>