<?php
session_start();

// solo operará si es que
if(isset($_POST))
{
    require_once(DOCUMENT_ROOT.'/class/connectPDO.php');
    $connection = new connectPDO;

    $sql = 'INSERT INTO '.PREFIXTABLA.'_anuncios (id_user,titulo,url_imagen,url_link) VALUES (?,?,?,?)';
    $params = array($_SESSION['auth']['id_user'],$_POST['anuncio_titulo'],$_POST['anuncio_imagen'],$_POST['anuncio_url_link']);


    $data = $connection->exec($sql,$params);
    if($data===PDOERROR)
    {
        $resultado = array(
            'errores' => 1,
            'msg' => 'No se pudo ingresar el anuncio'
            );
    }
    else
    {
        $resultado = array(
            'errores' => 0,
            'msg' => 'Anuncio agregado de manera exitosa: '.$_POST['anuncio_titulo'],
            );
    }
    echo json_encode($resultado);
}
?>