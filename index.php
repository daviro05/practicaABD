<?php 
session_start();
include "funciones_BD.php";
//include 'config_cookie.php';
?>
<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>MusicApp</title>
<link rel="shortcut icon" href="./img/favicon.ico" />
<link href="./css/style_index.css" rel="stylesheet" type="text/css" />
<link href="./css/style_avisos.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
<div id="logo">MusicApp</div>
  <div id="header">
  	<div id="menu_inicio"><br/><br/>
	  	<form id="f_login" action="logarse.php" method="post">
	  	<input class="login" type="text" name="usuario" placeholder="Nick de usuario"/><br>
	  	<input class="login" type="password" name="password" placeholder="Password"/><br>
	  	<input class="cambiar" id="b_entrar" type="submit" name="Entrar" value="Entrar"/><br><a class="registrarse" href="registro.php">Registrarse</a>
	  	</form>
  	</div>
  </div>
  </div>
  <div id="content" class="contenido">
  <?php 
  		/**
  		 * Errores cometidos en el login o validacion de claves.
  		 */
  
	  if( isset( $_GET['error'] ) )
	  {
	  	if( $_GET['error']=='bd_noselect' )
	  	{
	  		echo "<p class='error_2'><img src='./img/error.png' style='width:20px'> No se ha podido conectar con la BD</p>";
	  	}
	  	if( $_GET['error']=='error_nickopass' )
	  	{
	  		echo "<p class='error_2'><img src='./img/error.png' style='width:20px'> El nick o password no son correctos</p>";
	  	}
	  	if( $_GET['error']=='error_pass' )
	  	{
	  		echo "<p class='error_2'><img src='./img/error.png' style='width:20px'> El password no cumple los requisitos</p>";
	  	}
	  	if( $_GET['error']=='no_nick' )
	  	{
	  		echo "<p class='error_2'><img src='./img/error.png' style='width:20px'> No se ha encontrado nick</p>";
	  	}
	  	if( $_GET['error']=='no_conect')
	  	{
	  		echo "<p class='error_2'><img src='./img/error.png' style='width:20px'> No se ha encontrado nick</p>";
	  	}
	  }
	  
	  /**
	   * Estados producidos por la desconexion de usuarios.
	   */
	  if( isset( $_GET['estado'] ) )
	  {
	  	if( $_GET['estado']=='desconectar' )
	  	{
			session_destroy();
	  	}
	  	
	  }
	  
  ?>
  <!--<div id="footer"><p class="copy">&copy; Copyright 2017 David Rodríguez Marco. 3º Ing.Informática</p></div>-->
</div>
</body>
</html>