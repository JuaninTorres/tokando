<?php

function getPrincipal($whereami='inicio')
{
    $whereami = addslashes($whereami);
    switch($whereami)
    {
        case 'inicio':
            $contenido = '<div id="fila1">'.
                getRadio().
                getTv().
                getLocutorOnline().'
            </div>
            <div id="fila2">
                '.
                getChat().
                getRedes().
                getSiguenos().
                '
            </div>';
            break;
        case 'mp3':
            $contenido = getMp3();
            break;
        case 'contacto':
            $contenido = getContacto();
            //$contenido .= "<script>getContacto();</script>";
            break;
        default:
            //Error 404
            $contenido = '<h2>Error 404</h2><p>Lo sentimos, pero no se encuentra disponible lo que nos está solicitando</p>';
    }
    return $contenido;
}

// Funciones de secciones
function getHead($whereami='inicio')
{
    switch($whereami)
    {
        case 'inicio':
            $dondeEstoy = '';
            break;
        case 'contacto':
            $dondeEstoy = ' - '.ucfirst($whereami);
            $cargaInicialJS = 'getContacto()';
        default:
            $dondeEstoy = ' - '.ucfirst($whereami);
            break;
    }
    $titulo = 'TOKANDO.COM'.$dondeEstoy;

    $contenido = '
    <meta charset="UTF-8" />
    <meta property=og:image content="/imagenes/logo.png"/>
    <title>'.$titulo.'</title>
    <link rel="Shortcut Icon" href="/imagenes/logo.ico" />
    <link rel="image_src" href="/imagenes/logo.png" />
    <meta name="description" content="Radio Online donde encontraras lo mejor de la musica, pop, electro, metal, cortavenas, y los mas divertidos locutores con sus ocurrencias y disparates pero siempre con el cariño y respeto que nuestro publico merece por que aquí en esta radio somos una familia, aunque nada de esto seria posible sin el esfuerzo de nuestros fundadores  a los que se le debe un agradecimiento danos tu apoyo  se parte de esta familia que dia a dia crese y llega a ustedes con todo el cariño que se merecen xD!">
    <link href="http://fonts.googleapis.com/css?family=Fascinate+Inline" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Loved+by+the+King" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Dosis:500" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Average+Sans" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/_css/normalize.css" />
    <link rel="stylesheet" href="/_css/estilo.css" />
    <link rel="stylesheet" href="/_css/ui-dot-luv/jquery-ui-1.10.1.css" />
    <link rel="stylesheet" href="/_css/jquery-ui-timepicker-addon.css" />
    <link rel="stylesheet" href="/_css/contacto.css" />
    <script src="/_js/prefixfree.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="/_js/jquery-ui-1.10.2.min.js"></script>
    <script src="/_js/jquery-ui-timepicker-addon.js"></script>
    <script src="/_js/validation/jquery.validate.min.js"></script>
    <script src="/_js/validation/additional-methods.min.js"></script>
    <script src="/_js/validation/localization/messages_es.js"></script>
    <script src="/_js/scripts.js"></script>
    <script>
        $(document).on("ready",function(){
            contDinamico();
            '.$cargaInicialJS.'
        });
    </script>
    ';
    return $contenido;
}

function getHeader($whereami='inicio')
{
    $contenido = getNav($whereami);
    $contenido .= '<div id="arriba" style="">
                <img src="/imagenes/logoarriba.png">
            </div>';
    return $contenido;
}

function getFooter()
{
    $contenido = '<footer>
        <p>Diseñada por : <a href="http://www.phillipmg.com" target="_blank">Phillip Mendoza </a>|| &copy; tokando.com ~ 2013</p>
    </footer>';
    return $contenido;
}

// Funciones generales
function getNav($actualSeccion='inicio')
{
    $current['inicio']   =($actualSeccion=='inicio')?'actual':'';
    $current['mp3']      =($actualSeccion=='mp3')?'actual':'';
    $current['contacto'] =($actualSeccion=='contacto')?'actual':'';
    $variablesCurrent = array(
        'inicio'   => '_[CURRENT_INICIO]_',
        'mp3'      => '_[CURRENT_MUSICAMP3]_',
        'contacto' => '_[CURRENT_CONTACTO]_',
    );

    $nav = '
    <nav id="nav">
        <ul>
            <li class="_[CURRENT_INICIO]_"><a href="/">PROGRAMACION</a></li>
            <li class="_[CURRENT_MUSICAMP3]_"><a href="/mp3">MUSICA MP3</a></li>
            <li class="_[CURRENT_CONTACTO]_"><a href="/contacto">CONTACTANOS</a></li>
        </ul>
    </nav>
    ';
    return str_replace($variablesCurrent, $current, $nav);
}

function getBanner()
{
    return '<div id="slide" style="border:none;max-width:100%; overflow:hidden; margin:0 ;  ">
            <iframe src="slider.html" scrolling="no" style="border:none; max-width:100%; overflow:hidden; width:1000px;margin:0 ; height:350px;"></iframe>
        </div>';
}

