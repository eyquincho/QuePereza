<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/conDB.php");
conexionDB();

function RevSolicitudes (){
if (isset($_POST['aceptar_usuario'])) {
    //Añadir jugador
	$id_solicitud= $_POST['IDSolicitud'];
	$sql_solicitud= "SELECT * FROM `as_solicitudes` WHERE `id`='$id_solicitud'";
	$datos_solicitud = mysqli_query($_SESSION['con'], $sql_solicitud);
	$sol = mysqli_fetch_object($datos_solicitud);
	$NuevoNombre = $sol->solicitud_nombre;
	$NuevoDNI = $sol->solicitud_dni;
	$NuevoEquipo = $sol->solicitud_equipo;
	$NuevoRango = $sol->solicitud_rango;
	$sql_usuario= "INSERT INTO `as_usuarios`(`usuario_nombre`, `usuario_dni`, `usuario_equipo`, `usuario_rango`) VALUES ('$NuevoNombre','$NuevoDNI','$NuevoEquipo','$NuevoRango')";
	if (mysqli_query($_SESSION['con'], $sql_usuario)or die(mysqli_error($_SESSION['con']))) {
				$delete_user= "DELETE FROM `as_solicitudes` WHERE `id` = '$id_solicitud'";
				if (mysqli_query($_SESSION['con'], $delete_user)or die(mysqli_error($_SESSION['con']))) {
					echo "<div class=\"alert alert-success\" role=\"alert\">Añadido <strong>".$NuevoNombre."</strong></div>";
				}else {
					echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido eliminando la solicitud del listado.</div>";
				}
		}else {
		echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error y no se han podido guardar los cambios.</div>";
	}
	
	
} else if (isset($_POST['descartar_usuario'])) {
    //Descartar Jugador
	$IDCadaver = $_POST['IDSolicitud'];
	$delete_user= "DELETE FROM `as_solicitudes` WHERE `id` = '$IDCadaver'";
					if (mysqli_query($_SESSION['con'], $delete_user)or die(mysqli_error($_SESSION['con']))) {
									echo "<div class=\"alert alert-success\" role=\"alert\">Solicitud eliminada.</div>";
								}else {
									echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error y no se han podido guardar los cambios.</div>";
								}	
} else {}
}
$tabla_jugador = "as_usuarios";
$tabla_semana = "as_semana";
$tabla_asistencia = "as_asistencia";

//Seleccionamos la última semana añadida, y la asignamos a la variable semana
$sql_semana="SELECT * FROM $tabla_semana WHERE id=(SELECT MAX(id) FROM $tabla_semana)";
$result_semana=mysqli_query($_SESSION['con'], $sql_semana);
$semana = mysqli_fetch_object($result_semana);

$lista_solicitudes = mysqli_query($_SESSION['con'], "SELECT * FROM `as_solicitudes`");
$num_solicitudes = mysqli_num_rows($lista_solicitudes);

