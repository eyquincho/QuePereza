<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/conDB.php");
conexionDB();

if (!isset($_SESSION["dni"]) || $_SESSION["rango"]==0) {
	header("Location: main.php?er_per=1");
} else {}

$tabla_jugador = "as_usuarios";
$tabla_semana = "as_semana";
$tabla_asistencia = "as_asistencia";

function EditarSemana () {
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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<title>Gestor de asistencia - Entrenador</title>
</head>
<body>
<div class="container">
<div class="row">
<a href="main.php"><h2><img src="img/logos/logo_team_<?php echo $_SESSION['teamid']; ?>.jpg" width="75px" /></a> Editar semana</h2>
	<?php 
	EditarSemana();
	?>
	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#NewWeek"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Crear semana nueva</button>
	<hr>
	<?php
		//Definimos la variable $equipo
		$equipo=$_SESSION["equipo"];
		//Sacamos todos los datos de los jugadores que haya en el equipo
		$sql_semana = mysqli_query($_SESSION['con'], "SELECT * FROM $tabla_semana WHERE id=(SELECT MAX(id) FROM `as_semana` WHERE `equipo` = {$_SESSION["equipo"]})");
		while ($semana_actual = mysqli_fetch_object($sql_semana)) { 
		?>
	<h2>Editar la semana actual</h2>
	<form enctype="multipart/form-data" id="FormEditSemana" name="editar_semana" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	  <div class="form-group">
		<label for="ActivosSemana">Días activos </label><br>
			<div class="btn-group" data-toggle="buttons">
			  <button class="btn btn-outline-info <?php if ($semana_actual->ses_1==1) { ?> active<?php }else {} ?>">
				<input name="dia1" type="checkbox" value="1" <?php if ($semana_actual->ses_1==1) { ?> checked<?php }else {} ?>> Lunes
			  </button>
			  <button class="btn btn-outline-info <?php if ($semana_actual->ses_2==1) { ?> active<?php }else {} ?>">
				<input name="dia2" type="checkbox" value="1" <?php if ($semana_actual->ses_2==1) { ?> checked<?php }else {} ?>> Martes
			  </button>
			  <button class="btn btn-outline-info <?php if ($semana_actual->ses_3==1) { ?> active<?php }else {} ?>">
				<input name="dia3" type="checkbox" value="1" <?php if ($semana_actual->ses_3==1) { ?> checked<?php }else {} ?>> Miércoles
			  </button>
			  <button class="btn btn-outline-info <?php if ($semana_actual->ses_4==1) { ?> active<?php }else {} ?>">
				<input name="dia4" type="checkbox" value="1" <?php if ($semana_actual->ses_4==1) { ?> checked<?php }else {} ?>> Jueves
			  </button>
			  <button class="btn btn-outline-info <?php if ($semana_actual->ses_5==1) { ?> active<?php }else {} ?>">
				<input name="dia5" type="checkbox" value="1" <?php if ($semana_actual->ses_5==1) { ?> checked<?php }else {} ?>> Viernes
			  </button>
			  <button class="btn btn-outline-info <?php if ($semana_actual->ses_6==1) { ?> active<?php }else {} ?>">
				<input name="dia6" type="checkbox" value="1" <?php if ($semana_actual->ses_6==1) { ?> checked<?php }else {} ?>> Partido
			  </button>
			</div>
			<div class="card card-block">
				<label for="grupo">Grupo de trabajo</label>
				<select class="form-control" name="grupo" id="grupo">
					<option value="1" <?php if ($semana_actual->grupo==1) { ?> selected<?php }else {} ?>>Grupo 1</option>
					<option value="2" <?php if ($semana_actual->grupo==2) { ?> selected<?php }else {} ?>>Grupo 2</option>
					<option value="3" <?php if ($semana_actual->grupo==3) { ?> selected<?php }else {} ?>>Grupo 3</option>
					<option value="4" <?php if ($semana_actual->grupo==4) { ?> selected<?php }else {} ?>>Grupo 4</option>
				</select> 
			</div>
			  <div class="card card-block">
				<strong>Equipo A</strong>
				<div class="form-group">
					<label for="oponente">Equipo rival</label>
					<input type="text" class="form-control" name="oponente" value="<?php echo $semana_actual->oponente; ?>">
				</div>
				<div class="form-group">
					<label for="ciudad">Ciudad</label>
					<input type="text" class="form-control" name="ciudad" value="<?php echo $semana_actual->ciudad; ?>">
				</div>
				<div class="form-group">
					<label for="fechayhora">Fecha</label>
					<input type="datetime-local" class="form-control" name="fechayhora" value="<?php echo date("Y-m-d\TH:i:s", strtotime($semana_actual->fecha_hora)); ?>">
				</div>
				<strong>Equipo B</strong>
				<div class="form-group">
					<label for="oponente_b">Equipo rival</label>
					<input type="text" class="form-control" name="oponente_b" value="<?php echo $semana_actual->oponente_b; ?>">
				</div>
				<div class="form-group">
					<label for="ciudad_b">Ciudad</label>
					<input type="text" class="form-control" name="ciudad_b" value="<?php echo $semana_actual->ciudad_b; ?>">
				</div>
				<div class="form-group">
					<label for="fechayhora_b">Fecha</label>
					<input type="datetime-local" class="form-control" name="fechayhora_b" value="<?php echo date("Y-m-d\TH:i:s", strtotime($semana_actual->fecha_hora_b)); ?>">
				</div>
			  </div>
				<div class="form-group">
					<label for="comentarios">Comentarios</label>
					<input type="textarea" rows="4" class="form-control areacoments" name="comentarios" value="<?php echo $semana_actual->comentarios; ?>">
				</div>
				<input type="hidden" name="IDSemana" value="<?php echo $semana_actual->id; ?>">
	  </div>
	  <button type="submit" class="btn btn-default" name="EdicionSemana">Actualizar</button>
	</form>
	
	

	
	
	
<!-- Modal Nueva semana -->
	<div class="modal fade" id="NewWeek" tabindex="-1" role="dialog" aria-labelledby="CabeceraNewWeek" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" id="CabeceraNewWeek">Nueva semana</h4>
		  </div>
		  <div class="modal-body">
			<form enctype="multipart/form-data" id="FormSemana" name="envio_semana" action="saveweek.php" method="post">
			  <div class="form-group">
				<label for="ActivosSemana">Días activos </label><br>
					<div class="btn-group" data-toggle="buttons">
					  <label class="btn btn-outline-info" data-active-class="info" >
						<input name="dia1" type="checkbox" value="1"> Lunes
					  </label>
					  <label class="btn btn-outline-info">
						<input name="dia2" type="checkbox" value="1"> Martes
					  </label>
					  <label class="btn btn-outline-info">
						<input name="dia3" type="checkbox" value="1"> Miércoles
					  </label>
					  <label class="btn btn-outline-info">
						<input name="dia4" type="checkbox" value="1"> Jueves
					  </label>
					  <label class="btn btn-outline-info">
						<input name="dia5" type="checkbox" value="1"> Viernes
					  </label>
					  <label class="btn btn-outline-info"  data-toggle="collapse" data-target="#datosPartido" aria-expanded="false" aria-controls="datosPartido">
						<input name="dia6" type="checkbox" value="1"> Partido
					  </label>
					</div>
					<div class="collapse" id="datosPartido">
					  <div class="card card-block">
						<strong>Equipo A</strong>
						<div class="form-group">
							<label for="oponente">Equipo rival</label>
							<input type="text" class="form-control" name="rival" placeholder="Oponente">
						</div>
						<div class="form-group">
							<label for="ciudad">Ciudad</label>
							<input type="text" class="form-control" name="ciudad" placeholder="Ciudad">
						</div>
						<div class="form-group">
							<label for="fechayhora">Fecha</label>
							<input type="datetime-local" class="form-control" name="fechayhora" placeholder="Fecha y hora">
						</div>
						<strong>Equipo B</strong>
						<div class="form-group">
							<label for="oponente_b">Equipo rival</label>
							<input type="text" class="form-control" name="rival_b" placeholder="Oponente">
						</div>
						<div class="form-group">
							<label for="ciudad_b">Ciudad</label>
							<input type="text" class="form-control" name="ciudad_b" placeholder="Ciudad">
						</div>
						<div class="form-group">
							<label for="fechayhora_b">Fecha</label>
							<input type="datetime-local" class="form-control" name="fechayhora_b" placeholder="Fecha y hora">
						</div>
					  </div>
					</div>
					<div class="card card-block">
						<label for="grupo">Grupo de trabajo</label>
						<select class="form-control" name="grupo" id="grupo">
							<option value="1" <?php if ($semana_actual->grupo==1) { ?> selected<?php }else {} ?>>Grupo 1</option>
							<option value="2" <?php if ($semana_actual->grupo==2) { ?> selected<?php }else {} ?>>Grupo 2</option>
							<option value="3" <?php if ($semana_actual->grupo==3) { ?> selected<?php }else {} ?>>Grupo 3</option>
							<option value="4" <?php if ($semana_actual->grupo==4) { ?> selected<?php }else {} ?>>Grupo 4</option>
						</select> 
					</div>
						<div class="form-group">
							<label for="oponente">Comentarios</label>
							<input type="text" class="form-control" name="comentarios" placeholder="Comentarios">
						</div>
			  </div>
			  <button type="submit" class="btn btn-default" name="enviaNewWeek">Crear semana nueva</button>
			</form>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  </div>
		</div>
	  </div>
	</div>

 	<?php } ?> 
  
</div>
<hr>
<a class="btn btn-danger" href="php/cerrar.php">
<i class="fa fa-sign-out fa-lg"></i> Cerrar sesión</a>



</div>

<script src="inc/tether.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
