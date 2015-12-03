<?php
include '../../fungsi/fungsi_thumb.php';
include '../../fungsi/library.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "selectInformasi":
				
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_informasi','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$idIn = $_POST['id'];
		
		$idWhere = array(
			'id_informasi'	=>	$idIn,
		);

		$dataBalik = $con->selectFrom('m_informasi','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "hapusInformasi":
		$idIn = $_POST['id'];
		
		/*$fieldValue = array(
			'row_status'	=>	'0',
		);*/
		
		$idWhere = array(
			'id_informasi'	=>	$idIn
		);
		
		$query 	= "select * from m_informasi where id_informasi = '$idIn'";
		$data = $con->execQuery($query);
		$gambar = $data[0]['gambar'];
		unlink("../../../uploads/fotoInformasi/$gambar");   
		unlink("../../../uploads/fotoInformasi/kecil_$gambar");
		
		//$con->update('m_informasi',$fieldValue,$idWhere);
		$con->delete('m_informasi',$idWhere);
		
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
			$UploadDirectory	= 'uploads/fotoInformasi/';
			
			//check if this is an ajax request
			if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
				echo json_encode("");
			}
			
			$File_Name          = strtolower($_FILES['FileInput']['name']);
			$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //Mengambil ext. saja (.jpg)
			$Random_Number      = rand(0, 9999999999); //Random number to be added to name.
			$NewFileName 	    = $Random_Number.$File_Ext; //new file name
			//$FullPath 	    = "uploads/fotoInformasi/".$NewFileName;
			//$NewFileName 	    = $_POST['namaPj'].$File_Ext; //new file name
			
			UploadInformasi($NewFileName);
			//move_uploaded_file($_FILES['FileInput']['tmp_name'], "../../../uploads/fotoInformasi/".$NewFileName); //Mengupload langsung tanpa di re-Size
			$foto		= $NewFileName;
		}
		
		$idIn = $_POST['idInformasi'];
		
		$fieldValue = array(
			'judul'	=>	$_POST['judulIn']
			,'posisi_foto'	=>	$_POST['pf']
			,'isi'	=>	$_POST['isi']
			,'gambar'	=>	$foto
			,'jam'			=>	$jam_sekarang
			,'hari'			=>	$hari_ini
			,'tgl_posting'	=>	$tgl_sekarang
			,'row_status'	=>	1
		);
		
		if($idIn == ''){
			$con->insert('m_informasi',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_informasi'	=>	$idIn
			);
			
			//Jika FIle Foto Diganti Maka File Foto Yang lama Harus Di Unlink
			if ($foto != ""){
				$query 	= "select * from m_informasi where id_informasi = '$idIn'";
				$data = $con->execQuery($query);
				$gambar = $data[0]['gambar'];
				unlink("../../../uploads/fotoInformasi/$gambar");   
				unlink("../../../uploads/fotoInformasi/kecil_$gambar");
			}
			
			$con->update('m_informasi',$fieldValue,$idWhere);
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