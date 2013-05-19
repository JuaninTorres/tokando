<?php
session_start();
if($_SESSION['auth']['user_admin']==='1')
{
    require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
    $connection = new connectPDO;
    $sql = 'SELECT * FROM '.PREFIXTABLA.'_users';
    try
    {
        $dataEx = $connection->Execute($sql);
        if ($dataEx===PDOERROR)
        {
            throw new Exception('Error buscando a los usuarios', 1);
        }

        $html = "
        <div class='ui-widget' id='divMensajeError'>
            <div class='ui-state-error ui-corner-all' style='padding: 0 .7em;'>
                <p><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'></span>
                <span class='contenido'></span></p>
            </div>
        </div>
        <table id='usuarios' class='ui-widget'>
            <thead class='ui-widget-header'>
            <tr>
                <th>Usuario</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Estado</th>
                <th>Editar</th>
            </tr>
            </thead>
            <tbody class='ui-widget-content'>

        ";
        while($data = $dataEx->fetch())
        {
            $estadoUser = ($data['user_status']==='activo')?'Activo':'Inactivo';
            $html .= "
            <tr>
                <td>{$data['user_name']}</td>
                <td>{$data['first_name']}</td>
                <td>{$data['last_name']}</td>
                <td>{$estadoUser}</td>
                <td><button rel='{$data['id_user']}'>Editar</button></td>
            </tr>
            ";
        }
        $html .= "</tbody></table>
        <button id='btn_adduser'>Crear un nuevo usuario</button>
        <div id='div_modificar'></div>";
        $jsCall[]="
            showListadoUsuarios();
        ";
    }
    catch(Exception $e)
    {
        $html = $e->getMessage();
    }

    echo json_encode(array(
        'contenido'=>$html,
        'jscall'=>implode(';', $jsCall),
        ));
}
?>