<?php
session_start();
if(isset($_POST['user_name']) && $_SESSION['auth']['user_admin']==='1')
{
    try
    {
        require_once(DOCUMENT_ROOT.'/class/connectPDO.php');
        $connection = new connectPDO;

        $sql = 'DELETE FROM '.PREFIXTABLA.'_users WHERE user_name = ?';
        $params = array(strtolower($_POST['user_name']));

        $resultado = $connection->exec($sql,$params);
        if($resultado===PDOERROR)
        {
            throw new Exception('Se ha producido un error al intentar eliminar usuario', 1);
        }
        $html = "Se ha eliminado correctamente al usuario {$_POST['user_name']}";
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