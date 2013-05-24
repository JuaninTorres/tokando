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
	$err[]='Este campo es muy corto o está vacío!';
if(!checkLen('email'))
	$err[]='Este campo es muy corto o está vacío!';
else if(!checkEmail($_POST['email']))
	$err[]='Su email no es válido!';
if(!checkLen('subject'))
	$err[]='No ha seleccionado un título para el correo!';
if(!checkLen('message'))
	$err[]='ste campo es muy corto o está vacío!';
if((int)$_POST['captcha'] != $_SESSION['expect'])
	$err[]='El código de validación es incorrecto!';
if(count($err))
{
	echo implode('<br />',$err);
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
$mail->SetFrom($_POST['email'], $_POST['name']);
$mail->Subject = "Un nuevo contacto referente a ".strtolower($_POST['subject'])." de ".$_POST['name']." | contacto desde ".$_SERVER['HTTP_HOST'];
$mail->MsgHTML($msg);
$mail->Send();
unset($_SESSION['post']);
if($_POST['ajax'])
{
	echo '1';
}
else
{
	$_SESSION['sent']=1;
	if($_SERVER['HTTP_REFERER'])
		header('Location: '.$_SERVER['HTTP_REFERER']);
	exit;
}
function checkLen($str,$len=2)
{
	return isset($_POST[$str]) && strlen(strip_tags($_POST[$str])) > $len;
}
function checkEmail($str)
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}
?>