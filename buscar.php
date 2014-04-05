<?php
session_start();
include("./config.php");

// Hago peticion a la API con los datos del usuario logeado.
include("./lib/httpcurl.php");
if(isset($_SESSION["username"])) {
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
}

if(isset($_POST["send"])) {
   // Resultados
   include($docroot ."/html/static/header.php");  
   echo "<section id=\"main\"><h1>Resultados de busqueda</h1>";

   include_once($docroot ."/lib/httpcurl.php");
   $parametros = Array('busqueda' => $_POST["busqueda"], 'tipo' => 'vacio');
   $url = 'https://api.alergant.es/buscar/';
   $json = json_decode(httpPost($url, $parametros));
   $estado = $json[0]->{'status'};

   // Hay un error en la consulta a la API
   if($estado==100) {
      echo "No hay resultados de busqueda. "; 
      echo "</section>";
      include($docroot ."/html/static/footer.php"); 
      exit;
   }
?>
<table>
 <thead>
  <th class="titulo">Nombre</th>
  <th class="titulo">Tipo</th>
  <th class="titulo">Direccion</th>
  <th class="titulo">Ubicacion</th>
  <th class="titulo">Mapa</th>
 </thead>

 <tbody>
<?php
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

   echo "<tr>";
   echo " <td data-title=\"Nombre\">". $json[$i]->Nombre ."</td>";
   echo " <td data-title=\"Tipo\">". $tipo ."</td>";
   echo " <td data-title=\"Direccion\">". $json[$i]->Direccion ."</td>";
   echo " <td data-title=\"Poblacion\">". $json[$i]->Poblacion ." (".$json[$i]->Provincia.")</td>";
   echo " <td data-title=\"Mapa\"><img src='http://maps.googleapis.com/maps/api/staticmap?center=".$json[$i]->Lat.",".$json[$i]->Lng."&zoom=14&size=300x200&maptype=roadmap&markers=color:red|label:".$tipo."|".$json[$i]->Lat.",".$json[$i]->Lng."&sensor=false'>";
   echo "</tr>";
}
?>
 </tbody>
</table>
</section>

<?php
   echo "</section>";
   include($docroot ."/html/static/footer.php"); 
   exit;
}

// Pagina de buscar
include($docroot ."/html/static/header.php");  
?>	
<section id="main">
  <h1>Buscar</h1>
<div class="formCenter">
<form id="userform" name="userform" action="<?php echo $web; ?>/buscar.php" method="post">
<input type="hidden" name="send" value="1">
<div class="divform">
 <div class="r">
  <div class="c"><label for="Busqueda">Introduce el nombre de la ciudad o establecimiento a buscar:</label><input type="text" name="busqueda" value=""></div>
 </div>
</div>
<br>
<center><input type="submit" value="Buscar"></center>
</form>
</div>


</section>
<?php
include($docroot ."/html/static/footer.php"); 
exit;
?>	
