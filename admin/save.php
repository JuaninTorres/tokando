<?php
session_start();

if(isset($_POST))
{
    require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
    $connection = new connectPDO;

    $caption = (trim($_POST['valor']) == '') ? NULL : $_POST['valor'];
    $id_e = $_POST['id_valor'];
    $tmp=explode('W',$id_e);

    $tableT = $tmp[0];
    $field  = $tmp[1];
    $id     = $tmp[2];
    $field_required = $tmp[3];
    $validame   = $tmp[4];

    $tableA = array(
        'o' => PREFIXTABLA.'_online',
        'u' => PREFIXTABLA.'_users',
        'up' => PREFIXTABLA.'_users'
        );

    $fieldsPK = array(
        'o' => 'id_online',
        'u' => 'id_user',
        'up' => 'id_user'
        );

    $table       = $tableA[$tableT];
    $fieldPK     = $fieldsPK[$tableT];
    $caption     = ($_POST['checked']!='') ? $_POST['checked'] : $caption;
    $valoractual = $connection->getone("SELECT {$field} FROM {$table} WHERE {$fieldPK}=?",array($id));

    $jsCall = array();

    try
    {
        //Si campo esta marcado como obligatorio debo verificar que no venga vacio
        if ( $field_required===1 && is_null($caption) ){
            $jsMessage = "alert('Campo obligatorio , no puede venir vacio ...');$('#{$id_e}').val('{$valoractual}')";
            throw new Exception($jsMessage, 1);
        }

        //Si viene marcado que hay que validarlo
        if(trim($validame)!=''){
            switch ($validame) {
                case 'user_name':
                    if(!is_null($caption)){
                        $n = $connection->getone('SELECT count(*) FROM '.PREFIXTABLA.'_users WHERE user_name=? and id_user <> ?',array($caption,$id));
                        if($n>0){
                            $jsMessage = "alert('Username ingresado se encuentra asociado a otro usuario ...');$('#{$id_e}').val('{$valoractual}');";
                            throw new Exception($jsMessage, 1);
                        }
                    }
                    break;
                default:
                     //nada
                    break;
            }
        }

        // Tratamiento especial
        switch ($field) {
            case 'user_pass':
                $caption = md5($caption);
                break;
        }

        $sql    = "UPDATE {$table} SET {$field}=? WHERE {$fieldPK}=? ";
        $param  = array($caption,$id);

        $response = $connection->exec($sql,$param);
        if ($response === PDOERROR){
            switch ($tableT)
            {
                case 'u':
                    switch($field)
                    {
                        case 'publicidad_asignada':
                            $jsMessage = "alert('Este espacio publicitario ya está asignado a otro usuario, intente con otra opción');$('#{$id_e}').val('{$valoractual}');";
                            break;
                        default:
                            $jsMessage = "alert('fallo actualizacion de información');$('#{$id_e}').val('{$valoractual}');";
                    }
                    break;
                default:
                    $jsMessage = "alert('fallo actualizacion de información');$('#{$id_e}').val('{$valoractual}');";
                    break;
            }
            throw new Exception($jsMessage, 1);
        }else{
            // Si se pudo hacer el UPDATE, entonces muestro una animacion
            $jsCall[] = "savehighlight('{$id_e}')";

            // callback especiales
            switch ($tableT)
            {
                case 'u':
                    switch($field)
                    {
                        case 'timezone':
                            // Si estoy cambiando MI zona horaria, entonces me piso la variable de sesion
                            if($id==$_SESSION['auth']['id_user'])
                            {
                                $_SESSION['auth']['timezone'] = $caption;
                            }
                        break;
                    }
                    break;
            }
        }
    }
    catch(Exception $e)
    {
        $jsCall[] = $e->getMessage();
    }

    echo json_encode(array('jscall'=>implode(';', $jsCall)));
}
?>