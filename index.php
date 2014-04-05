<?php
session_start();
include("./config.php");

// Si no hay sesion establecida, lo llevo a la pagina publica.
if((!isset($_SESSION["token"]))||(!isset($_SESSION["username"]))) {
  //Header("Location: login.php");
  include("./html/index.php");
  exit;
}

// Hago peticion a la API con los datos del usuario logeado.
include("./lib/httpcurl.php");
$parametros = Array('username' => $_SESSION["username"]);
$url = 'https://api.alergant.es/gettoken/';
$json = json_decode(httpPost($url, $parametros));
$estado = $json->{'status'};
$token = $json->{'token'};
$cuenta = $json->{'cuenta'};

// Si no coincide el token o el usuario no es Administrador, se sale.
if($token!=$_SESSION["token"]) {
  session_unset();
  Header("Location: login.php?session");
  exit;
}

// Cargo pagina panel de control de usuario
include("./html/user/index.php");

exit;
?>
