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
   // Envio formulario
   include($docroot ."/html/static/header.php");  
   echo "<section id=\"main\">";
   echo "  <h1>Formulario enviado</h1>";
   echo "El formulario se ha enviado correctamente. En breve contactaremos con usted.";

   $mensaje = "Contacto Alergant\r\n";
   $mensaje .= "-------------------\r\n";
   $mensaje .= "\r\n";
   $mensaje .= "Nombre: ".$_POST["nombre"]." (IP: ".$_SERVER["REMOTE_ADDR"].")\r\n"; 
   $mensaje .= "Correo: ".$_POST["correo"]."\r\n"; 
   $mensaje .= "\r\n";
   $mensaje .= "$_POST[comentarios]\r\n"; 
   $mensaje .= "\r\n";
   mail($adminmail, "Contacto ". $titulo, $mensaje, "From: ".$_POST["nombre"]. " ". $_POST["correo"]);
 
   echo "</section>";
   include($docroot ."/html/static/footer.php"); 
   exit;
}

// Pagina de contacto
include($docroot ."/html/static/header.php");  
?>	
<section id="main">
  <h1>Contacto</h1>
<div class="formCenter">
<form id="userform" name="userform" action="<?php echo $web; ?>/contacto.php" method="post">
<input type="hidden" name="send" value="1">
<div class="divform">
 <div class="r">
  <div class="c"><label for="Nombre">Nombre:</label><input type="text" name="nombre"></div>
  <br>
  <div class="c"><label for="Correo">Correo:</label><input type="text" name="correo"></div>
  <br>
  <div class="c"><label for="Comentarios">Comentarios:</label><textarea name="comentarios" rows="8"></textarea></div>
  <br> 
  <center><input type="submit" value="Enviar"></center>
 </div>
</div>
</form>
</div>
</section>
<?php
include($docroot ."/html/static/footer.php"); 
exit;
?>	
