<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/class/connectPDO.php');
$connection = new connectPDO;

$data = $connection->getrow("SELECT
    o.id_online,
    NOW() BETWEEN tiempo_desde AND tiempo_hasta as vigente,u.*,
    CASE WHEN fecha_nacimiento IS NOT NULL THEN
        CONCAT(CAST((YEAR(CURDATE())-YEAR(fecha_nacimiento)) - (RIGHT(CURDATE(),5)<RIGHT(fecha_nacimiento,5)) AS CHAR), ' años')
    ELSE '-' END as edad
FROM ".PREFIXTABLA."_online as o, ".PREFIXTABLA."_users as u
WHERE u.id_user = o.id_user AND o.id_online = (SELECT MAX(id_online) FROM ".PREFIXTABLA."_online)");

$autoLocutor = ($data===PDOWARNING || $data['vigente']=='0')?true:false;
if($autoLocutor)
{

?>
<section id='locutor_online'>
    <article>
        <div class="foto_locutor">
            <img src="/imagenes/classic_mic.jpg">
        </div>
    </article>
</section>

<?php

}
else
{

$facebook = ($data['url_facebook']=='')?'':"<a href='{$data['url_facebook']}' target='_blank'><img src='/imagenes/facebook.png'></a>";
$twitter = ($data['url_twitter']=='')?'':"<a href='{$data['url_twitter']}' target='_blank'><img src='/imagenes/twitter.png'></a>";
$googleplus = ($data['url_googleplus']=='')?'':"<a href='{$data['url_googleplus']}' target='_blank'><img src='/imagenes/google.png'></a>";
$youtube = ($data['url_youtube']=='')?'':"<a href='{$data['url_youtube']}' target='_blank'><img src='/imagenes/youtube.png'></a>";
$soundcloud = ($data['url_soundcloud']=='')?'':"<a href='{$data['url_soundcloud']}' target='_blank'><img src='/imagenes/soundcloud.png'></a>";

$foto = (is_file($_SERVER['DOCUMENT_ROOT'].$data['fotografia']))?$data['fotografia']:'/imagenes/classic_mic.png';

?>
<div id="locutor" >
    <div id="datos">
        <div class="pregunta">NOMBRE :</div><!--.pregunta-->
        <div class="respuesta"><?php echo $data['first_name'].' '.$data['last_name']; ?></div><!--.respuesta--><br>
        <!---->
        <div class="pregunta">PAIS :</div><!--.pregunta-->
        <div class="respuesta"><?php echo $data['residencia']; ?></div><!--.respuesta--><br>
        <!---->
        <div class="pregunta">GENERO :</div><!--.pregunta-->
        <div class="respuesta"><?php echo $data['genero']; ?></div><!--.respuesta--><br>
        <!---->
        <div class="pregunta">HORARIO :</div><!--.pregunta-->
        <div class="respuesta"><?php echo $data['horarios']; ?></div><!--.respuesta-->
    </div><!-- #datos -->
    <div id="imagenL">
        <div id="logosL">
            <?php
                echo "{$facebook}\n";
                echo "{$twitter}\n";
                echo "{$googleplus}\n";
                echo "{$youtube}\n";
                echo "{$soundcloud}\n";
            ?>
        </div><!--#logosL-->
        <div id="locutorimagen">
            <img style="height: 225px;width: 150px;" src="<?php echo $foto; ?>">
        </div>
        <!--#imagenL-->
    </div>
<!-- #imagenL -->
</div>
<?php
}
?>