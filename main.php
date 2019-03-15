<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/conDB.php");
conexionDB();

$tabla_jugador = "as_usuarios";
$tabla_semana = "as_semana";
$tabla_asistencia = "as_asistencia";


if (!isset($_SESSION["rango"])) {
	// Definimos $dni y lo purgamos, por si hay cosas malas

	$dni=$_POST['dni'];
	$dni = mysqli_real_escape_string($_SESSION['con'], $dni);
	$uteam = $_POST['equipo'];

	// Vemos si el dni está en la BBDD
	$sql="SELECT * FROM $tabla_jugador WHERE usuario_dni='$dni' AND usuario_equipo='$uteam'";
	$result=mysqli_query($_SESSION['con'], $sql);
	$jugador = mysqli_fetch_object($result);

	// Contamos cuantas lineas salen en el resultado, si es 1, es que existe
	$existe=mysqli_num_rows($result);

	if($existe==0){
		//Si no existe, mostrar mensaje y botón de volver
		header("location:login.php?e=1");
	}
	else {
		//Si existe
		//Ver su rango
		$rango = $jugador->usuario_rango;
		$nombre = $jugador->usuario_nombre;
		$user_id = $jugador->id;
		$user_grupo = $jugador->usuario_grupo;

		//Declaro variables de sesion para poder acceder a ciertas cosas desde otras pantallas (y ver si está logueado el usuario_nombre)
		$_SESSION["dni"]= $dni;
		$_SESSION["rango"]= $rango;
		$_SESSION["nombre"]= $nombre;
		$_SESSION["userid"]= $user_id;
		$_SESSION["usergrupo"]= $user_grupo;
		$_SESSION["equipo"]= $uteam;
	}
}else {}

// Funcion para listar los miembros de cada grupo de trabajo, recibe como variable el número del grupo
function lista_grupo($gr) {
	$equipo=$_SESSION["equipo"];
	$sql_jugadores_grupo="SELECT * FROM as_usuarios WHERE `usuario_equipo`='$equipo' AND `usuario_grupo`='$gr' ORDER BY `usuario_nombre`";
	$result_jugadores_grupo = mysqli_query($_SESSION['con'], $sql_jugadores_grupo);
	while ($jugadorgrupo = mysqli_fetch_object($result_jugadores_grupo)){
		echo $jugadorgrupo->usuario_nombre."</br>";
	}
}

//Seleccionamos la última semana añadida, y la asignamos a la variable semana
$sql_semana="SELECT * FROM $tabla_semana WHERE id=(SELECT MAX(id) FROM $tabla_semana WHERE `equipo` = {$_SESSION["equipo"]})";
$result_semana=mysqli_query($_SESSION['con'], $sql_semana);
$semana = mysqli_fetch_object($result_semana);

//Si el equipo nunca ha tenido una semana, hay que forzar la creación de la primera
function comprobar_existencia() {
	$save_day_0= "INSERT INTO as_semana (equipo, ses_1, ses_2, ses_3, ses_4, ses_5, ses_6, oponente, ciudad, fecha_hora, comentarios, grupo) VALUES ('".$_SESSION["equipo"]."','1','1', '1', '1','1','1', 'test', 'test', '".$fechayhora."', 'Test','1')";
	if (mysqli_query($_SESSION['con'], $save_day_0)or die(mysqli_error($_SESSION['con']))) {
		header('Location: '.$_SERVER['REQUEST_URI']);
		}else {
			echo "<div class=\"alert alert-warning\" role=\"alert\">Ha habido un error</div>";
		}	
}
//Comprobamos que dias de la semana hay evento, y lo asignamos a sus variables
//Asi podemos deshabilitar o no los botones a la hora de seleccionar

switch ($semana->ses_1) {
		case 0:
			$ses_1="hidden";
			break;
		case 1:
			$ses_1="";
	}
switch ($semana->ses_2) {
		case 0:
			$ses_2="hidden";
			break;
		case 1:
			$ses_2="";
	}
switch ($semana->ses_3) {
		case 0:
			$ses_3="hidden";
			break;
		case 1:
			$ses_3="";
	}
