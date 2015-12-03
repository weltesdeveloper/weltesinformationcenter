<?php 
session_start();

if(isset($_GET['page'])){
	if(isset($_SESSION['id_user'])){
		$page = $_GET['page'];
		
		$SESSION_ID_USER = $_SESSION['id_user'];
		$SESSION_NAMA_USER = $_SESSION['nama_user'];
		$SESSION_ID_LEVEL = $_SESSION['id_level'];
		
	}else{
		$page = "login";
	}	
}
else{
	$page = "login";
}

switch($page){

	default:
		break;
		
	case "login":
		include "pages/W-Login/view-login.php";		
		break;	
		
	case "logout":
		include "pages/W-Logout/Logout.php";		
		break;	
		
	case "m_menu":
		$title = "Menu Front End";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Menu/dir-menu-info.php";
		include "temp/footer.php";
		
		break;
	
	case "dashboard":
		$url = "index.php?page=dashboard";
		$title = "Dashboard";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "temp/footer.php";
		
		break;
		
	case "menu":
		$title = "Menu";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/W-menu/dir-menu.php";
		include "temp/footer.php";
		
		break;
	
	case "berita":
		$title = "Berita";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Berita/dir-berita.php";
		include "temp/footer.php";
		
		break;	
		
	case "pengumuman":
		$title = "Berita";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Pengumuman/dir-pengumuman.php";
		include "temp/footer.php";
		
		break;	
		
	case "slide":
		$title = "Slide";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Slide/dir-slide.php";
		include "temp/footer.php";
		
		break;	
		
	case "informasi":
		$title = "Informasi";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Informasi/dir-informasi.php";
		include "temp/footer.php";
		
		break;
		
	
	case "kat_gallery":
		$title = "Kategori Gallery";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Kat-Gallery/dir-kat-gallery.php";
		include "temp/footer.php";
		
		break;	
		
	case "kat_product":
		$title = "Kategori Product";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Kat-Product/dir-kat-product.php";
		include "temp/footer.php";
		
		break;	
		
	case "product":
		$title = "Product";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Product/dir-product.php";
		include "temp/footer.php";
		
		break;	
		
	case "gallery":
		$title = "Gallery";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Gallery/dir-gallery.php";
		include "temp/footer.php";
		
		break;	
		
	case "video":
		$title = "Video";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Video/dir-video.php";
		include "temp/footer.php";
		
		break;	
		
	case "forum":
		$title = "Forum Tanya Jawab";
		include "temp/header.php";
		include "temp/menuleft.php";
		include "temp/ribbon.php";
		include "pages/M-Forum/dir-forum.php";
		include "temp/footer.php";
		
		break;		
		

}

?>