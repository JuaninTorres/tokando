<?php
/* config start */
$emailAddress = 'juanin.torres@gmail.com';
/* config end */
require $_SERVER['DOCUMENT_ROOT']."/class/phpmailer/class.phpmailer.php";
session_start();
foreach($_POST as $k=>$v)
{
	if(ini_get('magic_quotes_gpc'))
	$_POST[$k]=stripslashes($_POST[$k]);
	$_POST[$k]=htmlspecialchars(strip_tags($_POST[$k]));
}
$err = array();
if(!checkLen('name'))
	$err[]='<strong>NOMBRE</strong>: Este campo es muy corto o está vacío!';
if(!checkLen('email'))
	$err[]='<strong>EMAIL</strong>: Este campo es muy corto o está vacío!';
else if(!checkEmail($_POST['email']))
	$err[]='<strong>EMAIL</strong>: Su email no es válido!';
if(!checkLen('subject'))
	$err[]='<strong>TÍTULO</strong>: No ha seleccionado un título para el correo!';
if(!checkLen('message'))
	$err[]='<strong>MENSAJE</strong>: Este campo es muy corto o está vacío!';
if((int)$_POST['captcha'] != $_SESSION['expect'])
	$err[]='<strong>VALIDACIÓN</strong>: El código de validación es incorrecto!';
if(count($err))
{
	echo '<ul><li>'.implode('</li><li>',$err).'</li></ul>';
	exit;
}
$msg=
'Name:	'.$_POST['name'].'<br />
Email:	'.$_POST['email'].'<br />
IP:	'.$_SERVER['REMOTE_ADDR'].'<br /><br />
Message:<br /><br />
'.nl2br($_POST['message']).'
';
$mail = new PHPMailer();
$mail->IsMail();
$mail->AddReplyTo($_POST['email'], $_POST['name']);
$mail->AddAddress($emailAddress);
$mail->SetFrom('noreply@'.$_SERVER['SERVER_NAME']);
$mail->Subject = "Un nuevo contacto referente a ".strtolower($_POST['subject'])." de ".$_POST['name']." | contacto desde ".$_SERVER['HTTP_HOST'];
$mail->MsgHTML($msg);
$mail->Send();
unset($_SESSION['post']);

echo 1;
exit;

function checkLen($str,$len=2)
{
	return isset($_POST[$str]) && strlen(strip_tags($_POST[$str])) > $len;
}
function checkEmail($str)
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}
?>