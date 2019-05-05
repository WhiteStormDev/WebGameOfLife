<?php
	header("Content-Type: text/html; charset=utf-8");
	session_start ();
	$db = mysqli_connect('localhost', 'root', 'password');
	mysqli_select_db($db, 'GameOfLife');
?>
