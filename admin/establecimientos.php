<?php
include("../config.php");
include($docroot ."/lib/admin.php");
include($docroot ."/html/static/header.php"); 
?>
<section id="main">
<h1>Lista de establecimientos</h1>

<?php
include_once($docroot ."/lib/httpcurl.php");
$parametros = Array('username' => $_SESSION["username"], 'token' => $_SESSION["token"]);
$url = 'https://api.alergant.es/listarestablecimientos/';
$json = json_decode(httpPost($url, $parametros));
$estado = $json[0]->{'status'};

// Hay un error en la consulta a la API
if($estado!=1) {
   echo "Hay un error en el lista de los establecimientos. Codigo de error: ". $json[0]->{'status'};
   echo "</section>";
   include($docroot ."/html/static/footer.php");
   exit;
}

// La consulta a la API ha ido bien. Muestro resultados.
if($json[0]->{'longitud'}==0) {
   echo "No hay establecimientos registrados.";
   echo "</section>";
   include($docroot ."/html/static/footer.php");
   exit;
}
?>

<table>
 <thead>
  <th class="titulo">Nombre</th>
  <th class="titulo">Tipo</th>
  <th class="titulo">Ubicacion</th>
  <th class="titulo">Estado</th>
  <th class="titulo">Mapa</th>
  <th class="titulo">Administrar</th>
</thead>

<tbody>

<?php
//bucle de lectura de json que añade en la tabla
for($i=1;$i<=$json[0]->{'longitud'};$i++) {
   switch($json[$i]->Tipo) {
      case 1:
         $tipo = "R";
	 break;
      case 2:
         $tipo = "H";
	 break;
      case 3:
         $tipo = "T";
	 break;
      case 4:
         $tipo = "C";
	 break;
      case 5:
         $tipo = "B";
	 break;
      case 6:
         $tipo = "O";
	 break;

      default:
         $estado = $json[$i]->Tipo;
	 break;

   }

   switch($json[$i]->Estado) {
      case 1:
         $estado = "Publicada";
	 break;
      case 2:
         $estado = "Moderacion";
	 break;

      default:
         $estado = $json[$i]->Estado;
	 break;
   }
   echo "<tr>";
   echo " <td data-title=\"Nombre\">". $json[$i]->Nombre ."</td>"; 
   echo " <td data-title=\"Tipo\">". $tipo ."</td>"; 
   echo " <td data-title=\"Direccion\">". $json[$i]->Poblacion ." (".$json[$i]->Provincia.")</td>"; 
   echo " <td data-title=\"Estado\">". $estado ."</td>"; 
   echo " <td data-title=\"Mapa\"><img src='http://maps.googleapis.com/maps/api/staticmap?center=".$json[$i]->Lat.",".$json[$i]->Lng."&zoom=15&size=350x200&maptype=roadmap&markers=color:red|label:".$tipo."|".$json[$i]->Lat.",".$json[$i]->Lng."&sensor=false'>";
   echo " <td data-title=\"Administrar\"><a href=\"".$web."/admin/establecimientos_mod.php?id=". $json[$i]->ID ."\">Modificar</a></td>"; 
   echo "</tr>";
 }
?>
 </tbody>
</table>
</section>

<?php 
include($docroot ."/html/static/footer.php"); 
exit;
?>
