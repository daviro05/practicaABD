<?php

session_start();
$enlace=conecta();

$local = eleccion_servidor(); //true para local y false para servidor.

if($local == true)
{
	$nombre_db="socmica";
}
else
{
	$nombre_db="a4100383_socmica";
}

$seleccionar_bd=mysql_select_db($nombre_db,$enlace);

if(isset($_COOKIE['micookie']))
{
	$cookie = htmlentities($_COOKIE['micookie']);
	$cookie = explode("%",$cookie);
	$user = $cookie[0];
	$id = $cookie[1];
	$ip = $cookie[2];
	if ($HTTP_X_FORWARDED_FOR == "")
	{
		$ip2 = getenv(REMOTE_ADDR);
	}
	else
	{
		$ip2 = getenv(HTTP_X_FORWARDED_FOR);
	}
	if($ip == $ip2)
	{
		$query = mysql_query("SELECT * FROM usuarios WHERE otros_detalles='".$id."' and nick='".$user."'") or die(mysql_error());
		$row = mysql_fetch_array($query);
		if(isset($row['nick']))
		{
			$_SESSION["usuario"] = $row['nick'];
			$_SESSION["tipo"] = "normal";
			$_SESSION["logeado"] = "SI";
		}
		mysql_close($enlace);
	}
}
?>