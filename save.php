<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/conDB.php");
conexionDB();
$nombre=$_SESSION['nombre'];
$sessionid=$_SESSION['userid'];
$comentario=$_POST['comentario'];
$semana=$_POST['semana'];
$equipo=$_SESSION['equipo'];
$i=1;
while ($i<=6){
	$s = "dia$i";
	if (isset ($_POST[$s])) {
	$$s = $_POST[$s]; } 
	else {
	$$s = "3"; 
	}
	$i++;
}

// while ($i<=4){
	// $s = "dia$i";
	// echo $_POST[$s];
	// $i++;
	// }
	

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css">	
	<title>Asistencia</title>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h2><a href="main.php"><img src="img/logos/logo_team_<?php echo $_SESSION['teamid']; ?>.jpg" width="75px" /></a> Asistencia</h2>
			<?php
				if (isset($_POST['envasist'])){

						// Vemos si el usuario ya ha enviado una asistencia, para insertar nueva o actualizar la existente
						$sql_yaenvio="SELECT * FROM as_asistencia WHERE usuario_nombre='$nombre' AND semana='$semana'";
						$result=mysqli_query($_SESSION['con'], $sql_yaenvio);
						$reincidente = mysqli_num_rows($result);
						switch ($reincidente) {
							case 0:
								$save_day= "INSERT INTO as_asistencia (usuario_nombre, usuario_equipo, usuario_id, asist_1, asist_2, asist_3, asist_4, asist_5, asist_6, comentarios, semana) VALUES ('".$nombre."','".$equipo."','".$sessionid."','".$dia1."', '".$dia2."', '".$dia3."', '".$dia4."','".$dia5."','".$dia6."', '".$comentario."', '".$semana."')";
								break;
							case 1:
								$save_day= "UPDATE as_asistencia SET `usuario_nombre`= '$nombre', `usuario_equipo`= '$equipo', `usuario_id`='$sessionid', `asist_1`='$dia1', `asist_2`='$dia2', `asist_3`='$dia3', `asist_4`='$dia4',`asist_5`='$dia5',`asist_6`='$dia6', `comentarios`= '$comentario', `semana`='$semana' WHERE usuario_nombre='$nombre' AND semana='$semana'";
						}
					
					if (mysqli_query($_SESSION['con'], $save_day)or die(mysqli_error($_SESSION['con']))) {
									echo "<div class=\"alert alert-success\" role=\"alert\">Añadido correctamente</div>";
								}else {
									echo "<div class=\"alert alert-warning\" role=\"alert\">Ha habido un error</div>";
								}	
				}else{
				echo 'Ha ocurrido un error, avisa a Quincho';
				}
			?>		
			
		<?php 
			if ($sessionid==73) {?>
				<p>Querido Maga, </br>
				Espero que esto te haga la desaparición de Interviú más llevadera.
				Será nuestro secreto.</br>
				<strong>Quincho</strong>
				</p>
				<script type="text/javascript">
					frase = new Array();
					frase[0] = '<img src="img/maga/1.jpg" width="100%">';
					frase[1] = '<img src="img/maga/2.jpg" width="100%">';
					frase[2] = '<img src="img/maga/3.jpg" width="100%">';
					frase[3] = '<img src="img/maga/4.jpg" width="100%">';
					frase[4] = '<img src="img/maga/5.jpg" width="100%">';
					frase[5] = '<img src="img/maga/6.jpg" width="100%">';
					frase[6] = '<img src="img/maga/7.jpg" width="100%">';
					frase[7] = '<img src="img/maga/8.jpg" width="100%">';
					frase[8] = '<img src="img/maga/9.jpg" width="100%">';
					frase[9] = '<img src="img/maga/10.jpg" width="100%">';
					frase[10] = '<img src="img/maga/11.jpg" width="100%">';
					frase[11] = '<img src="img/maga/12.jpg" width="100%">';
					frase[12] = '<img src="img/maga/13.jpg" width="100%">';

					aleatorio = Math.random() * (frase.length);
					aleatorio = Math.floor(aleatorio);
					document.write(frase[aleatorio]);
				</script>
			<?php } else {}?>
		</div>
	</div>
</div>
<!-- Llamadas a los jS, para que cargue al final y ahorrarnos tiempo -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"></script>
</body>
</html>
