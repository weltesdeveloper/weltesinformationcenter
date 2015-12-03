<?php
session_start();
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$dataWhere = array(
	"username" => $_POST['username'],
	"password" => md5($_POST['password'])
);

$pass = md5($_POST['password']);
$query = "SELECT * FROM w_user WHERE id_user='$_POST[username]' AND password='$pass'";
	
$data = $con->execQuery($query);
$status = false;
if(empty($data)){
	$status = false;
	echo json_encode($status);
}
else{
	$status = true;
	
	$_SESSION['id_user'] = $data[0]['id_user'];
	$_SESSION['password'] = $data[0]['password'];
	$_SESSION['nama_user'] = $data[0]['nama_user'];
	$_SESSION['id_level'] = $data[0]['id_level'];

	echo json_encode($status);
}

/*
$dataUser = $con->selectFrom("w_user",$dataWhere)

$_SESSION['username'] = $dataUser['username'];
$_SESSION['nama_user'] = $dataUser['nama_user'];		
$_SESSION['password'] = $dataUser['password'];
$_SESSION['id_level'] = $dataUser['id_level'];
$_SESSION['nama_level'] = $dataUser['nama_level'];
$_SESSION['id_fakultas'] = $dataUser['id_fakultas'];
$_SESSION['id_jurusan'] = $dataUser['id_jurusan'];*/


?>