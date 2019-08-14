<!DOCTYPE html>
<html lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-6978317-26"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-6978317-26');
    </script>

    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>QuePereza</title>

    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="home/css/device-mockups.css">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="home/css/new-age.min.css" >

  </head>

  <body id="page-top">

    <!-- Navigation -->
    <header class="masthead">
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-lg-7 my-auto">
			<?php
				if (isset($_GET['fail'])) {
				echo "<div class=\"alert alert-warning\" role=\"alert\">Parece que has intentado acceder a un equipo que no existe <strong>". $_GET['fail'] .".quepereza.es</strong>. Si has escrito mal la dirección, inténtalo de nuevo. </div>";
				} else {}
			?>
            <div class="header-content mx-auto">
              <h1 class="mb-5"><strong>QuePereza</strong> te permite gestionar la asistencia a los entrenamientos semanales de tu equipo deportivo</h1>
              <a data-toggle="modal" data-target="#solicitud" class="btn btn-outline btn-xl js-scroll-trigger">Solicita acceso</a>
            </div>
          </div>
          <div class="col-lg-5 my-auto">
            <div class="device-container">
              <div class="device-mockup iphone6_plus portrait white">
                <div class="device">
                  <div class="screen">
                    <img src="home/demo-screen-1.jpg" class="img-fluid" alt="">
                  </div>
                  <div class="button">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
<div class="modal" id="solicitud">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Solicitar acceso a QuePereza</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
		Actualmente QuePereza es un sistema en desarrollo, y con bastantes deficiencias, principalmente en cuanto a seguridad (algunas de ellas, debido al propio funcionamiento de la plataforma). </br>
		Si de todos modos has visto la aplicación, y quieres dar de alta a tu equipo, por favor contacta con <strong>contacto@quepereza.es</strong>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>
    
    <footer>
      <div class="container">
        <ul class="list-inline">
          <li class="list-inline-item">
            <a href="http://muruais.com"><strong>Quincho</strong></a>
          </li>
          <li class="list-inline-item">
            <a href="https://github.com/eyquincho/QuePereza">GitHub</a>
          </li>
        </ul>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="home/js/new-age.min.js"></script>

  </body>

</html>
