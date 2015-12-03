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

		$dataBalik = $con->selectFrom('m_product','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectKatProduct":
				
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_kat_product','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$id_product = $_POST['id'];
		
		$idWhere = array(
			'id_product'	=>	$id_product,
		);

		$dataBalik = $con->selectFrom('m_product','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "hapus":
		$id_product = $_POST['id'];
		
		$idWhere = array(
			'id_product'	=>	$id_product
		);
		
		$query 	= "select * from m_product where id_product = '$id_product'";
		$data = $con->execQuery($query);
		$gambar = $data[0]['gambar_product'];
		unlink("../../../uploads/fotoProduct/$gambar");   
		unlink("../../../uploads/fotoProduct/kecil_$gambar");
		
		$con->delete('m_product',$idWhere);		
		
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
			
			//check if this is an ajax request
			if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
				echo json_encode("");
			}
			
			$File_Name          = strtolower($_FILES['FileInput']['name']);
			$File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //Mengambil ext. saja (.jpg)
			$Random_Number      = rand(0, 9999999999); //Random number to be added to name.
			$NewFileName 	    = $_POST['nama_product'].$Random_Number.$File_Ext; //new file name
			
			UploadProduct($NewFileName);
			$foto		= $NewFileName;
		}
	
		$idProduct = $_POST['idProduct'];
		
		$fieldValue = array(
			'id_kat_product'	=>	$_POST['kat_product']
			,'nama_product'		=>	$_POST['nama_product']
			,'ket_product'		=>	$_POST['ket']
			,'gambar_product'	=>	$foto
			,'row_status'		=>	1
		);
		
		if($idProduct == ''){
			$con->insert('m_product',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_product'	=>	$idProduct
			);
			
			//Jika FIle Foto Diganti Maka File Foto Yang lama Harus Di Unlink
			if ($foto != ""){
				$query 	= "select * from m_product where id_product = '$idProduct'";
				$data = $con->execQuery($query);
				$gambar = $data[0]['gambar_product'];
				unlink("../../../uploads/fotoProduct/$gambar");   
				unlink("../../../uploads/fotoProduct/kecil_$gambar");
			}
		
			$con->update('m_product',$fieldValue,$idWhere);
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