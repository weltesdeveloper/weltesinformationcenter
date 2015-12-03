<?php
include '../../fungsi/library.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "selectPengumuman":
				
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_pengumuman','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$idPeng = $_POST['id'];
		
		$idWhere = array(
			'id_pengumuman'	=>	$idPeng,
		);

		$dataBalik = $con->selectFrom('m_pengumuman','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "hapusPengumuman":
		$idPeng = $_POST['id'];
		
		/*$fieldValue = array(
			'row_status'	=>	'0',
		);*/
		
		$idWhere = array(
			'id_pengumuman'	=>	$idPeng
		);
		
		//$con->update('m_pengumuman',$fieldValue,$idWhere);
		$con->delete('m_pengumuman',$idWhere);
		
		if($con->commitQuery()){
			$pesan = "Query Berhasil";
		}
		else{
			$pesan = "Query Gagal";
		}

		echo json_encode($pesan);
		break;
		
	case "commit":
		$idPeng = $_POST['idPengumuman'];
		
		$fieldValue = array(
			'judul'			=>	$_POST['judulPeng']
			,'isi'			=>	$_POST['isi']
			,'jam'			=>	$jam_sekarang
			,'hari'			=>	$hari_ini
			,'tgl_posting'	=>	$tgl_sekarang
			,'row_status'	=>	1
		);
		
		if($idPeng == ''){
			$con->insert('m_pengumuman',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_pengumuman'	=>	$idPeng
			);
		
			$con->update('m_pengumuman',$fieldValue,$idWhere);
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