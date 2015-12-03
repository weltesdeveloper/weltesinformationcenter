<?php
include '../../fungsi/library.php';
include '../../fungsi/fungsi_thumb.php';
require_once('../../config/dbWeb.php');
$con = new dbWeb();

$ACTION = $_POST['action'];

switch($ACTION){
	
	case "selectVideo":
				
		$idWhere = array(
			'row_status'	=>	1,
		);

		$dataBalik = $con->selectFrom('m_video','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$idVD = $_POST['id'];
		
		$idWhere = array(
			'id_video'	=>	$idVD,
		);

		$dataBalik = $con->selectFrom('m_video','*',$idWhere);

		echo json_encode($dataBalik);
		break;
		
	case "hapusVideo":
		$idVD = $_POST['id'];
		
		/*$fieldValue = array(
			'row_status'	=>	'0',
		);*/
		
		$idWhere = array(
			'id_video'	=>	$idVD
		);
		
			$query 	= "select * from m_video where id_video = '$idVD'";
			$data = $con->execQuery($query);
			$file = $data[0]['file'];
			unlink("../../../uploads/fileVideo/$file");   
			unlink("../../../uploads/fileVideo/kecil_$file");
			
		//$con->update('m_video',$fieldValue,$idWhere);
		$con->delete('m_video',$idWhere);
		
		if($con->commitQuery()){
			$pesan = "Query Berhasil";
		}
		else{
			$pesan = "Query Gagal";
		}

		echo json_encode($pesan);
		break;
		
	case "commit":
	
		
		$name=$_FILES['FileInputVideo']['name'];
		$type=$_FILES['FileInputVideo']['type'];
		$size=$_FILES['FileInputVideo']['size'];
		//replace tanda spasi pada nama file dengan _
		$nama_file=str_replace(" ","_",$name);
		$tmp_name=$_FILES['FileInputVideo']['tmp_name'];
		$nama_folder="../../../uploads/fileVideo/";
		$nama_file_baru=$nama_folder.basename($nama_file);
		//Filter jenis file video dan ukuran file
		//if ((($type == "video/mp4") || ($type == "video/3gpp")	|| ($type == "video/x-flv")) && ($size < $_POST['MAX_FILE_SIZE']) )
		if ((($type == "video/mp4") || ($type == "video/3gp")	|| ($type == "video/x-flv")) && ($size < $_POST['MAX_FILE_SIZE']) )
		{
			//cek jika nama dile sudah ada
			if (file_exists($nama_file_baru))
			{
				$msg="Nama file $nama_file sudah ada!\n";
			} 
			else
			{	
				//pindah file dari temporari ke alamat tujuan
				if(move_uploaded_file($tmp_name,$nama_file_baru))
				{
					$msg="File video $nama_file berhasil diupload";
				}
			}
		} 
		else
		{
			$msg="Jenis file tidak sesuai atau ukuran file terlalu besar!";
		}
		echo "<p align=\"center\">$msg</p>";
		
		$idVD = $_POST['idVideo'];
		
		$fieldValue = array(
			'nama'	=>	$_POST['nama']
			,'file'	=>	$nama_file
			,'tgl_posting'	=>	$tgl_sekarang
			,'row_status'	=>	1
		);
		
		if($idVD == ''){
			
			$con->insert('m_video',$fieldValue);
		}
		else{
			$idWhere = array(
				'id_video'	=>	$idVD
			);
			
			if ($foto != ""){
				$query 	= "select * from m_video where id_video = '$idVD'";
				$data = $con->execQuery($query);
				$file = $data[0]['file'];
				unlink("../../../uploads/fileVideo/$file");   
				unlink("../../../uploads/fileVideo/kecil_$file");
			}
		
			$con->update('m_video',$fieldValue,$idWhere);
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