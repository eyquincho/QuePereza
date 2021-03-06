<?php
    session_start();
	header('Content-Type: text/html; charset=UTF-8'); 
	ob_start();
	include("php/conDB.php");
	conexionDB();
	//Evitamos que entre algo raro por la variable, y la guardamos en sesión
	$team= mysqli_real_escape_string($_SESSION['con'], $_GET['team']);
	$tabla_equipo = "as_equipos";
	$sql="SELECT * FROM $tabla_equipo WHERE nick='$team'";
	$result=mysqli_query($_SESSION['con'], $sql);
	$equipo = mysqli_fetch_object($result);
	// Contamos cuantas lineas salen en el resultado, si es 1, es que existe
	$existe=mysqli_num_rows($result);
	if($existe==0){
		//Si no existe, mostrar mensaje y botón de volver
		header('location:index.php?fail='. $team .'');
	}
	else {
		$_SESSION['teamnick']=$team;
		$_SESSION['teamid']=$equipo->ID;
		$_SESSION['teamname']=$equipo->nombre;
		}
	

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<?php include ("favicon.html"); ?>
	<title>Asistencia</title>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<center><!-- Logo grandote -->
			</br>
			<img src="img/logos/logo_team_<?php echo $_SESSION['teamid']; ?>.jpg" width="150px";/>
			<h2>Asistencia | <?php echo $_SESSION['teamname']; ?></h2>
			<?php
				if (isset($_GET['e'])) {
				echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error, puede que el DNI esté vacío o incorrecto, o que tu cuenta no exista. Inténtalo de nuevo.</div>";
				} else {}
			?>
				<form enctype="multipart/form-data" id="envio" name="envio_dni" class="col-lg-12" action="main.php" method="post">
					<div class="form-group">
					<input type="tel" class="form-control" maxlength = "8" id="dni" name="dni" placeholder="Introduce tu DNI (sin letra)">
					</div>
					<input type="hidden" name="equipo" value="<?php echo $_SESSION['teamid']; ?>">
					<button type="submit" class="btn btn-success">Entrar</button><a class="btn btn-outline-info" href="acceder.php?equipo=<?php echo $_SESSION['teamid']; ?>"><i class="fa fa-sign-in"></i> Solicitar acceso</a>
				</form>
			</center>
		</div>
	</div>
</div>



<!-- Llamadas a los jS, para que cargue al final y ahorrarnos tiempo -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
