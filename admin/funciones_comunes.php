<?php

function getLocutorOnline()
{
    $codeInput      = 'onclick="editInputCRC(this)" onblur="editInputCRCOff(this)"';
    $codeSelect     = 'onclick="editInputCRC(this)" onchange="editInputCRCOff(this);editInputCRC(this);"';
    $codeInputCheck = 'class="radio" onclick="guardando(this.value,this.id,this.checked)"';
    $codeInputRadio = 'onclick="guardando(this.value,this.name)" ';

    require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
    $connection = new connectPDO;

    //$jsCall[] = "alert('".$_SESSION['auth']['id_online']."')";
    // Lo que primero hago es insertar un nuevo registro si es que aun no lo he creado
    if(!isset($_SESSION['auth']['id_online']))
    {
        $sqlInsert = 'INSERT INTO '.PREFIXTABLA.'_online (id_user) VALUES (?)';
        $paramsInsert = array($_SESSION['auth']['id_user']);
        $connection->exec($sqlInsert,$paramsInsert);
        $_SESSION['auth']['id_online'] = $connection->getone('SELECT LAST_INSERT_ID()');
    }

    $fecha_hasta = $connection->getone("SELECT DATE_FORMAT(tiempo_hasta, '%Y-%m-%d %H:%i') FROM ".PREFIXTABLA."_online WHERE id_online = ?",array($_SESSION['auth']['id_online']));
    $fecha_desde = $connection->getrow("SELECT YEAR(tiempo_desde) as year,(MONTH(tiempo_desde)-1)  as month,DAY(tiempo_desde) as day,HOUR(tiempo_desde) hour,MINUTE(tiempo_desde) as minute from ".PREFIXTABLA."_online WHERE id_online = ?",array($_SESSION['auth']['id_online']));


    $divInput = "oWtiempo_hastaW{$_SESSION['auth']['id_online']}";
    $contenido = "<fieldset class='ui-widget ui-widget-content'>
    <legend class='ui-widget-header ui-corner-all'>¿".ucfirst($_SESSION['auth']['first_name'])." ".ucfirst($_SESSION['auth']['last_name']).", Hasta que hora vas a estar?</legend>
    <input onclick='editInputCRC(this)' type='text' id='{$divInput}' value='{$fecha_hasta}' />
    </fieldset>";
    $jsCall[]="$('#{$divInput}').datetimepicker({
        timeFormat: \"HH:mm\",
        dateFormat: \"yy-mm-dd\",
        minDate: new Date({$fecha_desde['year']}, {$fecha_desde['month']}, {$fecha_desde['day']}, {$fecha_desde['hour']}, {$fecha_desde['minute']}),
        onClose: function(calDate){
            console.log('al cerrar: ' +calDate);
            editInputCRCOff(this);
        },
        onSelected: function(calDate){
            console.log('seleccione '+calDate);
            editInputCRC(this);
        }
    })";

    return array(
        'contenido'=>$contenido,
        'jscall'=>implode(';', $jsCall));
}
?>