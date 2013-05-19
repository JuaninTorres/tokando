<?php
session_start();
if(isset($_SESSION['auth']))
{
    $principal = print_r($_SESSION['auth'],true);
}
else
{
    // No estoy logueado
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <title>Seleccionando Dj</title>
    <link rel='stylesheet' href='css/ui-lightness/jquery-ui-1.10.1.css' />
    <!-- <link rel='stylesheet' href='css/ui-overcast/jquery-ui-1.10.1.css' /> -->
    <!-- <link rel='stylesheet' href='css/ui-dot-luv/jquery-ui-1.10.2.css' /> -->

    <link rel='stylesheet' href='css/jquery-ui-timepicker-addon.css' />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='css/estilos.css' />

    <script src='js/jquery-1.9.1.min.js'></script>
    <script src='js/jquery-ui-1.10.2.min.js'></script>
    <script src='js/jquery-ui-timepicker-addon.js'></script>
    <!-- <script src="_upload/js/vendor/jquery.ui.widget.js"></script> -->
    <script src="_upload/js/jquery.iframe-transport.js"></script>
    <script src="_upload/js/jquery.fileupload.js"></script>

    <script src='js/scripts.js'></script>
</head>
<body>
    <div id='divlogin' title='Ingrese sus datos para autenticarse'>
        <form action='login.php' id='formlogin' name='formlogin'>
            <fieldset>
                <label for='login_user'>Usuario</label>
                <input type='text' name='login_user' id='login_user' class='text ui-widget-content ui-corner-all' />
                <label for='login_pass'>Password</label>
                <input type='password' name='login_pass' id='login_pass' value='' class='text ui-widget-content ui-corner-all' />
            </fieldset>
        </form>
    </div>
    <div id='div_btn_ingresar'>
        <button id='btn_ingresar'>Ingresar</button>
    </div>
    <div id='div_user_logueado'>
        <span id='saludo_user_logueado'></span><span id='user_logueado'></span><span id='hora_server'></span><button id='btn_logout'>Salir</button>
    </div>
    <div id='principal'></div>
<?php
?>
</body>
</html>