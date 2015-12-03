<?php
include '../../fungsi/fungsi_thumb.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "selectSlide":
				
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_slide','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$idSlide = $_POST['id'];
		
		$idWhere = array(
			'id_slide'	=>	$idSlide,
		);

		$dataBalik = $con->selectFrom('m_slide','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "hapusSlide":
		$idSlide = $_POST['id'];
		
		$query 	= "select * from m_slide where id_slide = '$idSlide'";
		$data = $con->execQuery($query);
		$gambar = $data[0]['gambar'];
		unlink("../../../uploads/fotoSlide/$gambar");   
  		unlink("../../../uploads/fotoSlide/kecil_$gambar");
		
		/*$fieldValue = array(
			'row_status'	=>	'0',
		);*/
		
		$idWhere = array(
			'id_slide'	=>	$idSlide
		);
		
		//$con->update('m_slide',$fieldValue,$idWhere);
		$con->delete('m_slide',$idWhere);
		
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
			$UploadDirectory	= 'uploads/fotoSlide/';
			
			//check if this is an ajax request
			if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
				echo json_encode("");
			}
			
			$File_Name          = strtolower($_FILES['FileInput']['name']);
			$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //Mengambil ext. saja (.jpg)
			$Random_Number      = rand(0, 9999999999); //Random number to be added to name.
			$NewFileName 	    = $Random_Number.$File_Ext; //new file name
			//$FullPath 	    = "uploads/fotoSlide/".$NewFileName;
			//$NewFileName 	    = $_POST['namaPj'].$File_Ext; //new file name
			
			UploadSlide($NewFileName);
			//move_uploaded_file($_FILES['FileInput']['tmp_name'], "../../../uploads/fotoSlide/".$NewFileName); //Mengupload langsung tanpa di re-Size
			$foto		= $NewFileName;
		}
	
	
		$idSlide = $_POST['idSlide'];
		
		$fieldValue = array(
			'judul'			=>	$_POST['judulSlide']
			,'keterangan'	=>	$_POST['ket']
			,'gambar'		=>	$foto
			,'status'		=>	$_POST['statusSlide']
			,'row_status'	=>	1
		);
		
		if($idSlide == ''){
			$con->insert('m_slide',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_slide'	=>	$idSlide
			);
			//Jika FIle Foto Diganti Maka File Foto Yang lama Harus Di Unlink
			if ($foto != ""){
				$query 	= "select * from m_slide where id_slide = '$idSlide'";
				$data = $con->execQuery($query);
				$gambar = $data[0]['gambar'];
				unlink("../../../uploads/fotoSlide/$gambar");   
				unlink("../../../uploads/fotoSlide/kecil_$gambar");
			}
			$con->update('m_slide',$fieldValue,$idWhere);
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