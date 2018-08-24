<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/conDB.php");
conexionDB();

function RegistrarSolicitud () {
	if (isset ($_POST['EnviarSolicitud'])) {
			$SolicitudNombre = mysqli_real_escape_string($_SESSION['con'], $_POST['Nombre']);
			$SolicitudDNI = mysqli_real_escape_string($_SESSION['con'], $_POST['DNI']);
			$SolicitudEquipo = $_POST['equipo'];

			// Vemos si el usuario ya existe
			$sql_yaexiste="SELECT * FROM as_usuarios WHERE usuario_dni='$SolicitudDNI' AND `usuario_equipo`='$SolicitudEquipo'";
			$result=mysqli_query($_SESSION['con'], $sql_yaexiste);
			$teconozco = mysqli_num_rows($result);
			switch ($teconozco) {
				case 0:
					$guardar_solicitud= "INSERT INTO `as_solicitudes` (solicitud_nombre, solicitud_dni, solicitud_equipo, solicitud_rango) VALUES ('$SolicitudNombre','$SolicitudDNI','$SolicitudEquipo','0')";
					if (mysqli_query($_SESSION['con'], $guardar_solicitud)or die(mysqli_error($_SESSION['con']))) {
						echo "<div class=\"alert alert-success\" role=\"alert\">Hemos añadido tu solicitud <strong>".$SolicitudNombre."</strong>. Puedes avisar a tu entrenador por otro medio para que te active la cuenta.</div>";
					}else {
						echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error y no se han podido guardar los datos.</div>";
					}
					break;
				case 1:
					echo "<div class=\"alert alert-warning\" role=\"alert\">Ya existe un usuario con ese DNI.</div>";
			}
	}else {}
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
<?php RegistrarSolicitud(); ?>
<div class="container">
<div class="row">
<a href="login.php"><h2><img src="ico/android-chrome-192x192.png" width="76px" /></a> Solicitar acceso</h2>
	<form enctype="multipart/form-data" id="FormSolicitarAccesi" name="SolicitarAcceso" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div class="form-group">
			<label for="Nombre">Nombre</label>
			<input type="text" class="form-control" name="Nombre">
		</div>
		<div class="form-group">
			<label for="DNI">DNI</label>
			<input type="tel" maxlength = "8" class="form-control" name="DNI">
		</div>
		<div class="form-group">
			<label for="Equipo">Equipo</label>
				<select class="form-control" name="equipo">
					<option value="1">Masculino</option>
					<option value="2">Femenino</option>
				</select>
		</div>
	  <button type="submit" class="btn btn-default" name="EnviarSolicitud">Enviar solicitud</button>
	</form>
</div>
</div>
<script src="inc/tether.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
</body>
</html>