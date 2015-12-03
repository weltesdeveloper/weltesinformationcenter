<?php
require_once('../../config/dbAkademik.php');
$con = new dbAkademik();

$ACTION = $_POST['action'];

switch($ACTION){

	case "selectMenu":
		
		$arrayHasil = $con->selectFrom("w_menu");

		echo json_encode($arrayHasil);
		break;
		
	case "selectParent":
		
		$subLevel = $_POST['subLevel'];
		$subLevel--;
		
		if($subLevel == '0'){
			$dataBalik = false;
		}
		else{
			$idWhere = array(
				'sub_level' => $subLevel
			);
			
			$dataBalik = $con->selectFrom('w_menu','*',$idWhere);
		}
		echo json_encode($dataBalik);
		break;
		
	case "commit":
		
		$idMenu = $_POST['idMenu'];
		$subLevel = $_POST['subLevel'];
		$menuParent = $_POST['menuParent'];
		$namaMenu = $_POST['namaMenu'];
		$url = $_POST['url'];
		$icon = $_POST['icon'];
		$letakMenu = $_POST['letakMenu'];
		$urutanMenu = $_POST['urutanMenu'];
		
		$fieldValue = array(
			'id_parent' => $menuParent
			,'sub_level' => $subLevel
			,'nama_menu' => $namaMenu
			,'url' => $url
			,'icon' => $icon
			,'letak_menu' => $letakMenu
			,'urutan_menu' => $urutanMenu
		);
		
		$idWhere = array(
			'id_menu' => $idMenu
		);
		
		if($idMenu == ''){//INSERT
			$con->insert("w_menu",$fieldValue);
		}
		else{//UPDATE
			$con->update("w_menu",$fieldValue,$idWhere);
		}
	
		if($con->commitQuery()){
			$dataBalik = "Query berhasil";
		}
		else{
			$dataBalik = "Query gagal";
		};

		echo json_encode($dataBalik);
		break;
		
	case "selectEdit":
		$id = $_POST['id'];
		
		$idWhere = array(
			'id_menu' => $id
		);
		
		$dataBalik = $con->selectFrom("w_menu","*",$idWhere);
		
		echo json_encode($dataBalik);
		break;
		
	case "delete":
		$id = $_POST['id'];
		
		$idWhere = array(
			'id_menu' => $id
		);
		
		$con->delete("w_menu",$idWhere);
		
		if($con->commitQuery()){
			$dataBalik = "Query berhasil";
		}
		else{
			$dataBalik = "Query gagal";
		};
		echo json_encode($dataBalik);
		break;
		

	default:
		break;
}


?>