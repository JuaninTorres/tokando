<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/configuration.inc.php');
require_once(DOCUMENT_ROOT.'/class/connectPDO.php');
$connectPDO = new connectPDO;

$configuracion['prefijoURL'] = '';
$REQUEST_URI = str_replace($configuracion['prefijoURL'] , '', $_SERVER['REQUEST_URI']);
$parametrosURL = explode('/', trim($REQUEST_URI,'/'));

// El primer parametro lo tomo como la seccion solicitada
$seccionActual = addslashes(array_shift($parametrosURL));
$seccionActual = ($seccionActual=='')?'inicio':$seccionActual;
require_once('/_template/_funciones.php');
?><!DOCTYPE html>
<html lang="es">
<head>
    <?php echo getHead($seccionActual); ?>
</HEAD>
<!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->
<!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->
<!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->
<body>
    <section id="web">
        <header>
            <?php echo getHeader($seccionActual); ?>
        </header>
        <?php echo getBanner(); ?>
        <div id="fila1">
            <?php echo getRadio(); ?>
            <?php echo getTv(); ?>
            <?php echo getLocutorOnline(); ?>
        </div>
        <div id="fila2">
            <?php echo getPrincipal($seccionActual); ?>
        </div>
        <hr align="left" size="1" width="100%" noshade>
        <?php echo getFooter(); ?>
    </section>
<!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->
</body>
</html>