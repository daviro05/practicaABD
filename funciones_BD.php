<?php
include 'conf/conf_env.php';
#######################################
### FUNCIONES DE CONEXIÓN A BBDD ######
#######################################

function conecta()
{
	$local = eleccion_servidor(); //true para local y false para servidor.
	
	if($local == true)
	{
		$host='localhost';
		$usuario='root';
		$clave='root';
	}
	else
	{
		$host='mysql11.000webhost.com';
		$usuario='a4100383_drmsoc';
		$clave='socmica2';
	}

	$resultado_conexion=mysql_connect($host,$usuario,$clave);

	return $resultado_conexion;
}

function crear_db($nombre_db,$enlace)
{
	$query="create database $nombre_db";
	$resultado=mysql_query($query,$enlace);
	return $resultado;	
}

#######################################
###### FUNCIONES DE VALIDACIÓN ########
#######################################

function validar_clave($pass)
{
	$tam=strlen($pass);
	if($tam>6 && $tam<16)
	{
		return true;
	}
	else return false;
	
}

function comprobar_tam_nick($nick)
{
	$tam = strlen($nick);
	
	if($tam>4 && $tam<16)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function comprobar_nick($nick,$enlace)
{
	$comprobar = mysql_query("SELECT nick from usuarios",$enlace);
	
	$bandera = true;
	
	if($comprobar)
	{
		while($fila=mysql_fetch_array($comprobar))
		{
			if($fila['nick'] == $nick)
			{
				$bandera = false;
			}
		}
	}
	
	return $bandera;
}

function comprobar_user_registrado($usuario,$enlace)
{
	$comprobar_user = mysql_query("SELECT nick from usuarios WHERE nick = '$usuario'",$enlace);
	
	if($comprobar_user)
	{
		if(mysql_num_rows($comprobar_user)==1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
}

function comprobar_email($email,$enlace)
{
	if(filter_var($email,FILTER_VALIDATE_EMAIL))
	{
		$comprobar = mysql_query("SELECT email from usuarios",$enlace);
	
		if($comprobar)
		{
			if(mysql_num_rows($comprobar)==1)
			{
				while($fila=mysql_fetch_array($comprobar))
				{
					if($fila['email']==$email)
					{
						return false;
					}
					else
					{
						return true;
					}
				}
			}
			else
			{
				return true;
			}
		}
	}
	else
	{
		return false;
	}
}

function validar_email($email,$enlace)
{
	if(filter_var($email,FILTER_VALIDATE_EMAIL))
	{
		return true;
	}
	else
	{
		return false;
	}
	
}


#######################################
###### FUNCIONES DE BÚSQUEDA ##########
#######################################


function busqueda($valor)
{
	$enlace=conecta();

		echo "<div id='hijo'>";
		$consulta = mysql_query("SELECT * FROM usuarios WHERE nick LIKE '%$b%'");
		while($col = mysql_fetch_array($consulta)){
			// reunir los resultados
			$resultados.= "<h2><a href='/$col[nick]'>$col[nick]</a></h2>\r\n";
		}
		
		if($resultados){
	
			$time = number_format($time_end - $time_start,4,'.','');
			echo $resultados;
	
		}else{
	
			//echo "<p>No existen resultados</p>";
	
		}
		echo "</div>";
	}


function ver_cuenta($usuario,$enlace)
{

	$consultar_cuenta = mysql_query("SELECT * from usuarios where nick='$usuario'",$enlace);

	if($consultar_cuenta)
	{
		while($fila = mysql_fetch_array($consultar_cuenta))
		{
			echo "<table class='micuenta' align='center'>
			<tr><td><img src='$fila[img_perfil]' style=' height:100px; border:1px solid black; border-radius:5px'/></td></tr>
			<tr><td>Nick de usuario: $fila[nick]</td></tr>
			<tr><td>Nombre: $fila[nombre]</td></tr>
			<tr><td>Apellidos: $fila[apellidos]</td></tr>
			<tr><td>E-Mail: $fila[email]</td></tr>
			<tr><td>Edad: $fila[edad]</td></tr>
			<tr><td>Sexo: $fila[sexo]</td></tr>
			<tr><td></td></tr>
			<tr><td><a class='eliminar' href='eliminar_cuenta.php'>Eliminar cuenta</a></td></tr>
			</table>";
			//0,1,2,4,5,6
		}
	}

}


function ver_canciones($usuario,$enlace)
{
	$consultar_canciones = mysql_query("SELECT * from musica where nick_usuario='$usuario'",$enlace);
	
	//$dominio = "http://localhost/eclipse/Socmica/";
	$dominio = "http://socmica.hostzi.com/";
	
	
	if($consultar_canciones)
	{
		//echo "<p>Canciones de $usuario</p>";
		echo "<table class='miscanciones' align='center'>";
		
		if(mysql_num_rows($consultar_canciones)>0)
		{
			echo "<tr><td><b>Nombre</b></td><td><b>Escuchar</b></td><td><b>Acción</b></td></tr>";
			while($fila = mysql_fetch_array($consultar_canciones))
			{
				echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><tr>";
				
				$server_cancion = explode("/", $fila[enlace_fichero]);
				
				if($server_cancion[0] == "http:")
				{
					echo "<tr><td><a class='cancion' href='seguimiento.php?user=$usuario&cancion=$fila[enlace_fichero]'>$fila[nombre_fichero]</a></td>
								<td><a class='cancion' href='seguimiento.php?user=$usuario&cancion=$fila[enlace_fichero]'>Escuchar en Seguimiento</a></td>
							<td><a class='cancion' href='eliminar_canciones.php?cancion=$fila[nombre_fichero]'>Eliminar</a></td></tr>";
				}
				else
				{
					echo "<tr><td><a class='cancion' href='seguimiento.php?user=$usuario&cancion=$fila[enlace_fichero]'>$fila[nombre_fichero]</a></td>
								<td>";?><object width="200" height="15"
										data="<?php echo $dominio; ?>/ficheros/xspf_player_slim.swf?song_url=<?php echo $dominio; ?>/<?php echo $fila[enlace_fichero]?>&song_title=<?php echo $fila[nombre_fichero];?>">
										<param name="cancion" value="<?php echo $dominio; ?>/ficheros/xspf_player_slim.swf?song_url=<?php echo $dominio; ?>/<?php echo $fila[enlace_fichero]?>&song_title=<?php echo $fila[nombre_fichero];?>"/>
										</object>
										<?php echo "</td>
								<td><a class='cancion' href='eliminar_canciones.php?cancion=$fila[nombre_fichero]'>Eliminar</a></td></tr>";
				}
			}
		
			echo "</table>";
		}
		else
		{
			echo "<tr><td>¡No tienes ninguna canción en tu cuenta!<br> A que esperás para subirlas?</td></tr></table>";
		}
	}
		
}

function ver_seguidos($usuario,$enlace)
{
	$consultar_seguidos = mysql_query("SELECT usuario_seguido from seguidores where id_usuario='$usuario'",$enlace);
	$num_seguidos = 0;
	
	if($consultar_seguidos)
	{
		echo "<table class='seguidos' align='left'><tr><td>Usuarios que sigues</td></tr>";
		
		if(mysql_num_rows($consultar_seguidos)>0)
		{
			while($fila = mysql_fetch_array($consultar_seguidos))
			{
				$num_seguidos++;
				echo "<tr><td><a class='seguir' href='seguimiento.php?user=$fila[usuario_seguido]'>$fila[usuario_seguido]</a></td></tr>";
			}
		
			echo "<tr><td><hr>SIGUIENDO: $num_seguidos</td></tr></table>";
		}
				else
				{
					echo "<tr><td>¡No sigues a nadie!<br>Porque no empiezas?</td></tr></table>";
				}
		
	}
	
}


function ver_seguidores($usuario,$enlace)
{
	$consultar_seguidores = mysql_query("SELECT id_usuario from seguidores where usuario_seguido='$usuario'",$enlace);
	$num_seguidores = 0;
	if($consultar_seguidores)
	{
		echo "<table class='seguidores' align='right'><tr><td>Usuarios que te siguen</td></tr>";

		if(mysql_num_rows($consultar_seguidores)>0)
		{
			while($fila = mysql_fetch_array($consultar_seguidores))
			{
				$num_seguidores++;
				echo "<tr><td><a class='seguir' href='seguimiento.php?user=$fila[id_usuario]'>$fila[id_usuario]</a></td></tr>";
			}

			echo "<tr><td><hr>SEGUIDORES: $num_seguidores</td></tr></table>";
		}
		else
		{
			echo "<tr><td>¡No te sigue nadie!<br>Porque no empiezas tú?</td></tr></table>";
		}

	}

}

function ver_usuario($usuario,$usuario_a_ver,$enlace)
{
	$ver_info = mysql_query("SELECT * FROM usuarios WHERE nick='$usuario_a_ver'",$enlace);
	$ver_canciones = mysql_query("SELECT * FROM musica WHERE nick_usuario='$usuario_a_ver'",$enlace) or die(mysql_error());
	
	echo "<table class='muestra_cancion' align='left'>";
	
	if($fila2 = mysql_fetch_array($ver_info))
	{
		echo "<tr><td colspan='2'><img src='$fila2[img_perfil]' style='width:100px; height:100px; border:1px solid black; border-radius:5px'/></td></tr>
		<tr><td colspan='2'>$fila2[nick]</td></tr>";
		
		while($fila = mysql_fetch_array($ver_canciones))
		{
			if(mysql_num_rows($ver_canciones)>0)
			{
				echo "<tr><td><a class='seguir' href='seguimiento.php?user=$fila2[nick]&cancion=$fila[enlace_fichero]&nom_cancion=$fila[nombre_fichero]'>$fila[nombre_fichero]</a></td>
						<td><a href='seguimiento.php?user=$fila2[nick]&id_fichero=$fila[id_fichero]&votar=true&cancion=$fila[enlace_fichero]&nom_cancion=$fila[nombre_fichero]'><img class='voto' src='./imagenes/voto.png'/></a></td></tr>";
			}
		}
	}
	echo "</table>";
}


function ver_votos($id_cancion,$enlace)
{
	$mostrar_votos = mysql_query("SELECT votos FROM musica WHERE id_fichero='$id_cancion'",$enlace);
	
	if($fila = mysql_fetch_array($mostrar_votos))
	{
		echo $fila['votos'];
	}
}

function mostrar_votaciones($usuario,$enlace)
{
	$votaciones = mysql_query("SELECT * FROM musica ORDER BY votos DESC",$enlace);
	
	if($votaciones)
	{
		echo "<table class='votaciones'><tr><td>Nombre de Usuario</td><td>Nombre de la Canción</td><td>Votos Totales</td></tr>";
		while($fila = mysql_fetch_array($votaciones))
		{
			echo "<tr><td><a class='seguir' href='seguimiento.php?user=$fila[nick_usuario]'>$fila[nick_usuario]</a></td>
			<td><a class='seguir' href='seguimiento.php?user=$fila[nick_usuario]&cancion=$fila[enlace_fichero]&nom_cancion=$fila[nombre_fichero]'>$fila[nombre_fichero]</a></td>
			<td>$fila[votos]</td><tr>";
		}
		echo "</table>";
	}
	
}


#######################################
####### FUNCIONES DE MENSAJES #########
#######################################


function enviar_mensaje($emisor,$destinatario,$mensaje,$asunto,$enlace)
{
	$id_mensaje = $emisor.time();
	
	$enviar_mensaje = mysql_query("INSERT INTO mensajes VALUES('$id_mensaje','$emisor','$destinatario','$asunto','$mensaje',now())",$enlace);
	
	if($enviar_mensaje)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function generar_destinatarios($usuario,$enlace)
{
	$consultar_destinatarios = mysql_query("SELECT usuario_seguido from seguidores where id_usuario='$usuario'",$enlace);

	if($consultar_destinatarios)
	{
		while($destinatario = mysql_fetch_array($consultar_destinatarios))
		{
			echo "<option>$destinatario[usuario_seguido]</option>";
		}
	}
	else
	{
		echo "<option>Error BBDD</option>";
	}
}

function ver_mensajes_recibidos($usuario,$enlace)
{
	$ver_mensajes_r = mysql_query("SELECT * FROM mensajes WHERE nick_usuario_re='$usuario' ORDER BY fecha_hora DESC",$enlace);
	$i=0;
	if($ver_mensajes_r)
	{
		echo "<br><p class='recibidos'>MENSAJES RECIBIDOS</p>";
		echo "<table class='mensajes'><tr><td><b>Usuario</b></td><td><b>Asunto</b></td><td><b>Acción</b></td><td><b>Eliminar</b></td></tr>";
			
		while($mensaje_r = mysql_fetch_array($ver_mensajes_r))
		{
			$mensacod = base64_encode($mensaje_r['id_mensaje']);
			
			echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			
			echo "<tr>
						<td>$mensaje_r[nick_usuario_em]</td><td>$mensaje_r[asunto]</td>
						<td><a class='mensa' href='mensajes.php?user=$_SESSION[usuario]&id=$mensacod&action=leer&metodo=entrada'>Leer</a></td>
						<td><a class='mensa' href='mensajes.php?user=$_SESSION[usuario]&id=$mensacod&action=eliminar&metodo=entrada'>Eliminar</a></td>
				</tr>";
		}
	}
	echo "</table>";
	
	//echo "<br><table class='mensajes'><tr><td><a href=''>Seleccionar todos</a></td><td><a href=''>Eliminar seleccionados</a></td></tr></table>";
}

function ver_mensajes_enviados($usuario,$enlace)
{
	$ver_mensajes_e = mysql_query("SELECT * FROM mensajes WHERE nick_usuario_em='$usuario' ORDER BY fecha_hora DESC",$enlace);
	$j=0;
	
	if($ver_mensajes_e)
	{
		echo "<br><p class='enviados'>MENSAJES ENVIADOS</p>";
		echo "<table class='mensajes'><tr><td><b>Usuario</b></td><td><b>Para</b></td><td><b>Asunto</b></td><td><b>Acción</b></td><td><b>Eliminar</b></td></tr>";
		
		while($mensaje_e = mysql_fetch_array($ver_mensajes_e))
		{
			$mensacod = base64_encode($mensaje_e['id_mensaje']);
			
			echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			
			echo "<tr>
						<td>$mensaje_e[nick_usuario_em]</td><td>$mensaje_e[nick_usuario_re]</td><td>$mensaje_e[asunto]</td>
						<td><a class='mensa' href='mensajes.php?user=$_SESSION[usuario]&id=$mensacod&action=leer&metodo=salida'>Leer</a></td>
						<td><a class='mensa' href='mensajes.php?user=$_SESSION[usuario]&id=$mensacod&action=eliminar&metodo=salida'>Eliminar</a></td>
				</tr>";
		}
	}
	echo "</table>";
	
	//echo "<br><table class='mensajes'><tr><td><a href=''>Seleccionar todos</a></td><td><a href=''>Eliminar seleccionados</a></td></tr></table>";
}

function leer_mensaje($usuario,$id_mensaje,$enlace)
{
	$leer_mensaje = mysql_query("SELECT * from mensajes where id_mensaje='$id_mensaje'",$enlace);
	
	if($leer_mensaje)
	{
		if($mensaje = mysql_fetch_array($leer_mensaje))
		{
			echo "<table class='mensajes2' align='center'>
			<textarea style='resize:none;font-weight:bold' rows='10' cols='50' readonly>--------------------------------------------------\nEnviado el: $mensaje[fecha_hora] \nDe: $mensaje[nick_usuario_em]\nPara: $mensaje[nick_usuario_re]\n--------------------------------------------------\n$mensaje[mensaje]</textarea>
			</table>";
		}
	}
	if($_GET[metodo]=='entrada'){$bandeja = "Entrada";}else{$bandeja = "Salida";}
	echo "<a class='cambiar' href='mensajes.php?metodo=$_GET[metodo]'>Volver a Bandeja de $bandeja</a>";
	
}

function eliminar_mensaje($usuario,$id_mensaje,$enlace)
{
	$elimina_mensaje = mysql_query("DELETE FROM mensajes WHERE id_mensaje='$id_mensaje'",$enlace);
	
	if($elimina_mensaje)
	{
		echo "<p class='correcto'>Se ha eliminado el mensaje.</p>";	
	}
	else
	{
		echo "<p class='aviso'>Error al eliminar el mensaje.</p>";
	}
	if($_GET[metodo]=='entrada'){$bandeja = "Entrada";}else{$bandeja = "Salida";}
	echo "<a class='cambiar' href='mensajes.php?metodo=$_GET[metodo]'>Volver a Bandeja de $bandeja</a>";
}


#######################################
### FUNCIONES DE SUBIDA CANCIONES #####
#######################################


function subir_canciones($url,$usuario)
{
	//Mime type MP3: audio/mpeg, audio/x-mpeg, audio/mp3, audio/x-mp3, 
	//audio/mpeg3, audio/x-mpeg3, audio/mpg, audio/x-mpg, audio/x-mpegaudio
	//echo $url['type'];
	
	if ($url['type'] == 'audio/mpeg' || $url['type'] == 'audio/mp3' || $url['type'] == 'audio/mpg' || $url['type'] == 'audio/mpeg3')
	{
		ini_set('max_execution_time', 1000);
		ini_set('max_input_time', 1000);
		ini_set('post_max_size', "100M");
		ini_set('memory_limit', "100M");
		ini_set('upload_max_filesize', "100M");
		ini_set('max_file_size', "100M");
	
		if (is_uploaded_file ($url['tmp_name']))
		{
			$nombreDirectorio = "./ficheros_musica/";
			$idUnico = time();
			$nombreFichero = $usuario.$url['name'];
		
			move_uploaded_file ($url['tmp_name'], $nombreDirectorio . $nombreFichero);
			echo "<p class='correcto'>Archivo subido satisfactoriamente</p>";
			return true;
		}
		else
		{
			echo "<p class='error_1'>Tu archivo no se ha podido subir! Error al enlazar</p>";
			return false;
		}
	}
	else
	{
		echo "<p class='error_1'>Tipo de archivo no válido</p>";
		return false;
	}
}

#######################################
#### FUNCIONES DE SUBIDA IMAGENES #####
#######################################


function subir_imagenes($img,$usuario)
{
	//echo $img['type'];
	
	if($img['type'] == 'image/jpeg' || $img['type'] == 'image/gif' || $img['type'] == 'image/png' || $img['type'] == 'image/bmp')
	{
		$trozos = explode(".", $img['name']);
		$extension = end($trozos);
	
		if (is_uploaded_file ($img['tmp_name']))
		{
			$nombreDirectorio = "./ficheros_imgperfil/";		
			$nombreFichero = $usuario."_perfil.".$extension;
	
			move_uploaded_file ($img['tmp_name'], $nombreDirectorio . $nombreFichero);
			//echo "<p class='correcto'>Archivo subido satisfactoriamente</p>";
			return $nombreFichero;
		}
		else
		{
			echo "<p class='error_1'>Tu archivo no se ha podido subir! Error al enlazar</p>";
			return false;
		}
	}
	else
	{
		echo "<p class='error_1'>La imagen no es de un tipo válido.</p>";
		return false;
	}
}

#######################################
###### FUNCIONES DE SEGUIMIENTO #######
#######################################


function seguir_usuario($usuario,$usuario_a_seguir,$enlace)
{
	$seguir_user = mysql_query("INSERT INTO seguidores VALUES('$usuario','$usuario_a_seguir')",$enlace);
	
	if($seguir_user)
	{
		echo "<p class='correcto'>Ahora sigues a $usuario_a_seguir. Vota sus canciones!</p>";
	}
	else
	{
		echo "<p class='aviso'>Ya sigues a $usuario_a_seguir</p>";
	}
}

function comprobar_seguimiento($usuario,$usuario_seguido,$enlace)
{
	$comprobar_seg = mysql_query("SELECT * FROM seguidores WHERE id_usuario='$usuario' AND usuario_seguido='$usuario_seguido'",$enlace);
	
	if(mysql_num_rows($comprobar_seg) == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function dejar_seguir_usuario($usuario,$usuario_no_seguir,$enlace)
{
	$dejar_de_seguir = mysql_query("DELETE FROM seguidores WHERE id_usuario='$usuario' AND usuario_seguido='$usuario_no_seguir'",$enlace);

	if($dejar_de_seguir)
	{
		echo "<p class='aviso'>Has dejado de seguir a $usuario_no_seguir</p>";
	}
	else
	{
		echo "<p class='aviso'>Error al dejar de seguir a $usuario_no_seguir</p>";
	}
}


#######################################
########## FUNCIONES DE VOTO ##########
#######################################


function votar_usuario($usuario,$usuario_a_votar,$id_fichero,$enlace)
{
	$comprobar_votante = mysql_query("SELECT * FROM votaciones WHERE id_votante='$usuario' AND id_votado='$usuario_a_votar'",$enlace);
	
	$ya_votado = false;
	
	if($comprobar_votante)
	{
		while($comprueba=mysql_fetch_array($comprobar_votante))
		{
			if($comprueba['id_cancion'] == $id_fichero)
			{
				$ya_votado=true;
			}
		}
	}
	
	if($ya_votado == false)
	{
		$ver_votos = mysql_query("SELECT * FROM musica WHERE nick_usuario='$usuario_a_votar' AND id_fichero='$id_fichero'",$enlace) or die(mysql_error($enlace));
		
		if($ver_votos)
		{
			if($fila=mysql_fetch_array($ver_votos))
			{
				$votos = $fila['votos'];
			}
			$votos++;
			 //Incrementamos en 1 los votos totales.
		}
		else
		{
			echo "<p class='error_1'> No se ha podido consultar los votos.</p>";
		}
		
		$votar = mysql_query("UPDATE musica SET votos='$votos' WHERE nick_usuario='$usuario_a_votar' AND id_fichero='$id_fichero'",$enlace) or die(mysql_error($enlace));
		
		if($votar)
		{
			$ingresar_voto = mysql_query("INSERT INTO votaciones VALUES('$usuario','$usuario_a_votar','$id_fichero')",$enlace);
			echo "<p class='correcto'> Has votado esta canción con éxito!</p>";
		}
		else
		{
			echo "<p class='error_1'> Error en la votación.</p>";
		}
	}
	else
	{
		echo "<p class='aviso'> Ya votada.</p>";
	}
}

#######################################
####### FUNCIONES DE ELIMINAR #########
#######################################


function confirmar_eliminacion()
{
	echo "<script>confirmar2()</script>";
}

#######################################
####### FUNCIONES PORTAPAPELES ########
#######################################

//<input id="login" type="text" name="usuario" value="Nick de Usuario" onclick="if(this.value=='Nick de Usuario') this.value=''" onblur="if(this.value=='') this.value='Nick de Usuario'"/>
//<input id="login" type="password" name="password" value="Password" onclick="if(this.value=='Password') this.value=''" onblur="if(this.value==''){this.value='Password';this.type='text'}" onfocus="if(this.value=='Password')this.value='';this.type='password'"/>
?>