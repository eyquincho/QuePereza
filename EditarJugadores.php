<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/conDB.php");
conexionDB();

if (!isset($_SESSION["dni"]) || $_SESSION["rango"]==0) {
	header("Location: login.php");
} else {}

$tabla_jugador = "as_usuarios";
$tabla_semana = "as_semana";
$tabla_semana = "as_asistencia";

function SumarUsuario () {
//Actualizar datos una vez pulsado el actualizar
	if (isset ($_POST['NewPlayerAdded'])) {
		if (!isset ($_POST['NombreNuevoJugador'],$_POST['DNINuevoJugador'],$_POST['EquipoNuevoJugador'],$_POST['RangoNuevoJugador'],$_POST['GrupoNuevoJugador'])) {
			echo "<div class=\"alert alert-danger\" role=\"alert\">Debes completar todos los campos a la hora de añadir un nuevo jugador. Inténtalo de nuevo.</div>";
			echo $_POST['NombreNuevoJugador'].$_POST['DNINuevoJugador'].$_POST['EquipoNuevoJugador'].$_POST['RangoNuevoJugador'].$_POST['GrupoNuevoJugador'];
		} else {
			$NuevoNombre = $_POST['NombreNuevoJugador'];
			$NuevoDNI = $_POST['DNINuevoJugador'];
			$NuevoEquipo = $_POST['EquipoNuevoJugador'];
			$NuevoRango = $_POST['RangoNuevoJugador'];
			$NuevoGrupo = $_POST['GrupoNuevoJugador'];
			
			// Vemos si el usuario ya existe
			$sql_yaexiste="SELECT * FROM as_usuarios WHERE usuario_dni='$NuevoDNI'";
			$result=mysqli_query($_SESSION['con'], $sql_yaexiste);
			$teconozco = mysqli_num_rows($result);
			switch ($teconozco) {
				case 0:
					$save_newplayer= "INSERT INTO `as_usuarios`(`usuario_nombre`, `usuario_dni`, `usuario_equipo`, `usuario_grupo`, `usuario_rango`) VALUES ('$NuevoNombre','$NuevoDNI','$NuevoEquipo','$NuevoGrupo','$NuevoRango')";
					if (mysqli_query($_SESSION['con'], $save_newplayer)or die(mysqli_error($_SESSION['con']))) {
						echo "<div class=\"alert alert-success\" role=\"alert\">Añadido <strong>".$NuevoNombre."</strong> correctamente</div>";
					}else {
						echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error y no se han podido guardar los cambios.</div>";
					}
					break;
				case 1:
					echo "<div class=\"alert alert-warning\" role=\"alert\">Ya existe un usuario con ese DNI.</div>";
			}
		}
	}else {}
}

function BorrarUsuario () {
//Actualizar datos una vez pulsado el actualizar
if (isset ($_POST['DeleteUser'])) {
	$IDCadaver = $_POST['IDJugador'];
	$NombreCadaver = $_POST['NombreJugador'];
	$delete_user= "DELETE FROM `as_usuarios` WHERE `id` = '$IDCadaver'";
					if (mysqli_query($_SESSION['con'], $delete_user)or die(mysqli_error($_SESSION['con']))) {
									echo "<div class=\"alert alert-success\" role=\"alert\"><em>Au revoir</em> <strong>".$NombreCadaver."</strong></div>";
								}else {
									echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error y no se han podido guardar los cambios.</div>";
								}
}else {}
}

