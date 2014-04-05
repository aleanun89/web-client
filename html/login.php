<?php
include("./config.php");
include($docroot ."/html/static/header.php");
?>
<script type="text/javascript" charset="UTF-8" src="<?php echo $web; ?>/js/md5.js"></script>
<script type="text/javascript">
function encriptacion() {
   var passmd5;
   passmd5 = md5(document.forms["loginform"].elements["password"].value);
   document.forms["loginform"].elements["passmd5"].value = passmd5;
   document.forms["loginform"].elements["password"].value = 'xxxxxxxx';
   document.forms["loginform"].submit();
}
</script>
<section id="main">

<div id="login">
<?php
if(isset($_GET['error'])) {
    echo '<p class="message"><strong>ERROR</strong>: Usuario y/o contrase&ntilde;a erroneos.<br /></p>'; 
    }
if(isset($_GET['session'])) {
    echo '<p class="message"><strong>ERROR</strong>: Sesi&oacute;n cerrada por inactividad.<br /></p>'; 
    }
if(isset($_GET['logout'])) {
    echo '<div id="login_error">Has cerrado sesi&oacute;n correctamente.<br /></div>'; 
    }
?>
<form name="loginform" id="loginform" action="./login.php" method="post"> 
	<p>
<input type="hidden" name="passmd5">
		<label>Nombre de usuario<br />
		<input type="text" name="username" id="user_login" class="input" value="" size="20" tabindex="10" /></label>
	</p>
	<p>
		<label>Contrase&ntilde;a<br />
		<input type="password" name="password" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
	</p>
	<p class="submit">
		<input type="submit" class="button-primary" value="Iniciar sesi&oacute;n" onClick="encriptacion();" />
	</p>
</form>
</div>
<script type="text/javascript">
try{document.getElementById('user_login').focus();}catch(e){}
</script>

<?php
include($docroot ."/html/static/footer.php");
?>
