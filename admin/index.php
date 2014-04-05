<?php
session_start();
include_once("../config.php");

// Si no hay sesion establecida, lo llevo a pagina de login.
if((!isset($_SESSION["token"]))||(!isset($_SESSION["username"]))) {
  Header("Location: ".$web."/login.php");
  exit;
}

// Hago peticion a la API con los datos del usuario logeado.
include($docroot ."/lib/httpcurl.php");
$parametros = Array('username' => $_SESSION["username"]);
$url = 'https://api.alergant.es/gettoken/';
$json = json_decode(httpPost($url, $parametros));
$estado = $json->{'status'};
$token = $json->{'token'};
$cuenta = $json->{'cuenta'};

// Si no coincide el token o el usuario no es Administrador, se sale.
if(($token!=$_SESSION["token"])||($cuenta!=1)) {
  Header("Location: ".$web."/index.php");
  exit;
}

include($docroot ."/html/admin/index.php");
exit;
?>
