<?php
include '../../fungsi/fungsi_thumb.php';
include '../../fungsi/library.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "select":
				
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_kat_product','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$id = $_POST['id'];
		
		$idWhere = array(
			'id_kat_product'	=>	$id,
		);

		$dataBalik = $con->selectFrom('m_kat_product','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "hapus":
		$id = $_POST['id'];
		
		$idWhere = array(
			'id_kat_product'	=>	$id
		);
		
		$con->delete('m_kat_product',$idWhere);
		
		
		if($con->commitQuery()){
			$pesan = "Query Berhasil";
		}
		else{
			$pesan = "Query Gagal";
		}

		echo json_encode($pesan);
		break;
		
	case "commit":	
		$id = $_POST['id'];
		
		$fieldValue = array(
			'nama_kat_product'	=>	$_POST['nama_kat']
			,'row_status'		=>	1
		);
		
		if($id == ''){
			$con->insert('m_kat_product',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_kat_product'	=>	$id
			);
			
			$con->update('m_kat_product',$fieldValue,$idWhere);
		}
		
		$hasil = $con->logQuery();
		if($con->commitQuery()){
			$pesan = "Query Berhasil";
		}
		else{
			$pesan = "Query Gagal";
		}
		
		echo json_encode($pesan);
		break;
		
	default:
		break;
}

?>