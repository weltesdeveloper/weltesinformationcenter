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

		$dataBalik = $con->selectFrom('m_gallery','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectKatGallery":
				
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_kat_gallery','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$id_gallery = $_POST['id'];
		
		$idWhere = array(
			'id_gallery'	=>	$id_gallery,
		);

		$dataBalik = $con->selectFrom('m_gallery','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "hapus":
		$id_gallery = $_POST['id'];
		
		$idWhere = array(
			'id_gallery'	=>	$id_gallery
		);
		
		$query 	= "select * from m_gallery where id_gallery = '$id_gallery'";
		$data = $con->execQuery($query);
		$gambar = $data[0]['gambar'];
		unlink("../../../uploads/fotoGallery/$gambar");   
		unlink("../../../uploads/fotoGallery/kecil_$gambar");
		
		$con->delete('m_gallery',$idWhere);		
		
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
			$UploadDirectory	= '../../../uploads/fotoGallery/';
			
			//check if this is an ajax request
			if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
				echo json_encode("");
			}
			
			$File_Name          = strtolower($_FILES['FileInput']['name']);
			$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //Mengambil ext. saja (.jpg)
			$Random_Number      = rand(0, 9999999999); //Random number to be added to name.
			$NewFileName 	    = $_POST['nama_gallery'].$Random_Number.$File_Ext; //new file name
			//$FullPath 	    = "uploads/fotoGallery/".$NewFileName;
			//$NewFileName 	    = $_POST['namaPj'].$File_Ext; //new file name
			
			UploadGallery($NewFileName);
			//move_uploaded_file($_FILES['FileInput']['tmp_name'], "../../uploads/fotoGallery/".$NewFileName); //Mengupload langsung tanpa di re-Size
			$foto		= $NewFileName;
		}
	
		$idGallery = $_POST['idGallery'];
		
		$fieldValue = array(
			'id_kat_gallery'	=>	$_POST['kat_galeri']
			,'nama_gallery'		=>	$_POST['nama_gallery']
			,'ket_gallery'		=>	$_POST['ket']
			,'gambar_gallery'	=>	$foto
			,'row_status'		=>	1
		);
		
		if($idGallery == ''){
			$con->insert('m_gallery',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_gallery'	=>	$idGallery
			);
			
			//Jika FIle Foto Diganti Maka File Foto Yang lama Harus Di Unlink
			if ($foto != ""){
				$query 	= "select * from m_gallery where id_gallery = '$idGallery'";
				$data = $con->execQuery($query);
				$gambar = $data[0]['gambar_gallery'];
				unlink("../../../uploads/fotoGallery/$gambar");   
				unlink("../../../uploads/fotoGallery/kecil_$gambar");
			}
		
			$con->update('m_gallery',$fieldValue,$idWhere);
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