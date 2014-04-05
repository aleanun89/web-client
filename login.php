<?php
session_start();

// Si tengo token en la sesion, no necesito identificarme.
if(!isset($_SESSION["token"])) {

  // No tengo token, y me estan mandando usuario y password. Se estan logeando.
  if((isset($_POST["username"]))&&(isset($_POST["passmd5"]))) {

    // Pregunto a la API si es correcto el usuario y el password.
    include("./lib/httpcurl.php");
    $parametros = Array('username' => $_POST["username"], 
                        'password' => $_POST["passmd5"]
    );

    $url = 'https://api.alergant.es/authcheck/';
    $json = json_decode(httpPost($url, $parametros));
    $estado = $json->{'status'};
    if($estado!=1)  {
       // Autenticacion incorrecta
       Header("Location: login.php?error");
       exit;
    }

    // Autenticacion correcta. Genero el token.
    $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdfghijklmnopqrstuvwxyz0123456789";
    $token = md5(substr(md5(str_shuffle($alphanum)), 0, 10));
    $parametros = Array('username' => $_POST["username"], 
                        'token' => $token,
                        'ip' => $_SERVER["REMOTE_ADDR"]
    );

    $url = 'https://api.alergant.es/writetoken/';
    $json = json_decode(httpPost($url, $parametros));
    $estado = $json->{'status'};
    if($estado==1) {
       // Token generado y actualizado correctamente en la BBDD.
       $_SESSION["token"] = $token;
       $_SESSION["username"] = $_POST["username"];
       Header("Location: index.php");
     } else {
       // Token no actualizado correctamente. Vuelvo a mandar a hacer login.
       session_unset();
       Header("Location: login.php?session");
     }
     exit;
  }

  // No tengo token y no me estan mandando ni usuario ni password, muestro pagina de login.
  include("./html/login.php");
  exit;
}

Header("Location: index.php");
exit;
?>
