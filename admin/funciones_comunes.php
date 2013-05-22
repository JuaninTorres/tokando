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

function getArrayTimeZones()
{
    return array(
      "-12:00" => "(GMT -12:00) Eniwetok, Kwajalein",
      "-11:00" => "(GMT -11:00) Midway Island, Samoa",
      "-10:00" => "(GMT -10:00) Hawaii",
      "-9:00" => "(GMT -9:00) Alaska",
      "-8:00" => "(GMT -8:00) Pacific Time (US &amp; Canada",
      "-7:00" => "(GMT -7:00) Mountain Time (US &amp; Canada",
      "-6:00" => "(GMT -6:00) Central Time (US &amp; Canada), Mexico City",
      "-5:00" => "(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima",
      "-4:00" => "(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz",
      "-3:30" => "(GMT -3:30) Newfoundland",
      "-3:00" => "(GMT -3:00) Brazil, Buenos Aires, Georgetown",
      "-2:00" => "(GMT -2:00) Mid-Atlantic",
      "-1:00" => "(GMT -1:00 hour) Azores, Cape Verde Islands",
      "+0:00" => "(GMT) Western Europe Time, London, Lisbon, Casablanca",
      "+1:00" => "(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris",
      "+2:00" => "(GMT +2:00) Kaliningrad, South Africa",
      "+3:00" => "(GMT +3:00) Baghdad, Riyadh, Moscow, St.0 Petersburg",
      "+3:05" => "(GMT +3:30) Tehran",
      "+4:00" => "(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi",
      "+4:05" => "(GMT +4:30) Kabul",
      "+5:00" => "(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent",
      "+5:30" => "(GMT +5:30) Bombay, Calcutta, Madras, New Delhi",
      "+5:45" => "(GMT +5:45) Kathmandu",
      "+6:00" => "(GMT +6:00) Almaty, Dhaka, Colombo",
      "+7:00" => "(GMT +7:00) Bangkok, Hanoi, Jakarta",
      "+8:00" => "(GMT +8:00) Beijing, Perth, Singapore, Hong Kong",
      "+9:00" => "(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk",
      "+9:30" => "(GMT +9:30) Adelaide, Darwin",
      "+10:00" => "(GMT +10:00) Eastern Australia, Guam, Vladivostok",
      "+11:00" => "(GMT +11:00) Magadan, Solomon Islands, New Caledonia",
      "+12:00" => "(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka",
    );
}
?>