switch ($semana->ses_4) {
		case 0:
			$ses_4="hidden";
			break;
		case 1:
			$ses_4="";
	}
switch ($semana->ses_5) {
		case 0:
			$ses_5="hidden";
			break;
		case 1:
			$ses_5="";
	}
switch ($semana->ses_6) {
		case 0:
			$ses_6="hidden";
			break;
		case 1:
			$ses_6="";
	}
// Establecemos la existencia o no de cada día como variables de sesión
$_SESSION["ses_1"]=$ses_1;
$_SESSION["ses_2"]=$ses_2;
$_SESSION["ses_3"]=$ses_3;
$_SESSION["ses_4"]=$ses_4;
$_SESSION["ses_5"]=$ses_5;
$_SESSION["ses_6"]=$ses_6;


//Revisar todos los días la asistencia, si la hubiese, para marcar los días que ya ha seleccionado el usuario
$us_id = $_SESSION["userid"];
$sql_asistencia = mysqli_query($_SESSION['con'], "SELECT * FROM $tabla_asistencia WHERE semana=(SELECT MAX(id) FROM `as_semana` WHERE `equipo` = {$_SESSION["equipo"]}) AND usuario_id= '$us_id'");
$asist_usuario = mysqli_fetch_object($sql_asistencia);
$asist1 = $asist_usuario->asist_1;
$asist2 = $asist_usuario->asist_2;
$asist3 = $asist_usuario->asist_3;
$asist4 = $asist_usuario->asist_4;
$asist5 = $asist_usuario->asist_5;
$asist6 = $asist_usuario->asist_6;
$asist_com = $asist_usuario->comentarios;
	
//Función que comprueba si el usuario ya ha marcado su disponibilidad, lo compara con la base de datos, y si es la opción que ya estaba seleccionada, pues lo marca. Obviamente
function disponibilidad_actual ($texto,$dia,$opcion){
	if ($GLOBALS["asist$dia"]==$opcion) {
		echo $texto;
	}else {}	
};

// Funcion para poner o no como placeholder el comentario que ha puesto el jugador
function placeholder_comentarios ($com) {
	if (strlen($com)<1) {
		return "Puedes añadir un comentario si quieres... (150 caracteres max)";
	} else {
		return $com;
	}
};
//Convertimos la fecha guardada en algo decente
$oDate = new DateTime($semana->fecha_hora);
$sDate = $oDate->format("d-m-Y H:i");
$oDate_b = new DateTime($semana->fecha_hora_b);
$sDate_b = $oDate_b->format("d-m-Y H:i");

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
	<title>Gestor de asistencia</title>
	<script>
		// Script para contar los caracteres restantes en el cuadro de comentario
			var text_max = 150;
		$('#count_message').html(text_max + ' remaining');

		$('#text').keyup(function() {
		  var text_length = $('#text').val().length;
		  var text_remaining = text_max - text_length;
		  
		  $('#count_message').html(text_remaining + ' remaining');
		});
	</script>
</head>
<body>
<div class="container">

<!-- Formulario jugador -->
<div class="row">
<a href="main.php"><h2><img src="img/logos/logo_team_<?php echo $_SESSION['teamid']; ?>.jpg" width="75px" /></a> Asistencia</h2>
<div class="card card-inverse">
  <div class="card-block" style="background-color: #333; border-color: #333;">
		<h3 class="card-title">Avisos</h3>
		<p class="card-text"><?php echo $semana->comentarios; ?></p>
  </div>

<?php
	// Cabecera del grupo de usuarios. Muestra contenido según el grupo activo esa semana 
	if (($_SESSION["usergrupo"])==($semana->grupo)) { ?>
		<div class="card-block" style="background-color: #990000; border-color: #990000;">
			<h3 class="card-title">We need you</h3>
			<p class="card-text">Esta semana el grupo de trabajo al que perteneces (Grupo <?php echo $semana->grupo; ?>) se encarga de que el equipo funcione. Puedes leer los quehaceres de los grupos <a data-toggle="modal" data-target="#modal-grupos" ><span style="text-decoration: underline;">aqui</span></a>.</p>
		</div>
		<?php } else { ?>
		<div class="card-block" style="background-color: #106883; border-color: #106883;">
			<p class="card-text">Grupo de trabajo de esta semana: <strong><?php echo $semana->grupo; ?></strong>. Puedes leer los quehaceres de los grupos <a data-toggle="modal" data-target="#modal-grupos" ><span style="text-decoration: underline;">aqui</span></a>.</p>
		</div>
		<?php }; ?>

