<style>
</style>
<?php
require_once('../class/connectPDO.php');
$connection = new connectPDO;
$data = $connection->getrow('SELECT * FROM cpj_anuncios ORDER BY id_anuncio DESC LIMIT 1');
?>
    <div class="titulo" >
        <p><?php echo $data['titulo']; ?></p>
    </div>
    <div class="imagen">
        <a href="<?php echo $data['url_link']; ?>" target="_blank" ><img src="<?php echo $data['url_imagen']; ?>"></a>
    </div>