//Comprobamos que dias de la semana hay evento, y lo asignamos a sus variables
//Asi podemos deshabilitar o no los botones a la hora de seleccionar
 
	if ($_SESSION["rango"]!="1") { 
	echo "No puedes acceder a este contenido"; } 
	else {
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
	<script>
		$('.collapse').collapse()
	</script>
	<style>
		.btn.active[data-active-class="success"] {
		color: #fff;
		background-color: #449d44;
		border-color: #398439;
		}
	</style>
	
</head>
<body>
<div class="container">
<div class="row">
<a href="main.php"><h2><img src="img/logos/logo_team_<?php echo $_SESSION['teamid']; ?>.jpg" width="75px" /></a> Entrenador</h2>
	<!-- Botones -->
	<?php RevSolicitudes(); ?>
	<a href="EditarSemana.php" class="btn btn-success btn-block" role="button" aria-pressed="true">Editar semana</a>
	<a href="EditarJugadores.php" class="btn btn-warning btn-block" role="button" aria-pressed="true">Editar jugadores</a>
	<button type="button" class="btn btn-info-outline btn-block" data-toggle="collapse" data-target="#solicitudes" aria-expanded="false" aria-controls="collapse_solicitudes">
	  Solicitudes de acceso pendientes: <strong><?php echo $num_solicitudes; ?></strong>
	</button>
	<div class="collapse" id="solicitudes">
		<div class="card card-inverse" style="background-color: #333; border-color: #333;">
			<div class="card-block">
				<p class="card-text">Debajo se listan, si las hubiese, las solicitudes de acceso a la plataforma hechas por jugadores. Puedes revisar su información tocando sobre su nombre. En el verde aceptas, en el rojo rechazas. 
				No va a salir ningún cartelito preguntando si estás seguro. I WAS BORN READY!</p>
			</div>
		</div>
		<div class="card card-block">
			<?php
						while ($solicitud = mysqli_fetch_object($lista_solicitudes)) {
									
				?>
				<button type="button" style="width:130px" class="btn btn-primary" data-toggle="modal" data-target="#Solicitud_<?php echo $solicitud->id; ?>"><?php echo $solicitud->solicitud_nombre; ?></button>
				<div class="modal fade" id="Solicitud_<?php echo $solicitud->id; ?>">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						<h4 class="modal-title">Solicitud <?php echo $solicitud->solicitud_nombre; ?></h4>
					  </div>
					  <div class="modal-body">
						<p>Nombre: <?php echo $solicitud->solicitud_nombre; ?></p>
						<p>DNI: <?php echo $solicitud->solicitud_dni; ?></p>
						<p>Equipo: <?php echo $solicitud->solicitud_equipo; ?></p>
					  </div>
					  
					  <div class="modal-footer">
						<form enctype="multipart/form-data" id="FormSemana" name="envio_semana" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
							<input type="hidden" name="IDSolicitud" value="<?php echo $solicitud->id; ?>">
							<button type="submit" name="aceptar_usuario" value="aceptar" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i></button>
							<button type="submit" name="descartar_usuario" value="rechazar" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
						</form>
					  </div>
					</div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<?php
						};
				?>
		</div>
	</div>
	<div class="col-lg-6">
	<strong>Resumen asistencia semana actual: </strong>
	<table class="table">
		<thead>
			<tr>
				<th>#</th>
				<th>SI</th>
				<th>DUDA</th>
				<th>NO</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			if (empty($_SESSION['ses_1'])){
		?>
			<tr>
				<th scope="row">Lunes</th>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_1` = 2 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_1` = 1 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_1` = 0 AND `semana` = ".$semana->id));
					?>
				</td>
			</tr>
		<?php
			}else{}
			if (empty($_SESSION['ses_2'])){
		?>
			<tr>
				<th scope="row">Martes</th>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_2` = 2 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_2` = 1 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_2` = 0 AND `semana` = ".$semana->id));
					?>
				</td>
			</tr>
		<?php
			}else{}
			if (empty($_SESSION['ses_3'])){
		?>
			<tr>
				<th scope="row">Miércoles</th>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_3` = 2 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_3` = 1 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_3` = 0 AND `semana` = ".$semana->id));
					?>
				</td>
			</tr>
		<?php
			}else{}
			if (empty($_SESSION['ses_4'])){
		?>
			<tr>
				<th scope="row">Jueves</th>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_4` = 2 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_4` = 1 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_4` = 0 AND `semana` = ".$semana->id));
					?>
				</td>
			</tr>
		<?php
			}else{}
			if (empty($_SESSION['ses_5'])){
		?>
			<tr>
				<th scope="row">Viernes</th>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_5` = 2 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_5` = 1 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_5` = 0 AND `semana` = ".$semana->id));
					?>
				</td>
			</tr>
		<?php
			}else{}
			if (empty($_SESSION['ses_6'])){
		?>
			<tr>
				<th scope="row">Partido</th>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_6` = 2 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_6` = 1 AND `semana` = ".$semana->id));
					?>
				</td>
				<td>
					<?php 
						echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_6` = 0 AND `semana` = ".$semana->id));
					?>
				</td>
			</tr>
			<?php }else{} ?>
		</tbody>
</table>
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
<?php
}
?>