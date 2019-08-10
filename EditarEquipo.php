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
	$Edit_nombre = $_POST["IDSemana"];
	$Edit_nick = $_POST["dia1"];
	$guardar_actualizacion= "UPDATE as_semana SET `nombre`='$Edit_nombre',`nick`='$Edit_nick' WHERE `id`= '$id_equipo'";
					if (mysqli_query($_SESSION['con'], $guardar_actualizacion)or die(mysqli_error($_SESSION['con']))) {
									echo "<div class=\"alert alert-success\" role=\"alert\">Has editado el equipo correctamente</div>";
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
	<div class="container">
	<form class="form" role="form">
		<div class="row">
      <!-- left column -->
      
		<div class="col-md-3 center-block">
        	<img src="img/logos/logo_team_<?php echo $equipo_actual->ID; ?>.jpg" class="avatar img-circle mx-auto d-block" alt="avatar" width="75%">
			  <!-- <input type="file" class="form-control"> -->
				<label for="nuevoescudo" class="btn btn-outline-primary mx-auto d-block">Cambiar escudo</label>
				<input id="nuevoescudo" style="visibility:hidden;" type="file">
				<span id="imgSeleccionada"></span>
        </div>
      
      <!-- edit form column -->
      <div class="col-md-9 personal-info">
        <div class="alert alert-info alert-dismissable">
          <a class="panel-close close" data-dismiss="alert">×</a> 
          <i class="fa fa-coffee"></i>
          This is an <strong>.alert</strong>. Use this to show important messages to the user.
        </div>
        
        
          <div class="form-group">
            <label class="col-lg-3 control-label">Nombre equipo:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" value="<?php echo $equipo_actual->nombre; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">URL:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" value="<?php echo $equipo_actual->nick; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-8">
		<hr>
              <input type="button" class="btn btn-primary" value="Guardar cambios">
            </div>
          </div>
        </form>
      </div>
  </div>
</div>
<hr>

<hr>
<a class="btn btn-danger" href="php/cerrar.php">
<i class="fa fa-sign-out fa-lg"></i> Cerrar sesión</a>


<?php } ?> 
</div>
</div>

<script src="inc/tether.min.js" ></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script>
	$("#nuevoescudo").change(function() {
  		filename = this.files[0].name
  		console.log(filename);
		document.getElementById("imgSeleccionada").innerHTML = "Seleccionado: "+filename;
	});
</script>

</body>
</html>