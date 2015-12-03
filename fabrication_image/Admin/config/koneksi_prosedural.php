<?php
	
	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "db_itk";
	
	/*
	$server = "localhost";
	$username = "unmerpon_web_tes";
	$password = "cakhadi123!@";
	$database = "unmerpon_web_tes";	
	*/
	
	mysql_connect($server,$username,$password) or die("Koneksi gagal");
	mysql_select_db($database) or die("Database tidak bisa dibuka");
	

?>