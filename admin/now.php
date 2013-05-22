<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
require_once('funciones_comunes.php');
$connection = new connectPDO;
echo json_encode(array('ahora'=>$connection->getone("SELECT CONCAT(CURRENT_DATE(),' ',CURTIME())")));
?>