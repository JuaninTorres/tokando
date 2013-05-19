<?php
session_start();
echo json_encode(array('ahora'=>date('Y-m-d H:i:s')));
?>