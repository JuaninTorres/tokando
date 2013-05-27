<?php
session_start();
if(isset($_SESSION['auth']['id_user']))
{
    $codeInput      = 'onclick="editInputCRC(this)" onblur="editInputCRCOff(this)"';
    $codeSelect     = 'onclick="editInputCRC(this)" onchange="editInputCRCOff(this);editInputCRC(this);"';
    $codeInputCheck = 'class="radio" onclick="guardando(this.value,this.id,this.checked)"';
    $codeInputRadio = 'onclick="guardando(this.value,this.name)" ';

    $id = $_POST['id_publicidad'];

    require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
    $connection = new connectPDO;

    $sql = 'SELECT p.*,u.id_user FROM '.PREFIXTABLA.'_publicidad as p
    LEFT JOIN '.PREFIXTABLA.'_users as u ON p.codigo_publicidad = u.publicidad_asignada
    WHERE id_publicidad = ?';
    $params = array($id);
    $jsCall = array();

    try
    {
        $dataP = $connection->getrow($sql,$params);
        if($dataP===PDOERROR)
        {
            throw new Exception('Error ejecutando la consulta', 1);
        }
        if($dataP===PDOWARNING)
        {
            throw new Exception('No existe la publicidad solicitada', 1);
        }

        $dataUEx = $connection->Execute('SELECT id_user, user_name, publicidad_asignada FROM '.PREFIXTABLA.'_users as u
            WHERE (tipo_usuario = ? AND publicidad_asignada IS NULL) OR id_user = ?', array('publicidad',$dataP['id_user']));
        if($dataUEx->rowCount()>0)
        {
            $options = '';
            while($dataU = $dataUEx->fetch())
            {
                $selected = ($dataU['publicidad_asignada']==$dataP['codigo_publicidad'])?'selected':'';
                $options .= "<opcion value='{$dataU['id_user']}' {$selected}>{$dataU['user_name']}</option>\n";
            }
        }

        // Comenzamos a dibujar
        $imagenpublicidad = ($dataP['url_imagen']=='')?"<img src='/imagenes/publica_aqui.jpg' />":"<img src='{$dataP['url_imagen']}' />";
        $html = "
            <table id='modificion_publicidad' class='ui-widget'>
            <tbody class='ui-widget-content'>
                <tr>
                    <th class='ui-widget-header'>Titulo</th>
                    <td><input {$codeInput} type='text' id='pWtituloW{$dataP['id_publicidad']}' value='{$dataP['titulo']}' placeholder='Cual es el titulo de la publicidad?' /></td>
                </tr>
                <tr>
                    <th class='ui-widget-header'>Usuario a cargo</th>
                    <td><select {$codeSelect} id='pWusuarioW{$dataP['codigo_publicidad']}'>
                        <option value=''>Selecciones un usuario</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class='ui-widget-header'>URL link</th>
                    <td><input {$codeInput} type='text' id='pWlinkW{$dataP['id_publicidad']}' value='{$dataP['link']}' placeholder='Hacia donde hay que dejar linkeada la imagen?'/></td>
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