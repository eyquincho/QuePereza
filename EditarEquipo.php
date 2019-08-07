<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/conDB.php");
conexionDB();

//if (!isset($_SESSION["dni"]) || $_SESSION["rango"]==0) {
//	header("Location: login.php");
//} else {}

$tabla_equipos = "as_equipos";
$id_equipo = $_SESSION['teamid'];

function EditarEquipo () {
//Actualizar datos una vez pulsado el actualizar
if (isset ($_POST["EdicionSemana"])) {
	$Edit_IDSemana = $_POST["IDSemana"];
	$Edit_ses_1 = $_POST["dia1"];
	$Edit_ses_2 = $_POST["dia2"];
	$Edit_ses_3 = $_POST["dia3"];
	$Edit_ses_4 = $_POST["dia4"];
	$Edit_ses_5 = $_POST["dia5"];
	$Edit_ses_6 = $_POST["dia6"];
	$Edit_oponente = $_POST["oponente"];
	$Edit_ciudad = $_POST["ciudad"];
	$Edit_fechayhora = $_POST["fechayhora"];
	$Edit_oponente_b = $_POST["oponente_b"];
	$Edit_ciudad_b = $_POST["ciudad_b"];
	$Edit_fechayhora_b = $_POST["fechayhora_b"];
	$Edit_comentarios = $_POST["comentarios"];
	$Edit_grupo = $_POST["grupo"];
	$guardar_actualizacion= "UPDATE as_semana SET `ses_1`='$Edit_ses_1',`ses_2`='$Edit_ses_2',`ses_3`='$Edit_ses_3',`ses_4`='$Edit_ses_4',`ses_5`='$Edit_ses_5',`ses_6`='$Edit_ses_6',`oponente`='$Edit_oponente',`ciudad`='$Edit_ciudad',`fecha_hora`='$Edit_fechayhora',`oponente_b`='$Edit_oponente_b',`ciudad_b`='$Edit_ciudad_B',`fecha_hora_B`='$Edit_fechayhora_b',`comentarios`='$Edit_comentarios',`grupo`='$Edit_grupo' WHERE `id`= '$Edit_IDSemana'";
					if (mysqli_query($_SESSION['con'], $guardar_actualizacion)or die(mysqli_error($_SESSION['con']))) {
									echo "<div class=\"alert alert-success\" role=\"alert\">Has editado la semana correctamente</div>";
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
	<link rel="stylesheet" href="css/style.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<title>Gestor de asistencia - Editar equipo</title>
</head>
<body>
<div class="container">
<div class="row">
<a href="main.php"><h2><img src="img/logos/logo_team_<?php echo $_SESSION['teamid']; ?>.jpg" width="75px" /></a> Editar equipo</h2>
	<?php 
	// EditarEquipo();
	?>
	<hr>
	<?php
		//Definimos la variable $equipo
		$equipo=$_SESSION["equipo"];
		//Sacamos todos los datos del equipo
		$sql_equipo = mysqli_query($_SESSION['con'], "SELECT * FROM $tabla_equipos WHERE id= $id_equipo");
		while ($equipo_actual = mysqli_fetch_object($sql_equipo)) { 
	?>
	<form enctype="multipart/form-data" id="FormEditEquipo" name="editar_equipo" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	  	
				<img class="img-thumbnail" src="/img/logos/logo_team_<?php echo $id_equipo; ?>.jpg" alt="Card image cap" >
				
					<strong>Escudo</strong>
					<label for="oponente">Modifica el nombre del equipo, puedes cambiarlo todas las veces que quieras</label>
					<input type="text" class="form-control" name="oponente" value="<?php echo $semana_actual->oponente; ?>">

				<strong>Nombre del equipo</strong>
				<div class="form-group">
					<label for="oponente">Modifica el nombre del equipo, puedes cambiarlo todas las veces que quieras</label>
					<input type="text" class="form-control" name="oponente" value="<?php echo $semana_actual->oponente; ?>">
				</div>

			<div class="card">
				<strong>URL personalizada</strong>
				<div class="form-group">
					<label for="url">Por ahora no puedes cambiar la url de tu equipo, generaría el caos. Si necesitas cambiarla ponte en contacto por correo electrónico.</label>
					<input type="text" class="form-control" name="url" value="<?php echo $equipo_actual->nick; ?>">

				<input type="hidden" name="IDequipo" value="<?php echo $equipo_actual->id; ?>">

	  	<button type="submit" class="btn btn-default" name="EdicionEquipo">Actualizar</button>
	</form>

<hr>
<a class="btn btn-danger" href="php/cerrar.php">
<i class="fa fa-sign-out fa-lg"></i> Cerrar sesión</a>


<?php } ?> 
</div>

<script src="inc/tether.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
</body>
</html>