<?php
        session_start();
        session_destroy();
		header('Location: http://'.$_SESSION["team"].'.quepereza.es');
		session_unset();
        exit;
?>
