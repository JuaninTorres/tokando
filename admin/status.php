<?php
session_start();
$logueado = (isset($_SESSION['auth']))?1:0;
echo json_encode(array('logueado'=>$logueado));
?>