</div>
<div class="card">
  <div class="card-header">
    Asistencia semana de <strong><?php echo $_SESSION["nombre"]; ?></strong>
  </div>
  <form enctype="multipart/form-data" id="envio" name="envio_asistencia" class="col-lg-12" action="save.php" method="post">
  <?php 
	//Ponemos @ para evitar que devuelva el warning
	if (mysqli_num_rows($result_semana)==0){
		@comprobar_existencia();
	}
	else{}
  ?> 
  <div class="card-block">
	<h4 class="card-title" <?php echo $ses_1; ?>>Lunes</h4>
		<div class="btn-group-lg" data-toggle="buttons" <?php echo $ses_1; ?>>
			  <button class="btn btn-outline-success <?php disponibilidad_actual("active", 1, 2) ?>">
				<input name="dia1" id="si" autocomplete="off" value="2" type="radio" <?php disponibilidad_actual("checked", 1, 2) ?>> Si
			  </button>
			  <button class="btn btn-outline-warning <?php disponibilidad_actual("active", 1, 1) ?>">
				<input name="dia1" id="duda" autocomplete="off" value="1" type="radio" <?php disponibilidad_actual("checked", 1, 1) ?>> Duda
			  </button>
			  <button class="btn btn-outline-danger <?php disponibilidad_actual("active", 1, 0) ?>">
				<input name="dia1" id="no" autocomplete="off" value="0" type="radio" <?php disponibilidad_actual("checked", 1, 0) ?>> No
			  </button>
		</div>
    <h4 class="card-title" <?php echo $ses_2; ?>>Martes</h4>
		<div class="btn-group-lg" data-toggle="buttons" <?php echo $ses_2; ?>>
			  <button class="btn btn-outline-success <?php disponibilidad_actual("active", 2, 2) ?>">
				<input name="dia2" id="si" autocomplete="off" value="2" type="radio" <?php disponibilidad_actual("checked", 2, 2) ?>> Si
			  </button>
			  <button class="btn btn-outline-warning <?php disponibilidad_actual("active", 2, 1) ?>">
				<input name="dia2" id="duda" autocomplete="off" value="1" type="radio" <?php disponibilidad_actual("checked", 2, 1) ?>> Duda
			  </button>
			  <button class="btn btn-outline-danger <?php disponibilidad_actual("active", 2, 0) ?>">
				<input name="dia2" id="no" autocomplete="off" value="0" type="radio" <?php disponibilidad_actual("checked", 2, 0) ?>> No
			  </button>
		</div>
	<h4 class="card-title" <?php echo $ses_3; ?>>Miércoles</h4>
		<div class="btn-group-lg" data-toggle="buttons" <?php echo $ses_3; ?>>
			  <button class="btn btn-outline-success <?php disponibilidad_actual("active", 3, 2) ?>">
				<input name="dia3" id="si" autocomplete="off" value="2" type="radio" <?php disponibilidad_actual("checked", 3, 2) ?>> Si
			  </button>
			  <button class="btn btn-outline-warning <?php disponibilidad_actual("active", 3, 1) ?>">
				<input name="dia3" id="duda" autocomplete="off" value="1" type="radio" <?php disponibilidad_actual("checked", 3, 1) ?>> Duda
			  </button>
			  <button class="btn btn-outline-danger <?php disponibilidad_actual("active", 3, 0) ?>">
				<input name="dia3" id="no" autocomplete="off" value="0" type="radio" <?php disponibilidad_actual("checked", 3, 0) ?>> No
			  </button>
		</div>
	<h4 class="card-title" <?php echo $ses_4; ?>>Jueves</h4>
		<div class="btn-group-lg" data-toggle="buttons" <?php echo $ses_4; ?>>
			  <button class="btn btn-outline-success <?php disponibilidad_actual("active", 4, 2) ?>">
				<input name="dia4" id="si" autocomplete="off" value="2" type="radio" <?php disponibilidad_actual("checked", 4, 2) ?>> Si
			  </button>
			  <button class="btn btn-outline-warning <?php disponibilidad_actual("active", 4, 1) ?>">
				<input name="dia4" id="duda" autocomplete="off" value="1" type="radio" <?php disponibilidad_actual("checked", 4, 1) ?>> Duda
			  </button>
			  <button class="btn btn-outline-danger <?php disponibilidad_actual("active", 4, 0) ?>">
				<input name="dia4" id="no" autocomplete="off" value="0" type="radio" <?php disponibilidad_actual("checked", 4, 0) ?>> No
			  </button>
		</div>
	<h4 class="card-title" <?php echo $ses_5; ?>>Viernes</h4>
		<div class="btn-group-lg" data-toggle="buttons" <?php echo $ses_5; ?>>
			  <button class="btn btn-outline-success <?php disponibilidad_actual("active", 5, 2) ?>">
				<input name="dia5" id="si" autocomplete="off" value="2" type="radio" <?php disponibilidad_actual("checked", 5, 2) ?>> Si
			  </button>
			  <button class="btn btn-outline-warning <?php disponibilidad_actual("active", 5, 1) ?>">
				<input name="dia5" id="duda" autocomplete="off" value="1" type="radio" <?php disponibilidad_actual("checked", 5, 1) ?>> Duda
			  </button>
			  <button class="btn btn-outline-danger <?php disponibilidad_actual("active", 5, 0) ?>">
				<input name="dia5" id="no" autocomplete="off" value="0" type="radio" <?php disponibilidad_actual("checked", 5, 0) ?>> No
			  </button>
		</div>
	<h4 class="card-title" <?php echo $ses_6; ?>><a data-toggle="collapse" href="#infopartido" aria-expanded="false">Partido</h4></a>
	<!-- Mostrar partidos de la semana actual -->
	<div class="collapse" id="infopartido">
	<p class="card-text">
	<?php if (!empty($semana->oponente)){ ?>	
		<strong>Equipo A</strong><br>
		<small class="text-muted"><strong><?php echo $semana->oponente; ?></strong><br><?php echo $sDate; ?><br><?php echo $semana->ciudad; ?><br></small>
	<?php } else{} ?>
	<?php if (!empty($semana->oponente_b)){ ?>
		<strong>Equipo B</strong><br>
		<small class="text-muted"><strong><?php echo $semana->oponente_b; ?></strong><br><?php echo $sDate_b; ?><br><?php echo $semana->ciudad_b; ?><br></small>
	<?php } else{} ?>
	</p>
	<?php if ((empty($semana->oponente)) && (empty($semana->oponente_b))){ ?>
		No hay partidos esta semana
	<?php } else{} ?>
	</div>
	<!-- Fin mostrar partidos -->
		<div class="btn-group-lg" data-toggle="buttons" <?php echo $ses_6; ?>>
			  <button class="btn btn-outline-success <?php disponibilidad_actual("active", 6, 2) ?>" <?php echo $ses_6; ?>>
				<input name="dia6" id="si" autocomplete="off" value="2" type="radio" <?php disponibilidad_actual("checked", 6, 2) ?>> Si
			  </button>
			  <button class="btn btn-outline-warning <?php disponibilidad_actual("active", 6, 1) ?>" <?php echo $ses_6; ?>>
				<input name="dia6" id="duda" autocomplete="off" value="1" type="radio" <?php disponibilidad_actual("checked", 6, 1) ?>> Duda
			  </button>
			  <button class="btn btn-outline-danger <?php disponibilidad_actual("active", 6, 0) ?>" <?php echo $ses_6; ?>>
				<input name="dia6" id="no" autocomplete="off" value="0" type="radio" <?php disponibilidad_actual("checked", 6, 0) ?>> No
			  </button>
		</div>
	<br>
	<strong>Comentarios (150 caracteres max.)</strong>
	<input type="textarea" rows="3" class="form-control" id="comentarios" name="comentario" placeholder="<?php echo placeholder_comentarios($asist_com); ?>" value="<?php echo $asist_com; ?>" maxlength="150">
	<input type="hidden" name="semana" value="<?php echo $semana->id; ?>" /><br>
	<button type="submit" name="envasist" class="btn btn-primary">Enviar</button>
	</form>
	
  </div>
  <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#listado">Listado semanal</button>
  <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modal-grupos">Grupos de trabajo</button>
  <!-- Modal Listado -->
	<div class="modal fade" id="listado" tabindex="-1" role="dialog" aria-labelledby="CabeceraListado" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" id="CabeceraListado">Listado asistencia</h4>
		  </div>
		  <div class="modal-body">
			<div <?php echo $ses_1; ?>><strong>L: </strong> <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_1` = 2 AND `semana` = ".$semana->id." AND `usuario_equipo` = ".$_SESSION["equipo"].""));?> ( <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_1` = 1 AND `semana` = ".$semana->id));?>)<br></div>
			<div <?php echo $ses_2; ?>><strong>M: </strong> <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_2` = 2 AND `semana` = ".$semana->id." AND `usuario_equipo` = ".$_SESSION["equipo"].""));?> ( <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_2` = 1 AND `semana` = ".$semana->id));?>)<br></div>
			<div <?php echo $ses_3; ?>><strong>X: </strong> <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_3` = 2 AND `semana` = ".$semana->id." AND `usuario_equipo` = ".$_SESSION["equipo"].""));?> ( <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_3` = 1 AND `semana` = ".$semana->id));?>)<br></div>
			<div <?php echo $ses_4; ?>><strong>J: </strong> <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_4` = 2 AND `semana` = ".$semana->id." AND `usuario_equipo` = ".$_SESSION["equipo"].""));?> ( <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_4` = 1 AND `semana` = ".$semana->id));?>)<br></div>
			<div <?php echo $ses_5; ?>><strong>V: </strong> <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_5` = 2 AND `semana` = ".$semana->id." AND `usuario_equipo` = ".$_SESSION["equipo"].""));?> ( <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_5` = 1 AND `semana` = ".$semana->id));?>)<br></div>
			<div <?php echo $ses_6; ?>><strong>P: </strong> <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_6` = 2 AND `semana` = ".$semana->id." AND `usuario_equipo` = ".$_SESSION["equipo"].""));?> ( <?php echo mysqli_num_rows(mysqli_query($_SESSION['con'], "SELECT `id` FROM ".$tabla_asistencia." WHERE `asist_6` = 1 AND `semana` = ".$semana->id));?>)</div>

			<?php
					$lista_asistencia = mysqli_query($_SESSION['con'], "SELECT * FROM `as_asistencia` WHERE semana=(SELECT MAX(semana) FROM `as_asistencia` WHERE `usuario_equipo` = {$_SESSION["equipo"]}) AND `usuario_equipo` = {$_SESSION["equipo"]} ORDER BY `usuario_nombre`");
					function checkasist($dia) {
							switch ($dia) {
								case (0):
									echo "btn-danger";
									break;
								case (1):
									echo "btn-warning";
									break;
								case (2):
									echo "btn-success";
									break;
								case (3):
									echo "btn-outline-primary";
							}
						};	
					while ($seleccionada = mysqli_fetch_object($lista_asistencia)) {
					$id_jugador = $seleccionada->usuario_id; 
					$sql_nombre_jugador = mysqli_query ($_SESSION['con'], "SELECT usuario_nombre FROM `as_usuarios` WHERE id=$id_jugador ");
					$nombre_jugador = mysqli_fetch_object($sql_nombre_jugador);
			?>

					 <p><div class="btn-group" role="group" aria-label="Asistencia">
						<button type="button" style="width:100px" class="btn btn-<?php if (strlen($seleccionada->comentarios)>3){?>primary<?php } else { ?>info<?php } ?>"  <?php if (strlen($seleccionada->comentarios)>3){?> data-toggle="collapse" data-target="#collapse_<?php echo $seleccionada->id; ?>" aria-expanded="false" aria-controls="collapse_<?php echo $seleccionada->id; ?>" <?php } else{} ?>><?php echo $nombre_jugador->usuario_nombre; ?></button>
						<button type="button" class="btn <?php checkasist($seleccionada->asist_1); ?>" <?php echo $ses_1; ?>>L</button>
						<button type="button" class="btn <?php checkasist($seleccionada->asist_2); ?>" <?php echo $ses_2; ?>>M</button>
						<button type="button" class="btn <?php checkasist($seleccionada->asist_3); ?>" <?php echo $ses_3; ?>>X</button>
						<button type="button" class="btn <?php checkasist($seleccionada->asist_4); ?>" <?php echo $ses_4; ?>>J</button>
						<button type="button" class="btn <?php checkasist($seleccionada->asist_5); ?>" <?php echo $ses_5; ?>>V</button>
						<button type="button" class="btn <?php checkasist($seleccionada->asist_6); ?>" <?php echo $ses_6; ?>>P</button>
					</div></p>
					<div class="collapse" id="collapse_<?php echo $seleccionada->id; ?>">
					  <div class="card card-block">
						<?php echo $seleccionada->comentarios; ?>
					  </div>
					</div>
					
					
					
			
			<?php
					};
			?>
				
			<br>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  </div>
		</div>
	  </div>
	</div>
	
<!-- Modal grupos -->
	<div class="modal fade" id="modal-grupos" tabindex="-1" role="dialog" aria-labelledby="CabeceraGrupos" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" id="CabeceraListado">¿Qué es un grupo de trabajo?</h4>
		  </div>
		  <div class="modal-body">
			Los grupos de trabajo son un sistema de organización enfocado en tener siempre jugadores disponibles para llevar a cabo las tareas diarias del club. <br />
			Cada semana habrá un grupo encargado de:
			<ul>
				<li><strong>Entrenamientos:</strong> Llevar el material al campo que consideren oportuno los entrenadores (balones, conos, agua, escudos, sacos, etc.) y al finalizar el entrenamiento, recogerlo.</li>
				<li><strong>Partidos en casa:</strong> montar el campo [banderines (7 a cada lado: medio campo, 22, linea de marca y linea de balon muerto) y palos con sus protectores], material para calentar (conos, balones, agua, tee y 6 escudos), botiquín, bolsa de hielo picado y camisetas de juego. Una vez finalizado, recoger todo el material, dejar el campo y el vestuario LIMPIO y encargarse de lavar las camisetas de juego.</li>
				<li><strong>Partidos fuera de casa:</strong> Lo expuesto anteriormente menos montar el campo.</li>
				<li><strong>Tercer tiempo:</strong> Dado que mucha gente se está quejando de la cantidad de comida, cada grupo se encargara de recaudar 2€ a los jugadores convocados y poner algo más de comer. El presupuesto oscilará entre 30€ y 46€ (15 y 23 jugadores). Ajustándonos a esos presupuestos (el viernes lo mas tardar se sabe), cada grupo tendrá que organizarnizarse y poner lo que considere oportuno.</li>
			</ul>
			<hr />
			<h2>Miembros</h2>
			<h4>Grupo 1</h4>
			<?php lista_grupo(1); ?>
			<h4>Grupo 2</h4>
			<?php lista_grupo(2); ?>
			<h4>Grupo 3</h4>
			<?php lista_grupo(3); ?>
			<h4>Grupo 4</h4>
			<?php lista_grupo(4); ?>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		  </div>
		</div>
	  </div>
	</div>

  <?php 
	if ($_SESSION["rango"]=="1"){
		?>
		<a class="btn btn-outline-info btn-lg btn-block" href="trainer.php"><i class="fa fa-cog fa-lg"></i> Entrar en el área de entrenador</a>
		<?php
	}
	else{}
?>
  
</div>



</div>
<a class="btn btn-danger" href="php/cerrar.php">
<i class="fa fa-sign-out fa-lg"></i> Cerrar sesión</a>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
</body>
</html>