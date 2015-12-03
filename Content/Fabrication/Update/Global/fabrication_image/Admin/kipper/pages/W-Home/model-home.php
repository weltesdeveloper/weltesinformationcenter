<?php
session_start();
require_once('../../config/dbAkademik.php');
$con = new dbAkademik();

$dataWhere = array(
	"username" => $_POST['username'],
	"password" => md5($_POST['password'])
);

/*
$dataUser = $con->selectFrom("w_user",$dataWhere)

$_SESSION['username'] = $dataUser['username'];
$_SESSION['nama_user'] = $dataUser['nama_user'];		
$_SESSION['password'] = $dataUser['password'];
$_SESSION['id_level'] = $dataUser['id_level'];
$_SESSION['nama_level'] = $dataUser['nama_level'];
$_SESSION['id_fakultas'] = $dataUser['id_fakultas'];
$_SESSION['id_jurusan'] = $dataUser['id_jurusan'];*/



echo json_encode("OK");

?>