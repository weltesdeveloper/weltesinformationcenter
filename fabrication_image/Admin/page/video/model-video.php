<?php
//include '../../fungsi/fungsi_thumb.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "view_gallery":
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_kat_gallery','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "view_gallery_detil":
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_gallery','*',$idWhere);

		echo json_encode($dataBalik);
		break;
	
		
	default:
		break;
}

?>