function EdicionDeUsuario () {
//Actualizar datos una vez pulsado el actualizar
if (isset ($_POST["EdicionUser"])) {
	$IDJugador = $_POST["IDJugador"];
	$NuevoNombre = $_POST["EditarNombre".$IDJugador];
	$NuevoDNI = $_POST["EditarDNI".$IDJugador];
	$NuevoEquipo = $_POST["EditarEquipo".$IDJugador];
	$NuevoRango = $_POST["EditarRango".$IDJugador];
	$NuevoGrupo = $_POST["EditarGrupo".$IDJugador];
	$guardar_actualizacion= "UPDATE as_usuarios SET `usuario_nombre`='$NuevoNombre',`usuario_dni`='$NuevoDNI',`usuario_equipo`='$NuevoEquipo',`usuario_grupo`='$NuevoGrupo',`usuario_rango`='$NuevoRango' WHERE `id`= '$IDJugador'";
					if (mysqli_query($_SESSION['con'], $guardar_actualizacion)or die(mysqli_error($_SESSION['con']))) {
									echo "<div class=\"alert alert-success\" role=\"alert\">Has editado a <strong>".$NuevoNombre."</strong> correctamente</div>";
								}else {
									echo "<div class=\"alert alert-danger\" role=\"alert\">Ha ocurrido un error y no se han podido guardar los cambios.</div>";
								}	
				}
else {}
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<?php include ("favicon.html"); ?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<title>Gestor de asistencia - Entrenador</title>

	
</head>
<body>
<div class="container">
<div class="row">
<a href="main.php"><h2><img src="ico/android-chrome-192x192.png" width="76px" /></a> Editar jugadores</h2>
	<?php 
	EdicionDeUsuario();
	SumarUsuario ();
	BorrarUsuario ();
	?>
	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#NewPlayer"><i class="fa fa-user-plus" aria-hidden="true"></i> Añadir jugador</button>
	<hr>
	<?php
		//Definimos la variable $equipo
		$equipo= $_SESSION["equipo"];
		//Sacamos todos los datos de los jugadores que haya en el equipo
		$sql_jugadores="SELECT * FROM $tabla_jugador WHERE usuario_equipo='$equipo' ORDER BY usuario_nombre";
		$result_jugadores=mysqli_query($_SESSION['con'], $sql_jugadores);
		while ($jugador = mysqli_fetch_object($result_jugadores)){
	?>
	
	<div class="btn-group" role="group" aria-label="Asistencia">
		<button type="button" style="width:150px" class="btn btn-secondary disabled"><?php echo $jugador->usuario_nombre; ?></button>
		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#editar<?php echo $jugador->id; ?>"><i class="fa fa-cogs" aria-hidden="true"></i></button>
		<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#borrar<?php echo $jugador->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
	</div><br>
	
	<!-- Modal Editar jugador -->
	<div class="modal fade" id="editar<?php echo $jugador->id; ?>" tabindex="-1" role="dialog" aria-labelledby="CabeceraEdit" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" id="CabeceraEdit">Editar jugador</h4>
		  </div>
		  <div class="modal-body">
			<form enctype="multipart/form-data" id="FormEditPlayer" name="edit_player" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<div class="form-group">
					<label for="EditarNombre<?php echo $jugador->id; ?>">Nombre</label>
					<input type="text" class="form-control" name="EditarNombre<?php echo $jugador->id; ?>" value="<?php echo $jugador->usuario_nombre; ?>">
				</div>
				<div class="form-group">
					<label for="EditarDNI<?php echo $jugador->id; ?>">DNI</label>
					<input type="tel" maxlength = "8" class="form-control" name="EditarDNI<?php echo $jugador->id; ?>" value="<?php echo $jugador->usuario_dni; ?>">
				</div>
					
				<label for="EditarGrupo<?php echo $jugador->id; ?>">Grupo de trabajo</label>
					<select class="form-control" name="EditarGrupo<?php echo $jugador->id; ?>">
						<option value="1" <?php if ($jugador->usuario_grupo==1) { ?> selected<?php }else {} ?>>Grupo 1</option>
						<option value="2" <?php if ($jugador->usuario_grupo==2) { ?> selected<?php }else {} ?>>Grupo 2</option>
						<option value="3" <?php if ($jugador->usuario_grupo==3) { ?> selected<?php }else {} ?>>Grupo 3</option>
						<option value="4" <?php if ($jugador->usuario_grupo==4) { ?> selected<?php }else {} ?>>Grupo 4</option>
					</select>
				<div class="form-group">
					<label for="EditarRango<?php echo $jugador->id; ?>">Rango</label>
					<select class="form-control" name="EditarRango<?php echo $jugador->id; ?>">
						<option value="0" <?php if ($jugador->usuario_rango) { ?> selected="selected"<?php }else {} ?>>Jugador</option>
						<option value="1" <?php if ($jugador->usuario_rango) { ?> selected="selected"<?php }else {} ?>>Entrenador</option>
					</select>
				</div>
				<input type="hidden" name="IDJugador" value="<?php echo $jugador->id; ?>">
				<input type="hidden" name="EditarEquipo<?php echo $jugador->id; ?>" value="<?php echo $_SESSION["equipo"]; ?>">
			  <button type="submit" class="btn btn-default" name="EdicionUser">Actualizar datos</button>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  </div>
		</div>
	  </div>
	</div>	
	
	<!-- Modal Eliminar jugador -->
	<div class="modal fade" id="borrar<?php echo $jugador->id; ?>" tabindex="-1" role="dialog" aria-labelledby="CabeceraEliminar" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" id="CabeceraEliminar">Borrar jugador</h4>
		  </div>
		  <div class="modal-body">
			<p>Estás a punto de borrar al jugador <strong><?php echo $jugador->usuario_nombre; ?></strong>, esta acción no se puede deshacer (aunque siempre puedes añadirle de nuevo...)
			<form enctype="multipart/form-data" id="FormEditPlayer" name="edit_player" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="IDJugador" value="<?php echo $jugador->id; ?>">
				<input type="hidden" name="NombreJugador" value="<?php echo $jugador->usuario_nombre; ?>">
			  <button type="submit" class="btn btn-danger" name="DeleteUser">Eliminar Jugador</button>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  </div>
		</div>
	  </div>
	</div>	
	
	
	
	
	<?php } ?>
	
	<!-- Modal Añadir jugador -->
	<div class="modal fade" id="NewPlayer" tabindex="-1" role="dialog" aria-labelledby="CabeceraNewPlayer" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" id="CabeceraNewPlayer">Añadir nuevo jugador</h4>
		  </div>
		  <div class="modal-body">
			<form enctype="multipart/form-data" id="FormEditPlayer" name="edit_player" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<div class="form-group">
					<label for="NombreNuevoJugador">Nombre</label>
					<input type="text" class="form-control" name="NombreNuevoJugador">
				</div>
				<div class="form-group">
					<label for="DNINuevoJugador">DNI</label>
					<input type="tel" maxlength = "8" class="form-control" name="DNINuevoJugador">
				</div>
					<input type="hidden" name="EquipoNuevoJugador" value="<?php echo $_SESSION["equipo"]; ?>">
				<label for="GrupoNuevoJugador">Grupo de trabajo</label>
				<select class="form-control" name="GrupoNuevoJugador" id="GrupoNuevoJugador">
					<option value="1">Grupo 1</option>
					<option value="2">Grupo 2</option>
					<option value="3">Grupo 3</option>
					<option value="4">Grupo 4</option>
				</select>
				<div class="form-group">
					<label for="RangoNuevoJugador">Rango</label>
					<select class="form-control" name="RangoNuevoJugador">
						<option value="0" <?php if ($jugador->usuario_rango) { ?> selected="selected"<?php }else {} ?>>Jugador</option>
						<option value="1" <?php if ($jugador->usuario_rango) { ?> selected="selected"<?php }else {} ?>>Entrenador</option>
					</select>
				</div>
			  <button type="submit" class="btn btn-default" name="NewPlayerAdded">Añadir nuevo jugador</button>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  </div>
		</div>
	  </div>
	</div>	
  
  
</div>
<hr>
<a class="btn btn-danger" href="php/cerrar.php">
<i class="fa fa-sign-out fa-lg"></i> Cerrar sesión</a>



</div>
<script src="inc/tether.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
</body>
</html>