<?php
include("../config.php");
include($docroot ."/lib/admin.php");
include($docroot ."/html/static/header.php"); 

// Funcion para decodificar todas las alergias/intolerancias dado un ID de Alergia
function alergantdecode($idalergias) {
   $pow2 = array("8192", "4096", "2048", "1024", "512", "256", "128", "64", "32", "16", "8", "4", "2", "1");
   $alergant = array();

   $j=0;
   for($i=0; $i<count($pow2); $i++) {
      if($idalergias>=$pow2[$i]) {
           $idalergias=$idalergias-$pow2[$i];
           $alergant[$j]=$pow2[$i];
           $j++;
        }
   }
   return $alergant;

}

// Cambiar usuario
if(isset($_GET["mod"])) {
   echo "<section id=\"main\">";
   echo "<h1>Modificar establecimiento</h1>";

   $alergias=0;
   if($_POST["a1"]=="on") $alergias=$alergias+1;
   if($_POST["a2"]=="on") $alergias=$alergias+2;
   if($_POST["a4"]=="on") $alergias=$alergias+4;
   if($_POST["a8"]=="on") $alergias=$alergias+8;
   if($_POST["a16"]=="on") $alergias=$alergias+16;
   if($_POST["a32"]=="on") $alergias=$alergias+32;
   if($_POST["a64"]=="on") $alergias=$alergias+64;
   if($_POST["a128"]=="on") $alergias=$alergias+128;
   if($_POST["a256"]=="on") $alergias=$alergias+256;
   if($_POST["a512"]=="on") $alergias=$alergias+512;
   if($_POST["a1024"]=="on") $alergias=$alergias+1024;
   if($_POST["a2048"]=="on") $alergias=$alergias+2048;

   if($_POST["alergant"]=="on") $alergant="1"; else $alergant="0"; 
   if($_POST["acuerdoasociacion"]=="on") $acuerdoasociacion="1"; else $acuerdoasociacion="0";

   $url = 'https://api.alergant.es/updateestablecimiento/';
   $parametros = Array('id' => $_POST["id"],
                       'tipo' => $_POST["tipo"],
                       'estado' => $_POST["estado"],
		       'nombre' => $_POST["nombre"],
		       'direccion' => $_POST["direccion"],
		       'cp' => $_POST["cp"],
		       'poblacion' => $_POST["poblacion"],
		       'provincia' => $_POST["provincia"],
		       'pais' => $_POST["pais"],
		       'telefono' => $_POST["telefono"],
		       'lat' => $_POST["lat"],
		       'lng' => $_POST["lng"],
		       'web' => $_POST["web"],
		       'webreserva' => $_POST["webreserva"],
		       'carta' => $_POST["carta"],
		       'foto' => $_POST["foto"],
		       'descripcion' => $_POST["descripcion"],
		       'propietario' => $_POST["propietario"],
		       'acuerdoasociacion' => $acuerdoasociacion,
		       'alergant' => $alergant,
		       'idalergia' => $alergias
		       );
   $json = json_decode(httpPost($url, $parametros));
   $estado = $json->{'status'};

   if($estado==1) {
      echo "Informacion del establecimiento actualizada con exito.<br><br><a href=\"".$web."/admin/establecimientos.php\">Volver a la lista de establecimientos</a>";
   } else {
      echo "ERROR: No se ha podido actualizar el establecimiento. Codigo de error: ". $status;
   }

   echo "</section>";
   include($docroot ."/html/static/footer.php"); 
   exit;
}


// Comienza la pagina de modificacion
echo "<section id=\"main\">";
echo "<h1>Modificar establecimiento</h1>";
include_once($docroot ."/lib/httpcurl.php");

$url = 'https://api.alergant.es/alergias/';
$parametros = Array('status' => 0);
$json_alergias = json_decode(httpPost($url, $parametros));
$estado = $json_alergias[0]->{'status'};
// Hay un error en la consulta a la API
if($estado!=1) {
   echo "Hay un error en al obtener los datos de los distintos tipos de alergias/intolerancias. Codigo de error: ". $json[0]->{'status'};
   echo "</section>";
   include($docroot ."/html/static/footer.php");
   exit;
}

$parametros = Array('id' => $_GET["id"]);
$url = 'https://api.alergant.es/detalles/';
$json = json_decode(httpPost($url, $parametros));
$estado = $json[0]->{'status'};
// Hay un error en la consulta a la API
if($estado!=1) {
   echo "Hay un error en al obtener los datos del establecimiento. Codigo de error: ". $json[0]->{'status'};
   echo "</section>";
   include($docroot ."/html/static/footer.php");
   exit;
}

