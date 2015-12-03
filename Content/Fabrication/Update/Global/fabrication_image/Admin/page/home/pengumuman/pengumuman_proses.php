<?php
//include '../../fungsi/fungsi_thumb.php';
require_once('../../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "view":
		
			
		$idWhere = array(
			'row_status'	=>	1,
		);
		
		$orderby = array(
			'id_berita desc limit 3'	
		);
			
		$dataBalik = $con->selectFrom('m_berita','*',$idWhere,$orderby);		
		
		echo json_encode($dataBalik);
		break;	
		
	case "view_promosi":
				
		$idWhere = array(
			'row_status'	=>	1,
		);
		
		$orderby = array(
			'id_pengumuman desc limit 3'	
		);
		
		$dataBalik = $con->selectFrom('m_pengumuman','*',$idWhere,$orderby);

		echo json_encode($dataBalik);
		break;
	
		
	default:
		break;
}

?>
