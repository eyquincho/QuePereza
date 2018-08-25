<?php
        session_start();
        session_destroy();
		header('Location: http://'.$_SESSION["teamnick"].'.quepereza.es');
		session_unset();
        exit;
?>
