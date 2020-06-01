<?php
	session_start();
	
	unset($_SESSION['login']);
	unset($_SESSION['last_access']);
	unset($_SESSION['ipaddr']);
	unset($_SESSION['droits']);
	unset($_SESSION['id']);
	
	header("Location: ../index.php");
?>