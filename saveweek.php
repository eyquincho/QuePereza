<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/conDB.php");
conexionDB();
$ses_1=$_POST['dia1'];
$ses_2=$_POST['dia2'];
$ses_3=$_POST['dia3'];
$ses_4=$_POST['dia4'];
$oponente= mysqli_real_escape_string($_SESSION['con'], $_POST['rival']);
$ciudad= mysqli_real_escape_string($_SESSION['con'], $_POST['ciudad']);
$fechayhora= $_POST['fechayhora'];
$oponente_b= mysqli_real_escape_string($_SESSION['con'], $_POST['rival_b']);
$ciudad_b= mysqli_real_escape_string($_SESSION['con'], $_POST['ciudad_b']);
$fechayhora_b= $_POST['fechayhora_b'];
$comentarios= mysqli_real_escape_string($_SESSION['con'], $_POST['comentarios']);
$grupo= mysqli_real_escape_string($_SESSION['con'], $_POST['grupo']);
$equipo= $_SESSION["equipo"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
	<!-- favicons para todos -->
	<?php include ("favicon.html"); ?>
	<link rel="icon" href="favicon.ico">
	<link href="ico/apple-touch-icon.png" rel="apple-touch-icon" />
	<link href="ico/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
	<link href="ico/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
	<link href="ico/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
	<link href="ico/apple-touch-icon-180x180.png" rel="apple-touch-icon" sizes="180x180" />
	<link href="ico/icon-hires.png" rel="icon" sizes="192x192" />
	<link href="ico/icon-normal.png" rel="icon" sizes="128x128" />
	<title>Asistencia</title>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<!-- Logo grandote -->
			<h2><a href="main.php"><img src="ico/android-chrome-192x192.png" width="76px" /></a> Asistencia</h2>
			<?php
				if (isset($_POST['enviaNewWeek'])){
					$save_day= "INSERT INTO as_semana (equipo, ses_1, ses_2, ses_3, ses_4, oponente, ciudad, fecha_hora, oponente_b, ciudad_b, fecha_hora_B, comentarios, grupo) VALUES ('".$equipo."','".$ses_1."','".$ses_2."', '".$ses_3."', '".$ses_4."', '".$oponente."', '".$ciudad."', '".$fechayhora."', '".$oponente_b."', '".$ciudad_b."', '".$fechayhora_b."','".$comentarios."','".$grupo."')";
					if (mysqli_query($_SESSION['con'], $save_day)or die(mysqli_error($_SESSION['con']))) {
									echo "<div class=\"alert alert-success\" role=\"alert\">Nueva semana añadida correctamente</div>";
								}else {
									echo "<div class=\"alert alert-warning\" role=\"alert\">Ha habido un error</div>";
								}	
				}else{
				echo 'Ha ocurrido un error, vuelve a intentarlo, o avisa a Quincho';
				}
			?>			
		</div>
	</div>
</div>
<!-- Llamadas a los jS, para que cargue al final y ahorrarnos tiempo -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
</body>
</html>