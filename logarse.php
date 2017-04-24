<?php
include "funciones_BD.php";

$local = eleccion_servidor(); //true para local y false para servidor.

$enlace=conecta();

if($enlace == false)
{
header('Location: index.php?error=no_conect');
}

if($local == true)
{
	$nombre_db="practicaabd";
}
else
{
	//$nombre_db="a4100383_socmica";
}

$seleccionar_bd=mysql_select_db($nombre_db,$enlace);

if($seleccionar_bd == false)
{
	header('Location: index.php?error=bd_noselect');
}
else 
{

	if($_POST['usuario']!="")
	{
		$usuario=$_POST['usuario'];
		$_SESSION['usuario']=$usuario;
		$clave=$_POST['password'];
		
			if(validar_clave($clave))
			{
				$usuario=mysql_real_escape_string($usuario);
				$clave=md5(mysql_real_escape_string($clave));
				
				$sentencia=mysql_query("select * from usuarios 
										where nick='$usuario' and password='$clave'",$enlace);
				if(mysql_num_rows($sentencia)==1)
				{
					$fila=mysql_fetch_array($sentencia);
					$valor=$fila['nick'];
					$_SESSION['usuario']=$valor;
					$_SESSION['tipo']="normal";
					
					header("Location:mensajes.php");
				}
				else
				{
					header('Location: index.php?error=error_nickopass');
				}
				mysql_close($enlace);
				
			}
			else 
			{
				header('Location: index.php?error=error_pass');
			}
	}
	else
	{
		header('Location: index.php?error=no_nick');
		$_SESSION['nick'] = "";
	}
	
	echo "<br><br><p>Usuario: <b>".$_SESSION['usuario']."</b> - tipo usuario <b>". $_SESSION['tipo']."</b></p>";
}
?>