function getRadio()
{
    $contenido = '<div id="radio">
        <!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->
        <div class="titulo1">
                <a href="http://phillipmg.com/radio/tokando/" target="repro" class="link" >TOKANDO EN VIVO</a>
        </div>
        <div id="repro">
                    <iframe name="repro"allowtransparency="true" border="0px" style="border:none; max-width:100%;max-height:100%;height:270px;" scrolling="no" src="http://phillipmg.com/radio/tokandoradio/"></iframe>
        </div><!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->
        </div>
        <div id="escuchanos">
                <div id="escuchanosen">
                <p>&nbsp;Escuchanos en :</p>
                </div><!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->
                <div id="logosescuchanos">
                <a href="http://www.facebook.com/pages/TOKANDO-TV/288908571149565?id=288908571149565&sk=app_208195102528120" alt="escuchanos en fb" target="_blank"><img src="imagenes/facebook.png"></a>
                <a href="rtsp://aac.01wzserver.com:1935/aacplus/8004" alt="escuchanos en android" target="_blank"><img src="imagenes/android.png"></a>
                <a href="http://aac.01wzserver.com:1935/aacplus/8004/playlist.m3u" alt="escuchanos en apple" target="_blank"><img src="imagenes/apple.png"></a>
                <a href="rtsp://aac.01wzserver.com:1935/aacplus/8004" alt="escuchanos en bb" target="_blank"><img src="imagenes/bb.png"></a>
                <a href="" alt="escuchanos en tunein" target="_blank"><img src="imagenes/tunein.png"></a>
                </div>
        </div>

        ';
    return $contenido;
}

function getTv()
{
    $contenido = '<div id="tokandotv">
            <div class="titulo1" style="margin:0.7em 0em 0.4em 0em">
                    <a href="" target="reprolista" class="link" >TOKANDO TV</a>
            </div><!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->
            <div id="reprolista" name="reprolista">
                <div id="caja3">
                    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" name="utv55137"  id="utv55137">
                    <param name="flashvars" value="autoplay=false&amp;brand=embed&amp;cid=8661709&amp;v3=1"/><param name="allowfullscreen" value="true"/><param name="allowscriptaccess" value="always"/><param name="movie" value="http://www.ustream.tv/flash/viewer.swf"/><embed flashvars="autoplay=false&amp;brand=embed&amp;cid=8661709&amp;v3=1" width="440" height="310" allowfullscreen="true" allowscriptaccess="always" id="utv55137" name="utv55137" src="http://www.ustream.tv/flash/viewer.swf" type="application/x-shockwave-flash" /></object>
                </div>
            </div>
        </div>';
    return $contenido;
}

function getLocutorOnline()
{
    $contenido ='
            <div class="titulo1" style="margin:0.7em 0em 0.4em 0em">
                <a href="" class="link" >EN VIVO</a>
            </div>
            <!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->

            <div id="espacio_locutor">

            </div>
            <!--#imagenL-->';

    return $contenido;
}

function getRedes()
{
    $contenido = '<div class="titulo1" >
            <a href="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FTOKANDO-TV%2F288908571149565%3Ffref%3Dts&amp;width=500&amp;height=258&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=false&amp;appId=334775653285723" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:460px; height:258px;" target="like" class="link" >ME GUSTA EN FACEBOOK</a>
        </div>
        <div id="espaciolike" name="like" style="margin:0.2em 0em 0.4em 0em">
            <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FTOKANDO-TV%2F288908571149565%3Ffref%3Dts&amp;width=500&amp;height=258&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=false&amp;appId=334775653285723" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:258px;" allowTransparency="true"></iframe>
        </div>';
    return $contenido;
}

function getSiguenos()
{
    $contenido = '<div class="titulo1" >
                    <a href="javascript:void(0)" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:460px; height:258px;" target="like" class="link" >SIGUENOS</a>
                </div>
                <div id="siguenos">
                    <div class="siguenos"><a href="https://play.google.com/store/apps/details?id=s42.tokando.radio&amp;feature=search_result#?t=W251bGwsMSwyLDEsInM0Mi50b2thbmRvLnJhZGlvIl0." target="_blank"><img src="/imagenes/gplay.png"></a></div><!--siguenos-->
                    <div class="siguenos"><a href="https://www.facebook.com/groups/216191211835073/?fref=ts" target="_blank"><img src="/imagenes/grupo.png"></a></div><!--siguenos-->
                    <div class="siguenos"><a href="http://www.youtube.com/feed/UC8cuGz98vuQD9vITDqVMv6Q/u" target="_blank"><img src="/imagenes/canal.png"></a></div><!--siguenos-->
                </div>';
    return $contenido;
}

function getChat()
{
    $contenido = '<div class="titulo1" style="margin:0.7em 0em 0.4em 0em">
            <a href="2chat.html" target="chat" class="link" >CHAT TOKANDO</a>
        </div>
        <div id="espaciochat" name="chat">
            <iframe name="chat" width="500" height="650" allowtransparency="true" border="0px" style="border:none; max-width:100%;" scrolling="no" src="2chat.html"></iframe>
        </div>
        <!-- PAGINA DESARROLLADA POR PHILLIP MENDOZA GONZALES WWW.PHILLIPMG.COM-->';

    return $contenido;
}