// La consulta a la API ha ido bien. Muestro resultados.
?>
<div class="formCenter">
<form id="userform" name="userform" action="<?php echo $web; ?>/admin/establecimientos_mod.php?id=<?php echo $_GET["id"]; ?>&mod=1" method="post">
<div class="divform">
 <div class="r">
  <input type="hidden" name="id" value="<?php echo $json[1]->{'ID'}; ?>">
  <div class="c"><label for="Tipo">Tipo:</label>
   <select name="tipo">
    <option value="1" <?php if($json[1]->{'Tipo'}==1) echo "selected"; ?>>Restaurantes</option>
    <option value="2" <?php if($json[1]->{'Tipo'}==2) echo "selected"; ?>>Hoteles</option>
    <option value="3" <?php if($json[1]->{'Tipo'}==3) echo "selected"; ?>>Tiendas</option>
    <option value="4" <?php if($json[1]->{'Tipo'}==4) echo "selected"; ?>>Comedores</option>
    <option value="5" <?php if($json[1]->{'Tipo'}==5) echo "selected"; ?>>Bares</option>
    <option value="6" <?php if($json[1]->{'Tipo'}==6) echo "selected"; ?>>Otros</option>
   </select>
  </div>
  <br>
  <div class="c"><label for="Estado">Estado:</label>
   <select name="estado">
    <option value="1" <?php if($json[1]->{'Estado'}==1) echo "selected"; ?>>Publicado</option>
    <option value="2" <?php if($json[1]->{'Estado'}==2) echo "selected"; ?>>Moderacion</option>
   </select>
  </div>
  <br>
  <div class="c"><label for="Nombre">Nombre:</label><input type="text" name="nombre" value="<?php echo $json[1]->{'Nombre'}; ?>"></div>
  <br>
  <div class="c"><label for="Direccion">Direccion:</label><input type="text" name="direccion" value="<?php echo $json[1]->{'Direccion'}; ?>"></div>
  <br>
  <div class="c"><label for="CP">Codigo Postal:</label><input type="text" name="cp" value="<?php echo $json[1]->{'CP'}; ?>"></div>
  <br>
  <div class="c"><label for="Poblacion">Poblacion:</label><input type="text" name="poblacion" value="<?php echo $json[1]->{'Poblacion'}; ?>"></div>
  <br>
  <div class="c"><label for="Provincia">Provincia:</label><input type="text" name="provincia" value="<?php echo $json[1]->{'Provincia'}; ?>"></div>
  <br>
  <div class="c"><label for="Pais">Pais:</label><input type="text" name="pais" value="<?php echo $json[1]->{'Pais'}; ?>"></div>
  <br>
  <div class="c"><label for="Telefono">Telefono:</label><input type="text" name="telefono" value="<?php echo $json[1]->{'Telefono'}; ?>"></div>
  <br>
  <div class="c"><label for="Lat">Latitud:</label><input type="text" name="lat" value="<?php echo $json[1]->{'Lat'}; ?>"></div>
  <br>
  <div class="c"><label for="Lng">Longitud:</label><input type="text" name="lng" value="<?php echo $json[1]->{'Lng'}; ?>"></div>
  <br>
  <div class="c"><label for="Web">Pagina Web:</label><input type="text" name="web" value="<?php echo $json[1]->{'Web'}; ?>"></div>
  <br>
  <div class="c"><label for="WebReserva">Pagina Web de las Reservas:</label><input type="text" name="webreserva" value="<?php echo $json[1]->{'WebReserva'}; ?>"></div>
  <br>
  <div class="c"><label for="Carta">Carta:</label><input type="text" name="carta" value="<?php echo $json[1]->{'Carta'}; ?>"></div>
  <br>
  <div class="c"><label for="Foto">Foto:</label><input type="text" name="foto" value="<?php echo $json[1]->{'Foto'}; ?>"></div>
  <br>
  <div class="c"><label for="Descripcion">Descripcion:</label><textarea rows="8" name="descripcion"><?php echo $json[1]->{'Descripcion'}; ?></textarea></div>
  <br>
  <div class="c"><label for="Propietario">ID del usuario propietario del establecimiento:</label><input type="text" name="propietario" value="<?php echo $json[1]->{'Propietario'}; ?>"></div>
  <br>
  <div class="c"><label for="Tipo">Acuerdos:</label><br>
   <input type="checkbox" name="acuerdoasociacion" <?php if($json[1]->{'AcuerdoAsociacion'}) echo "checked"; ?>>Acuerdo con asociacion&nbsp;&nbsp;&nbsp;&nbsp; 
   <input type="checkbox" name="alergant" <?php if($json[1]->{'Alergant'}) echo "checked"; ?>>Restaurante Alergant (Developer Choice)
  </div>
  <br>
  <div class="c"><label for="Tipo">Alergia/Intolerancia:</label><br>
<?php 
$alergias = alergantdecode($json[1]->{'IDAlergia'});

for($i=1;$i<count($json_alergias);$i++) { 
   $check="";
   for($j=0;$j<count($alergias);$j++) {
      if($alergias[$j]==$json_alergias[$i]->{'ID'}) $check="checked";
   }
   echo "<nobr><input type=\"checkbox\" name=\"a".$json_alergias[$i]->{'ID'}."\" ".$check.">".$json_alergias[$i]->{'Descripcion'}."</nobr> \r\n";
}
?> 
  </div>
  <br>

</div>
<br>
<center><input class="formatBtn" type="button" value="Volver" onClick="history.go(-1);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Cambiar" onClick="encriptacion();"></center>
</form>
</div>


</section>

<?php 
include($docroot ."/html/static/footer.php"); 
exit;
?>
