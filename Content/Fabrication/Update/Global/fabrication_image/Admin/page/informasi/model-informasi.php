<?php
//include '../../fungsi/fungsi_thumb.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "view":
		$id = $_POST['id'];
		$idWhere = array(
			'id_informasi' 	=> 	$id,
		);

		$dataBalik = $con->selectFrom('m_informasi','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	default:
		break;
}

?>