function getMp3()
{
    $contenido = '
    <div id="musica1" style="margin-left:12px">
        <div class="titulo1">
            <a href="#" target="repro" class="link" >Bachat mp3</a>
        </div>
        <div id="repro1">
            <iframe src="/_swf/repro3.swf" frameborder="0" ></iframe>
        </div>
    </div><!-- musica1  -->
    <div id="musica2">
        <div class="titulo1">
            <a href="#" target="repro" class="link" >salsa mp3</a>
        </div>
        <div id="repro2">
            <iframe src="/_swf/repro2.swf" frameborder="0" ></iframe>
        </div>
    </div><!-- musica2  -->
    <div id="musica3">
        <div class="titulo1">
            <a href="#" target="repro" class="link" >merengue mp3</a>
        </div>
        <div id="repro3">
            <iframe src="/_swf/repro1.swf" frameborder="0" ></iframe>
        </div>
    </div>';
    return $contenido;
}

function getPublicidad()
{
    global $connectPDO;

    $templatePublicidad = '
        <div class="espacios">
            <div class="tituloespacio">
                <a href="_[LINKPUBLICIDAD]_">_[TITULOPUBLICIDAD]_</a>
            </div>
            <div class="imagenespacio">
                <a href="_[LINKPUBLICIDAD]_"><img src="_[URLIMAGEN]_" style="height:310px; width:210px"></a>
            </div>
        </div>
    ';

    $buscame = array('_[TITULOPUBLICIDAD]_','_[URLIMAGEN]_','_[LINKPUBLICIDAD]_');
    $sql = 'SELECT p.* FROM '.PREFIXTABLA.'_publicidad as p,'.PREFIXTABLA.'_users as u
    WHERE p.codigo_publicidad = u.publicidad_asignada
    AND p.activa';

    $publicidad = array();
    $espacios = '';

    $dataEx = $connectPDO->Execute($sql);
    if($dataEx->rowCount()>0)
    {
        while($data = $dataEx->fetch())
        {
            $publicidad[$data['codigo_publicidad']] = array(
                'titulo'     => $data['titulo'],
                'url_imagen' => $data['url_imagen'],
                'link'       => $data['link']
                );
        }
    }

    for($i=1;$i<=4;$i++)
    {
        $titulo     = ($publicidad[$i]['titulo']=='')?'Publica aquí':$publicidad[$i]['titulo'];
        $url_imagen = ($publicidad[$i]['url_imagen']=='')?'/imagenes/publica_aqui.jpg':$publicidad[$i]['url_imagen'];
        $link       = ($publicidad[$i]['link']=='')?'javascript:void(0)':$publicidad[$i]['link'];

        $espacios .= str_replace($buscame, array($titulo,$url_imagen,$link), $templatePublicidad);
    }

    $contenido = '
        <div class="titulo1" style="margin:0em 0.2em 0em 0.2em" >
            <a href="javascript:void(0);"  >PUBLICIDAD</a>
        </div>

        <div id="publicidad">
            '.$espacios.'
        </div>';
    return $contenido;
}

function getContacto()
{
    $_SESSION['n1'] = rand(1,20);
    $_SESSION['n2'] = rand(1,20);
    $_SESSION['expect'] = $_SESSION['n1']+$_SESSION['n2'];
    $contenido = '
    <div id="main-container-contacto">
        <div id="form-container">
            <h1>Contactenos</h1>
            <h2>Por favor, cuentenos sus inquietudes</h2>
            <form id="contact-form" name="contact-form" method="post" action="/_submit_contacto.php">
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td width="15%"><label for="name">Nombre</label></td>
                        <td width="70%"><input type="text" name="name" id="name" value="" /></td>
                        <td width="15%" id="errOffset">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><label for="email">Email</label></td>
                        <td><input type="text" name="email" id="email" value="" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><label for="subject">Asunto</label></td>
                        <td><select name="subject" id="subject">
                            <option value="" selected="selected"> - Elija -</option>
                            <option value="Pregunta">Pregunta</option>
                            <option value="Propuesta de negocios">Propuesta de negocios</option>
                            <option value="Cotizacion">Cotización</option>
                        </select>          </td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="top"><label for="message">Mensaje</label></td>
                        <td><textarea name="message" id="message" class="validate[required]" cols="35" rows="5"></textarea></td>
                        <td valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><label for="captcha">'.$_SESSION['n1'].' + '.$_SESSION['n2'].'</label></td>
                        <td><input type="text" class="validate[required,custom[onlyNumber]]" name="captcha" id="captcha" /></td>
                        <td valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="top">&nbsp;</td>
                        <td colspan="2">
                            <button type="submit" id="btn_enviar_contacto" >Enviar</button>
                            <button type="reset" id="btn_reset_contacto" >Reset</button>
                            <img id="loading" src="/_css/img/ajax-load.gif" width="16" height="16" alt="loading" /></td>
                        </tr>
                    </table>
                </form>

                <?php echo $success; ?>

            </div>

        </div>
    ';
    return $contenido;
}

?>