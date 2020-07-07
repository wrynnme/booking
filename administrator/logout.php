<?php
	require_once("../config.php") ;
	session_start();
	session_destroy();
	hd("../login/");
?>
