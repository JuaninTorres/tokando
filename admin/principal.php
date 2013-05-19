<?php
session_start();
if(isset($_SESSION['auth']))
{
    require_once('funciones_comunes.php');

    // Lo que primero haré es dejar la instruccion para armar los tabs
    $jsCall[] = "$('#tabs').tabs()";
    $jsCall[] = "$('#div_btn_logout').show()";


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

    // Dejo disponible para todos la opcion de hacer anuncios
    $titulos[] = 'Anuncios';
    $anuncios = "
        <form id='form_anuncio' name='form_anuncio' onsubmit='return false' action='publicar_anuncio.php'>
        <table id='modificion_usuario' class='ui-widget'>
        <tbody class='ui-widget-content'>
            <tr>
                <th class='ui-widget-header'>Titulo</th>
                <td><input type='text' id='anuncio_titulo' name='anuncio_titulo' placeholder='Cual es el titulo del anuncio?' /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>URL link</th>
                <td><input type='text' id='anuncio_url_link' name='anuncio_url_link' placeholder='Hacia donde hay que dejar linkeada la imagen?'/></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Imagen</th>
                <td><input id='anuncio_imagen_file' type='file' name='files[]' data-url='_upload/server/php/' multiple>
                <input id='anuncio_imagen' type='hidden' name='anuncio_imagen' id='anuncio_imagen' >
                <div id='anuncio_preview_imagen'></div>
                </td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Publicar</th>
                <td><button id='btn_publicar_anuncio'>Publicar Ahora</button></td>
            </tr>
        </tbody>
        </table>
        </form>
    ";
    // <td><input id='anuncio_imagen' type='file' name='anuncio_imagen[]' data-url='_upload/server/php/' multiple>

    $contenidos[] = "<fieldset class='ui-widget ui-widget-content'>
        <legend class='ui-widget-header ui-corner-all'>Subir un nuevo anuncio</legend>
        <div id='nuevo_anuncio'>{$anuncios}</div>
        </fieldset>";
    $jsCall[]="$(function () {
                        $('#anuncio_imagen_file').fileupload({
                            dataType: 'json',
                            done: function (e, data) {
                                $.each(data.result.files, function (index, file) {
                                    $('#anuncio_preview_imagen').html($('<img />').attr('src',file.url));
                                    $('#anuncio_imagen').val(file.url);
                                    console.log(file);
                                });
                            }
                        });
                    })";
    $jsCall[]="$('#btn_publicar_anuncio').button({icons: { primary: 'ui-icon-pin-s'}}).click(function(){
                    var formValues = $('#form_anuncio').serialize(),
                    url = $('#form_anuncio').attr('action');
                    var publicarAnuncio = $.post( url, formValues, function(data){
                        $('#form_anuncio')[0].reset();
                        $('#anuncio_preview_imagen').empty();
                        alert(data.msg);
                        console.log(data);
                    },'json' );
    })";

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