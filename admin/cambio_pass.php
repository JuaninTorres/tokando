<?php
session_start();

// solo operará si es que
if(isset($_POST))
{
    require_once(DOCUMENT_ROOT.'/class/connectPDO.php');
    $connection = new connectPDO;

    $sql = 'UPDATE '.PREFIXTABLA.'_users SET user_pass = ? WHERE id_user = ?';
    $params = array(md5($_POST['change_pass_1']),$_SESSION['auth']['id_user']);

    $data = $connection->exec($sql,$params);
    if($data===PDOERROR)
    {
        $resultado = array(
            'errores' => 1,
            'msg' => 'No se ha podido actualizar el Password'
            );
    }
    else
    {
        $resultado = array(
            'errores' => 0,
            'msg' => 'El Password se ha actualizado de manera correcta',
            );
    }
    echo json_encode($resultado);
}
?>