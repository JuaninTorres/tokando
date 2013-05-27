<?php
session_start();
if($_SESSION['auth']['user_admin']==='1')
{
    require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
    $connection = new connectPDO;
    $sql = 'SELECT p.id_publicidad,p.codigo_publicidad,p.titulo,u.user_name FROM '.PREFIXTABLA.'_publicidad as p
    LEFT JOIN '.PREFIXTABLA.'_users as u ON p.codigo_publicidad = u.publicidad_asignada
    ORDER BY p.codigo_publicidad
    ';
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
        <table id='publicidades' class='ui-widget'>
            <thead class='ui-widget-header'>
            <tr>
                <th>Espacio publicidad</th>
                <th>Titulo</th>
                <th>Usuario a cargo</th>
                <th>-</th>
            </tr>
            </thead>
            <tbody class='ui-widget-content'>

        ";
        while($data = $dataEx->fetch())
        {
            $estadoUser = ($data['user_status']==='activo')?'Activo':'Inactivo';
            $html .= "
            <tr>
                <td>{$data['codigo_publicidad']}</td>
                <td>{$data['titulo']}</td>
                <td>{$data['user_name']}</td>
                <td><button rel='{$data['id_publicidad']}'>Editar</button></td>
            </tr>
            ";
        }
        $html .= "</tbody></table>
        <div id='div_modificar_publicidad'></div>";
        $jsCall[]="
            showListadoPublicidades();
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