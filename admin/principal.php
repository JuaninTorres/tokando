<?php
session_start();
if(isset($_SESSION['auth']))
{
    require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
    require_once('funciones_comunes.php');
    $connection = new connectPDO;

    // Lo que primero haré es dejar la instruccion para armar los tabs
    $jsCall[] = "$('#tabs').tabs()";
    $jsCall[] = "$('#div_btn_logout').show()";

    if($_SESSION['auth']['tipo_usuario']=='locutor')
    {
        $titulos[] = 'Dj Locuteando';
        //si existe el id_online quiere decir que tiene viva la sesion para transmitir
        if(isset($_SESSION['auth']['id_online']))
        {
            $locutor = getLocutorOnline();
            $contenidos[]=$locutor['contenido'];
            $jsCall[]=$locutor['jscall'];
        }
        else
        {
            $idBtn = 'btn_comenzar_transmitir';
            $contenidos[] = "<fieldset class='ui-widget ui-widget-content'>
            <legend class='ui-widget-header ui-corner-all'>¿".ucfirst($_SESSION['auth']['first_name'])." ".ucfirst($_SESSION['auth']['last_name']).", Vas a transmitir en este momento?</legend>
            <button id='{$idBtn}'>Comenzar a transmitir</button>
            </fieldset>";
            $jsCall[]="$('#{$idBtn}').button({icons: { primary: \"ui-icon-check\"}}
                ).click(function(){
                    var comenzarLocutor = \$.get('locutor_online.php',function(data){
                        \$('#tabs-1').html(data.contenido);
                        eval(data.jscall);
                    },'json');
            })";
        }
    }

    if($_SESSION['auth']['tipo_usuario']=='publicidad')
    {
        // Dejo disponible para todos la opcion de hacer anuncios
        $titulos[] = 'Publicidad';
        $dataP = $connection->getrow('SELECT p.* FROM '.PREFIXTABLA.'_publicidad as p,'.PREFIXTABLA.'_users as u
            WHERE p.codigo_publicidad = u.publicidad_asignada
            AND p.activa AND u.id_user = ?',array($_SESSION['auth']['id_user']));
        if($dataP===PDOWARNING)
        {
            $modificacionPublicidad = "No tiene asignado ningun espacio publicitario";
        }
        else
        {
            $imagenpublicidad = ($dataP['url_imagen']=='')?"<img src='/imagenes/publica_aqui.jpg' />":"<img src='{$dataP['url_imagen']}' />";
            $modificacionPublicidad = "
                <table id='modificion_publicidad' class='ui-widget'>
                <tbody class='ui-widget-content'>
                    <tr>
                        <th class='ui-widget-header'>Titulo</th>
                        <td><input type='text' id='pWtituloW{$dataP['id_publicidad']}' value='{$dataP['titulo']}' placeholder='Cual es el titulo de la publicidad?' /></td>
                    </tr>
                    <tr>
                        <th class='ui-widget-header'>URL link</th>
                        <td><input type='text' id='pWlinkW{$dataP['id_publicidad']}' value='{$dataP['link']}' placeholder='Hacia donde hay que dejar linkeada la imagen?'/></td>
                    </tr>
                    <tr>
                        <th class='ui-widget-header'>Imagen</th>
                        <td><input id='pWurl_imagenW{$dataP['id_publicidad']}' type='file' name='files[]' data-url='/_upload/server/php/' multiple>
                        <div id='publicidad_preview_imagen'>{$imagenpublicidad}</div>
                        </td>
                    </tr>
                </tbody>
                </table>
            ";
        }

        $contenidos[] = "<fieldset class='ui-widget ui-widget-content'>
            <legend class='ui-widget-header ui-corner-all'>Subir un nuevo anuncio</legend>
            <div id='nuevo_anuncio'>{$modificacionPublicidad}</div>
            </fieldset>";
        $jsCall[]="$(function () {
                        $('#pWurl_imagenW{$dataP['id_publicidad']}').fileupload({
                            dataType: 'json',
                            done: function (e, data) {
                                $.each(data.result.files, function (index, file) {
                                    $('#publicidad_preview_imagen').html($('<img />').attr('src',file.url));
                                    guardando(file.url,'pWurl_imagenW{$dataP['id_publicidad']}');
                                    console.log(file);
                                });
                            }
                        });
                    })";
    }

    $titulos[] = 'Mis Datos';
    $contenidos[] = "<fieldset class='ui-widget ui-widget-content'>
        <legend class='ui-widget-header ui-corner-all'>Esta es mi información</legend>
        <div id='modificacion_personal'></div>
        </fieldset>";
    $jsCall[]="\$.get('modificacion_personal.php',function(data){
                \$('#modificacion_personal').html(data.contenido);
                eval(data.jscall);
            },'json');
    ";

    // Ahora vemos si es administrador, si es asi... le damos las opciones
    if($_SESSION['auth']['user_admin']=='1')
    {
        $titulos[] = 'Administracion de usuarios';
        $contenidos[] = "<fieldset class='ui-widget ui-widget-content'>
            <legend class='ui-widget-header ui-corner-all'>Usuarios del sistema</legend>
            <div id='listado_usuarios'></div>
            </fieldset>";
        $jsCall[]="getListadoUsuarios()";
    }

    // Procesamos los contenidos
    // titulos
    $html = "<div id='tabs'><ul>";
    foreach ($titulos as $index => $titulo)
    {
        $html .= "<li><a href='#tabs-".($index+1)."'>".$titulo."</a></li>";
    }
    $html .= "</ul>";

    //Contenidos de los tabs
    foreach ($contenidos as $index => $contenido)
    {
        $html .= "<div id='tabs-".($index+1)."'>".$contenido."</div>";
    }
    $html .= "</div>";
    echo json_encode(array(
        'contenido'=>$html,
        'jscall'=>implode(';', $jsCall),
        'user'=>$_SESSION['auth'],
        'session'=>array('session_id' => session_id())
        ));

}
?>