<?php
session_start();
if(isset($_POST['id_user']) && $_SESSION['auth']['user_admin']==='1')
{
    $codeInput      = 'onclick="editInputCRC(this)" onblur="editInputCRCOff(this)"';
    $codeSelect     = 'onclick="editInputCRC(this)" onchange="editInputCRCOff(this);editInputCRC(this);"';
    $codeInputCheck = 'class="radio" onclick="guardando(this.value,this.id,this.checked)"';
    $codeInputRadio = 'onclick="guardando(this.value,this.name)" ';

    require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
    $connection = new connectPDO;

    $sql = 'SELECT * FROM '.PREFIXTABLA.'_users WHERE id_user = ?';
    $params = array($_POST['id_user']);

    try
    {
        $data = $connection->getrow($sql,$params);
        if($data===PDOERROR)
        {
            throw new Exception('Error ejecutando la consulta', 1);
        }
        if($data===PDOWARNING)
        {
            throw new Exception('No existe el usuario', 1);
        }

        $id = $data['id_user'];

        // veamos el estado del usuario
        $estadosPosibles = array('activo','inactivo');
        $estadoUsuario = "<div id='estado_usuario'>";
        foreach ($estadosPosibles as $estado) {
            $checked=($estado==$data['user_status'])?"checked='checked'":'';
            $estadoUsuario .= "<input type='radio' id='estado_{$estado}' name=uWuser_statusW{$data['id_user']} value='{$estado}' {$codeInputRadio} {$checked}/><label for='estado_{$estado}'>".ucfirst($estado)."</label>";
        }
        $estadoUsuario .= "</div>";
        $estadoUsuario .= "<button id='btn_eliminar_user_{$id}' rel='{$data['user_name']}'>Eliminar</button>";
        $jsCall[] = "\$('#estado_usuario').buttonset()";
        $jsCall[] = "\$('#btn_eliminar_user_{$id}').button({icons: { primary: 'ui-icon-trash'}}).click(function(){
            confirmEliminaUsuario(\$(this).attr('rel'));
        })";

        // Foto
        if($data['fotografia']=='' || !is_file(urldecode(DOCUMMENT_ROOT.$data['fotografia'])))
        {
            // no existe la foto del locutor
            $srcFoto = "/imagenes/classic_mic.png";
        }
        else
        {
            $srcFoto = $data['fotografia'];
        }
        $foto = "<img src='{$srcFoto}' border='0' id='fotoW{$data['id_user']}' />";

        // veamos tipo de usuario
        $tiposPosibles = array('Normal','Administrador');
        $tipoUsuario = "<div id='tipo_usuario'>";
        foreach ($tiposPosibles as $index => $tipo) {
            $checked=($index==$data['user_admin'])?"checked='checked'":'';
            $tipoUsuario .= "<input type='radio' id='tipo_{$index}' name=uWuser_adminW{$data['id_user']} value='{$index}' {$codeInputRadio} {$checked}/><label for='tipo_{$index}'>".ucfirst($tipo)."</label>";
        }
        $tipoUsuario .= "</div>";
        $jsCall[] = "\$('#tipo_usuario').buttonset()";

        // Comenzamos a dibujar
        $html = "<div id='modalModificarUsuario'></div>
        <table id='modificion_usuario' class='ui-widget'>
        <tbody class='ui-widget-content'>
            <tr>
                <th class='ui-widget-header'>Usuario</th>
                <td><input type='text' value='{$data['user_name']}' readonly='readonly' disabled='disabled' /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Password</th>
                <td><input type='password' id='uWuser_passW{$data['id_user']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Nickname</th>
                <td><input type='text' id='uWnick_nameW{$data['id_user']}' value='{$data['nick_name']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Nombres</th>
                <td><input type='text' id='uWfirst_nameW{$data['id_user']}' value='{$data['first_name']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Apellidos</th>
                <td><input type='text' id='uWlast_nameW{$data['id_user']}' value='{$data['last_name']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Fecha de Nacimiento</th>
                <td><input type='text' id='uWfecha_nacimientoW{$data['id_user']}' value='{$data['fecha_nacimiento']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>email</th>
                <td><input type='text' id='uWemailW{$data['id_user']}' value='{$data['email']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Estado</th>
                <td>{$estadoUsuario}</td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Tipo de usuario</th>
                <td>{$tipoUsuario}</td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Genero Musical</th>
                <td><input type='text' id='upWgeneroW{$id}' value='{$data['genero']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Programación</th>
                <td><input type='text' id='uWprogramasW{$data['id_user']}' value='{$data['programas']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Horarios</th>
                <td><input type='text' id='uWhorariosW{$data['id_user']}' value='{$data['horarios']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Residencia</th>
                <td><input type='text' id='uWresidenciaW{$data['id_user']}' value='{$data['residencia']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Facebook</th>
                <td><input type='text' id='uWurl_facebookW{$data['id_user']}' value='{$data['url_facebook']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Twitter</th>
                <td><input type='text' id='uWurl_twitterW{$data['id_user']}' value='{$data['url_twitter']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Google+</th>
                <td><input type='text' id='uWurl_googleplusW{$data['id_user']}' value='{$data['url_googleplus']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>YouTube</th>
                <td><input type='text' id='uWurl_youtubeW{$data['id_user']}' value='{$data['url_youtube']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>SoundCloud</th>
                <td><input type='text' id='upWurl_soundcloudW{$id}' value='{$data['url_soundcloud']}' {$codeInput} /></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Hobbies</th>
                <td><textarea id='uWhobbiesW{$data['id_user']}' {$codeInput} >{$data['hobbies']}</textarea></td>
            </tr>
            <tr>
                <th class='ui-widget-header'>Fotografía</th>
                <td><input id='uWfotografiaW{$data['id_user']}' type='file' name='files[]' data-url='/_upload/server/php/' multiple>
                {$foto}
                </td>
            </tr>
        </tbody>
        </table>
        ";
        $jsCall[]="\$('#uWfotografiaW{$data['id_user']}').fileupload({
                        dataType: 'json',
                        done: function (e, data) {
                            \$.each(data.result.files, function (index, file) {
                                \$('#fotoW{$data['id_user']}').attr('src',file.url);
                                guardando(file.url,'uWfotografiaW{$data['id_user']}');
                                console.log(file);
                            });
                        }
                    });";
    }
    catch(Exception $e)
    {
        $html = $e->getMessage();
    }
    echo json_encode(
        array(
            'contenido'=>$html,
            'jscall'=>implode(';', $jsCall),
        )
    );
}
?>