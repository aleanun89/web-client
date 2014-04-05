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
   echo "<h1>Modificar usuario</h1>";

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

   if($_POST["passmd5"]!=$_POST["pass2md5"]) {
      echo "Los passwords no coinciden.";
   } else {
      $url = 'https://api.alergant.es/updateusuario/';
      if($_POST["passmd5"]=="d41d8cd98f00b204e9800998ecf8427e") {
         // Password en blanco, no lo cambio
         $parametros = Array('username' => $_SESSION["username"], 'token' => $_SESSION["token"], 'id' => $_POST["id"], 'nombre' => $_POST["nombre"], 'apellidos' => $_POST["apellidos"], 'correo' => $_POST["correo"], 'estado' => $_POST["tipo"], 'alergias' => $alergias);
      } else {
         // El password se cambia
         $parametros = Array('username' => $_SESSION["username"], 'token' => $_SESSION["token"], 'id' => $_POST["id"], 'password' => $_POST["passmd5"], 'nombre' => $_POST["nombre"], 'apellidos' => $_POST["apellidos"], 'correo' => $_POST["correo"], 'estado' => $_POST["tipo"], 'alergias' => $alergias);

      }

      $json = json_decode(httpPost($url, $parametros));
      $estado = $json->{'status'};
      if($estado==1) {
         echo "Informacion del usuario actualizada con exito.<br><br><a href=\"".$web."/admin/usuarios.php\">Volver a la lista de usuarios</a>";
      } else {
         echo "ERROR: No se ha podido actualizar el usuario. Codigo de error: ". $status;
      }
   }

   echo "</section>";
   include($docroot ."/html/static/footer.php"); 
   exit;
}


?>
<script type="text/javascript" charset="UTF-8" src="<?php echo $web; ?>/js/md5.js"></script>
<script type="text/javascript">
function encriptacion() {
   var passmd5;
   var pass2md5;
   passmd5 = md5(document.forms["userform"].elements["password"].value);
   pass2md5 = md5(document.forms["userform"].elements["password2"].value);
   document.forms["userform"].elements["passmd5"].value = passmd5;
   document.forms["userform"].elements["pass2md5"].value = pass2md5;
   document.forms["userform"].elements["password"].value = 'xxxxxxxx';
   document.forms["userform"].elements["password2"].value = 'xxxxxxxx';
   document.forms["userform"].submit();
}
</script>
<?php
echo "<section id=\"main\">";
echo "<h1>Modificar usuario</h1>";
include_once($docroot ."/lib/httpcurl.php");
$url = 'https://api.alergant.es/estadosusuario/';
$parametros = Array('status' => 0);
$json_cuentas = json_decode(httpPost($url, $parametros));
$estado = $json_cuentas[0]->{'status'};
// Hay un error en la consulta a la API
if($estado!=1) {
   echo "Hay un error en al obtener los datos de los estados de los usuario. Codigo de error: ". $json[0]->{'status'};
   echo "</section>";
   include($docroot ."/html/static/footer.php");
   exit;
}

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

$parametros = Array('username' => $_SESSION["username"], 'token' => $_SESSION["token"], 'id' => $_GET["id"]);
$url = 'https://api.alergant.es/datosusuario/';
$json = json_decode(httpPost($url, $parametros));
$estado = $json[0]->{'status'};
// Hay un error en la consulta a la API
if($estado!=1) {
   echo "Hay un error en al obtener los datos del usuario. Codigo de error: ". $json[0]->{'status'};
   echo "</section>";
   include($docroot ."/html/static/footer.php");
   exit;
}

// La consulta a la API ha ido bien. Muestro resultados.
?>
<div class="formCenter">
<form id="userform" name="userform" action="<?php echo $web; ?>/admin/usuarios_mod.php?id=<?php echo $_GET["id"]; ?>&mod=1" method="post">
<div class="divform">
 <div class="r">
  <input type="hidden" name="passmd5"><input type="hidden" name="pass2md5"><input type="hidden" name="id" value="<?php echo $json[1]->{'ID'}; ?>">
  <div class="c"><label for="Nombre">Nombre:</label><input type="text" name="nombre" value="<?php echo $json[1]->{'Nombre'}; ?>"></div>
  <br>
  <div class="c"><label for="Apellidos">Apellidos:</label><input type="text" name="apellidos" value="<?php echo $json[1]->{'Apellidos'}; ?>"></div>
  <br>
  <div class="c"><label for="Usuario">Usuario:</label><input type="text" name="usuario" value="<?php echo $json[1]->{'Usuario'}; ?>" disabled></div>
  <br>
  <div class="c"><label for="Password">Password: (Vacio para no cambiar)</label><input type="password" name="password" value=""></div>
  <br>
  <div class="c"><label for="Password">Repetir password:</label><input type="password" name="password2" value=""></div>
  <br>
  <div class="c"><label for="Correo">Correo:</label><input type="text" name="correo" value="<?php echo $json[1]->{'Correo'}; ?>"></div>
  <br>
  <div class="c"><label for="Tipo">Tipo:</label>
   <select name="tipo" <?php if($json[1]->{'Estado'}==1) echo "disabled"; ?>>
<?php for($i=1;$i<count($json_cuentas);$i++) { ?>
    <option value="<?php echo $json_cuentas[$i]->{'ID'}; ?>" <?php if($json_cuentas[$i]->{'ID'}==$json[1]->{'Estado'}) echo "selected "; ?>><?php echo $json_cuentas[$i]->{'Descripcion'}; ?></option>
<?php } ?>
   </select>
  </div>
  <br>
  <div class="c"><label for="Tipo">Alergia/Intolerancia:</label><br>
<?php 
$alergias = alergantdecode($json[1]->{'Alergias'});

for($i=1;$i<count($json_alergias);$i++) { 
   $check="";
   for($j=0;$j<count($alergias);$j++) {
      if($alergias[$j]==$json_alergias[$i]->{'ID'}) $check="checked";
   }
   echo "<nobr><input type=\"checkbox\" name=\"a".$json_alergias[$i]->{'ID'}."\" ".$check.">".$json_alergias[$i]->{'Descripcion'}."</nobr> ";
}
?> 
  </div>
  <br>
  <div class="c"><label for="IP">Ultima direcci&oacute;n IP:</label><input type="text" name="ip" value="<?php echo $json[1]->{'IP'}; ?>" disabled></div>
  <br>
  <div class="c"><label for="UltimaSesion">Ultimo inicio de sesi&oacute;n:</label><input type="text" name="ultimasesion" value="<?php echo $json[1]->{'UltimaSesion'}; ?>" disabled></div>
 </div>
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
