<?php
session_start();
header('Content-Type: text/html; charset=UTF-8'); 
ob_start();
include("php/class.upload.php");
include("php/conDB.php");
conexionDB();

if (!isset($_SESSION["dni"]) || $_SESSION["rango"]==0) {
	header("Location: main.php?er_per=1");
} else {}

$tabla_equipos = "as_equipos";
$id_equipo = $_SESSION['teamid'];

function EditarEquipo () {
//Actualizar datos una vez pulsado el actualizar
if (isset ($_POST["EdicionEquipo"])) {
	$Edit_equipo = $_POST["IDEquipo"];
	$Edit_nombre = $_POST["nombre"];
	$Edit_nick = $_POST["nick"];
		//Implementar class.upload.php
		//https://github.com/verot/class.upload.php
		//La variable que viene es $FotoNuevoEscudo
		$handle = new Upload($_FILES['FotoNuevoEscudo']);
		if ($handle->uploaded) {
		  $handle->file_new_name_body   = $Edit_equipo;
		  $handle->file_name_body_pre	= 'logo_team_';
		  $handle->file_safe_name = true;
		  $handle->image_convert = 'jpg';
		  $handle->image_resize         = true;
		  $handle->image_x              = 300;
		  $handle->image_y              = 300;
		  $handle->image_ratio        	= true;
		  $handle->file_overwrite = true;
		  $handle->process('img/logos/');
		  if ($handle->processed) {
			$NuevoFoto = $handle->file_dst_pathname;
			$handle->clean();
		  } else {
			echo 'error : ' . $handle->error;
		  }
		}else{}


	$guardar_actualizacion= "UPDATE as_equipos SET `nombre`='$Edit_nombre',`nick`='$Edit_nick' WHERE `ID`= '$Edit_equipo'";
		if (mysqli_query($_SESSION['con'], $guardar_actualizacion)or die(mysqli_error($_SESSION['con']))) {
			?><div class="alert alert-success alert-dismissable">
				<a class="panel-close close" data-dismiss="alert">×</a> 
				<i class="fa fa-check"></i>
				Cambios realizados correctamente.
				</div><?php
					}else {
					?><div class="alert alert-danger alert-dismissable">
					<a class="panel-close close" data-dismiss="alert">×</a> 
					<i class="fa fa-times"></i>
					Ha ocurrido un error al hacer los cambios. Inténtalo de nuevo
					</div><?php
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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<title>Gestor de asistencia - Editar equipo</title>
</head>
<body>
<div class="container">
<div class="row">
<a href="main.php"><h2><img src="img/logos/logo_team_<?php echo $_SESSION['teamid']; ?>.jpg" width="75px" /></a> Editar equipo</h2>
	<?php 
		EditarEquipo();
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
	<form enctype="multipart/form-data" id="FormEditEquipo" name="editar_equipo" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<div class="row">
		<!-- left column -->
		<div class="col-md-3 center-block">
			<img src="img/logos/logo_team_<?php echo $equipo_actual->ID; ?>.jpg" class="avatar img-circle mx-auto d-block" alt="avatar" width="75%">
			<!-- <input type="file" class="form-control"> -->
			<label for="FotoNuevoEscudo" class="btn btn-outline-primary mx-auto d-block">Cambiar escudo</label>
			<input id="FotoNuevoEscudo" name="FotoNuevoEscudo" style="visibility:hidden;" type="file" accept="image/*" >
			<span id="imgSeleccionada"></span>
		</div>
		
		<!-- edit form column -->
		<div class="col-md-9">
			
			<div class="form-group">
				<label class="col-lg-3 control-label">Nombre equipo:</label>
				<div class="col-lg-8">
				<input class="form-control" name="nombre" type="text" value="<?php echo $equipo_actual->nombre; ?>">
				</div>
			</div>
			<label class="col-lg-3 control-label">URL:</label>
			<div class="input-group col-lg-8">
				<input type="text" class="form-control" name="nick" value="<?php echo $equipo_actual->nick; ?>">
				<div class="input-group-append">
					<span class="input-group-text">.quepereza.es</span>
				</div>
				<small id="urlHelp" class="form-text text-muted">Ten en cuenta que si cambias este dato, todos los jugadores deberán acceder desde la nueva dirección.</small>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-8">
				<input type="hidden" name="IDEquipo" value="<?php echo $equipo_actual->ID; ?>">
			<hr>
			<button type="submit" class="btn btn-outline-warning" name="EdicionEquipo">Actualizar</button>
				</div>
			</div>
        
      </div>
  </div>
  </form>
</div>
<?php include("footer.php"); ?>
<?php } ?> 
</div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
	$("#FotoNuevoEscudo").change(function() {
  		filename = this.files[0].name
  		console.log(filename);
		document.getElementById("imgSeleccionada").innerHTML = "Seleccionado: "+filename;
	});
</script>

</body>
</html>
