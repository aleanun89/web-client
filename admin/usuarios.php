<?php
include("../config.php");
include($docroot ."/lib/admin.php");
include($docroot ."/html/static/header.php"); 
?>
<section id="main">
<h1>Lista de usuarios</h1>

<?php
include_once($docroot ."/lib/httpcurl.php");
$parametros = Array('username' => $_SESSION["username"], 'token' => $_SESSION["token"]);
$url = 'https://api.alergant.es/listarusuarios/';
$json = json_decode(httpPost($url, $parametros));
$estado = $json[0]->{'status'};

// Hay un error en la consulta a la API
if($estado!=1) {
   echo "Hay un error en el lista de los usuarios. Codigo de error: ". $json[0]->{'status'};
   echo "</section>";
   include($docroot ."/html/static/footer.php");
   exit;
}

// La consulta a la API ha ido bien. Muestro resultados.
if($json[0]->{'longitud'}==0) {
   echo "No hay usuarios registrados.";
   echo "</section>";
   include($docroot ."/html/static/footer.php");
   exit;
}
?>

<table>
 <thead>
  <th class="titulo">Usuario</th>
  <th class="titulo">Nombre</th>
  <th class="titulo">Estado</th>
  <th class="titulo">Administrar</th>
</thead>

<tbody>

<?php
//bucle de lectura de json que añade en la tabla
for($i=1;$i<=$json[0]->{'longitud'};$i++) {
   switch($json[$i]->Estado) {
      case 1:
         $tipo = "Administrador";
	 break;
      case 2:
         $tipo = "Usuario";
	 break;
      case 3:
         $tipo = "Deshabilitada";
	 break;
      case 4:
         $tipo = "Borrada";
	 break;
      case 5:
         $tipo = "Registro";
	 break;

      default:
         $tipo = $json[$i]->Estado;
	 break;
   }
   echo "<tr>";
   echo " <td data-title=\"Usuario\">". $json[$i]->Usuario ."</td>";
   echo " <td data-title=\"Nombre\">". $json[$i]->Nombre ." ". $json[$i]->Apellidos ."</td>"; 
   echo " <td data-title=\"Estado\">". $tipo ."</td>"; 
   echo " <td data-title=\"Administrar\"><a href=\"".$web."/admin/usuarios_mod.php?id=". $json[$i]->ID ."\">Modificar</a></td>"; 
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
