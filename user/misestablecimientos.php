<?php
session_start();
include_once("../config.php");
include($docroot ."/lib/user.php");

// Codigo principal
include($docroot ."/html/static/header.php");

$parametros = Array('username' => $_SESSION["username"]);
$url = 'https://api.alergant.es/misestablecimientos/';
$json = json_decode(httpPost($url, $parametros));

if($json[0]->status!=1) {
  echo "<section id=\"main\">";
  echo "ERROR: No se ha podido procesar la peticion. Codigo de error". $json[0]->status;
  echo "</section>";
  include($docroot ."/html/static/footer.php");
  exit;
}

if($json[0]->longitud==0) {
  echo "<section id=\"main\">";
  echo "No has añadido establecimientos aun.";
  echo "</section>";
  include($docroot ."/html/static/footer.php");
  exit;
}


echo "<section id=\"main\">";
echo "<h1>Lista de establecimientos</h1>";
for($i=1;$i<=$json[0]->longitud;$i++) {
   echo "<br><b>Nombre</b>: ". $json[$i]->Nombre; 
   echo "<br>Poblacion: ". $json[$i]->Poblacion; 
   echo "<br>Provincia: ". $json[$i]->Provincia; 
   echo "<hr>";
}
echo "</section>";

include($docroot ."/html/static/footer.php");
exit;
?>
