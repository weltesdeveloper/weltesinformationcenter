<?php
include '../../fungsi/fungsi_thumb.php';
include '../../fungsi/library.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "selectBerita":
				
		$idWhere = array(
			'row_status'	=>	1
		);
		
		$orderby = array(
			'id_berita desc'	
		);

		$dataBalik = $con->selectFrom('m_berita','*',$idWhere,$orderby);

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
		
	case "hapusBerita":
		$idberita = $_POST['id'];
		
		$query 	= "select * from m_berita where id_berita = '$idberita'";
		$data = $con->execQuery($query);
		$gambar = $data[0]['gambar'];
		unlink("../../../uploads/fotoBerita/$gambar");   
  		unlink("../../../uploads/fotoBerita/kecil_$gambar");
		
		/*$fieldValue = array(
			'row_status'	=>	'0',
		);*/
		
		$idWhere = array(
			'id_berita'	=>	$idberita
		);
		
		//$con->update('m_berita',$fieldValue,$idWhere); // Untuk Delete Tapi Update ke status 0
		$con->delete('m_berita',$idWhere);
		
		if($con->commitQuery()){
			$pesan = "Query Berhasil";
		}
		else{
			$pesan = "Query Gagal";
		}

		echo json_encode($pesan);
		break;
		
	case "commit":
	
	
		if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK){
			$UploadDirectory	= 'uploads/fotoBerita/';
			
			//check if this is an ajax request
			if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
				echo json_encode("");
			}
			
			$File_Name          = strtolower($_FILES['FileInput']['name']);
			$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //Mengambil ext. saja (.jpg)
			$Random_Number      = rand(0, 9999999999); //Random number to be added to name.
			$NewFileName 	    = $Random_Number.$File_Ext; //new file name
			//$FullPath 	    = "uploads/fotoBerita/".$NewFileName;
			//$NewFileName 	    = $_POST['namaPj'].$File_Ext; //new file name
			
			UploadBerita($NewFileName);
			//move_uploaded_file($_FILES['FileInput']['tmp_name'], "../../../uploads/fotoBerita/".$NewFileName); //Mengupload langsung tanpa di re-Size
			$foto		= $NewFileName;
		}
	
		$idBerita = $_POST['idBerita'];
		
		$fieldValue = array(
			'judul'			=>	$_POST['judulBerita']
			,'isi'			=>	$_POST['isi']
			,'gambar'		=>	$foto			
			,'jam'			=>	$jam_sekarang
			,'hari'			=>	$hari_ini
			,'tgl_posting'	=>	$tgl_sekarang
			,'row_status'	=>	1
		);
		
		if($idBerita == ''){
			$con->insert('m_berita',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_berita'	=>	$idBerita
			);
			
			//Jika FIle Foto Diganti Maka File Foto Yang lama Harus Di Unlink
			if ($foto != ""){
				$query 	= "select * from m_berita where id_berita = '$idBerita'";
				$data = $con->execQuery($query);
				$gambar = $data[0]['gambar'];
				unlink("../../../uploads/fotoBerita/$gambar");   
				unlink("../../../uploads/fotoBerita/kecil_$gambar");
			}
		
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