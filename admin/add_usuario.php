<?php
session_start();
if(isset($_POST['user_name']) && $_SESSION['auth']['user_admin']==='1')
{
    try
    {
        if(trim($_POST['user_name'])=='')
        {
            throw new Exception("El nombre de usuario no puede estar vacio", 1);
        }
        require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
        $connection = new connectPDO;

        $sql = 'INSERT INTO '.PREFIXTABLA.'_users (user_name) VALUES (?)';
        $params = array(strtolower($_POST['user_name']));

        $resultado = $connection->exec($sql,$params);
        if($resultado===PDOERROR)
        {
            throw new Exception('Se ha producido un error al intentar crear un usuario', 1);
        }
        $html = 'Se creo sin problemas el nuevo usuario';
        $errores = 0;
    }
    catch(Exception $e)
    {
        $html = $e->getMessage();
        $errores = 1;
    }

    echo json_encode(array('contenido'=>$html,'errores'=>$errores));
}
?>