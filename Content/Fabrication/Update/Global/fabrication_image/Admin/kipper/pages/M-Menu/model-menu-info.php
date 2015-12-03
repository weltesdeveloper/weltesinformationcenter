<?php
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "selectBerita":
				
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_berita','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$idberita = $_POST['id'];
		
		$idWhere = array(
			'id_berita'	=>	$idberita,
		);

		$dataBalik = $con->selectFrom('m_berita','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "hapusFak":
		$idFak = $_POST['id'];
		
		$fieldValue = array(
			'row_status'	=>	'0',
		);
		
		$idWhere = array(
			'id_fakultas'	=>	$idFak
		);
		
		$con->update('m_fakultas',$fieldValue,$idWhere);
		
		if($con->commitQuery()){
			$pesan = "Query Berhasil";
		}
		else{
			$pesan = "Query Gagal";
		}

		echo json_encode($pesan);
		break;
		
	case "commit":
		$idBerita = $_POST['idBerita'];
		
		$fieldValue = array(
			'judul'	=>	$_POST['judulBerita']
			,'isi'	=>	$_POST['isi']
			,'gambar'	=>	$_POST['gambar']
			,'row_status'	=>	1
		);
		
		if($idBerita == ''){
			$con->insert('m_berita',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_berita'	=>	$idBerita
			);
		
			$con->update('m_berita',$fieldValue,$idWhere);
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