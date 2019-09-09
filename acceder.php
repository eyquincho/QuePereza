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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<title>Gestor de asistencia - Entrenador</title>

	
</head>
<body>
<?php RegistrarSolicitud(); ?>
<div class="container">
<div class="row">
<a href="login.php?team=<?php echo $_SESSION['teamnick']; ?>"><h2><img src="img/logos/logo_team_<?php echo $_SESSION['teamid']; ?>.jpg" width="75px" /></a> Solicitar acceso a <?php echo $_SESSION['teamname']; ?></h2>
</div>
<div class="row  justify-content-center">
	<form enctype="multipart/form-data" id="FormSolicitarAccesi" name="SolicitarAcceso" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div class="form-group">
			<label for="Nombre">Nombre</label>
			<input type="text" class="form-control" name="Nombre">
		</div>
		<div class="form-group">
			<label for="DNI">DNI</label>
			<input type="tel" maxlength = "8" class="form-control" name="DNI">
		</div>
		<input type="hidden" name="equipo" value="<?php echo $_SESSION['teamid']; ?>">
	  <button type="submit" class="btn btn-danger btn-lg btn-block" name="EnviarSolicitud">Enviar solicitud</button>
	</form>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
