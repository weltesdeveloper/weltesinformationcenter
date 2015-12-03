<?php
//include '../../fungsi/fungsi_thumb.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "view_berita":
		$idWhere = array(
			'row_status'	=>	1,
		);
			
		$dataBalik = $con->selectFrom('m_berita','*',$idWhere);
		
		echo json_encode($dataBalik);
		break;
		
	default:
		break;
}

?>