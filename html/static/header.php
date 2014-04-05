<?php if(!isset($cuenta)) $cuenta=0; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title><?php echo $titulo; ?> - Panel de Administracion</title>
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
		<link href="<?php echo $web; ?>/css/main.css" type="text/css" rel="stylesheet">
		<link href="<?php echo $web; ?>/css/table.css" type="text/css" rel="stylesheet">
		<link href="<?php echo $web; ?>/css/imagesTypes.css" type="text/css" rel="stylesheet">
<?php
if(eregi("login.php", $_SERVER["PHP_SELF"])) {
   echo "		<link href=\"".$web."/css/login.css\" type=\"text/css\" rel=\"stylesheet\">";
   } else {
   echo "		<link href=\"".$web."/css/forms.css\" type=\"text/css\" rel=\"stylesheet\">";
   }
?>


		<!--[if lt IE 9]>
		<script src="<?php echo $web; ?>/js/html5.js"></script>
		<![endif]-->
		<script type='text/javascript' src='<?php echo $web; ?>/js/respond.min.js'></script>
	</head>
	<body>
		
		<div id="wrapper">
		
		  <header>
				
				
	     <!-- <h1><a href="http://www.alergant.es">Alergant</a></h1>-->
	     <a href="<?php echo $web; ?>"><img src="<?php echo $web; ?>/images/alergant_logo.png" class="logoinicio" border="0"></a>
				
				<nav>
					<ul>
<?php
if($cuenta==1) {
// Menu de administrador
?>
						<li><a href="<?php echo $web; ?>/" title="Inicio">Inicio</a></li>
						<li><a href="<?php echo $web; ?>/buscar.php" title="Buscar">Buscar</a></li>
						<li><a href="<?php echo $web; ?>/admin/usuarios.php" title="Usuarios">Usuarios</a></li>
						<li><a href="<?php echo $web; ?>/admin/establecimientos.php" title="Establecimientos">Establecimientos</a></li>
						<li><a href="<?php echo $web; ?>/user/preferencias.php" title="Preferencias">Preferencias</a></li>
						<li><a href="<?php echo $web; ?>/contacto.php" title="Contacto">Contacto</a></li>
                        			<li><a href="<?php echo $web; ?>/logout.php" title=Logout">Logout</a></li>
<?php
} elseif ($cuenta==2) {
// Usuario autenticado
?>
						<li><a href="<?php echo $web; ?>/" title="Inicio">Inicio</a></li>
						<li><a href="<?php echo $web; ?>/buscar.php" title="Buscar">Buscar</a></li>
						<li><a href="<?php echo $web; ?>/user/misestablecimientos.php" title="Mis Establecimientos">Establecimientos</a></li>
						<li><a href="<?php echo $web; ?>/user/preferencias.php" title="Preferencias">Preferencias</a></li>
						<li><a href="<?php echo $web; ?>/contacto.php" title="Contacto">Contacto</a></li>
                        			<li><a href="<?php echo $web; ?>/logout.php" title=Logout">Logout</a></li>
<?php
} else {
// Usuario publico
?>
						<li><a href="<?php echo $web; ?>/" title="Inicio">Inicio</a></li>
						<li><a href="<?php echo $web; ?>/buscar.php" title="Buscar">Buscar</a></li>
						<li><a href="<?php echo $web; ?>/contacto.php" title="Contacto">Contacto</a></li>
                        			<li><a href="<?php echo $web; ?>/login.php" title=Login">Login</a></li>
<?php
}
?>
					</ul>
				</nav>
				
				<div id="banner"></div>
				
			</header>	
