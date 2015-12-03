<?php 

if(isset($_GET['page'])){
	$page = $_GET['page'];
}else{
	$page = 'galeri';
}

switch($page){
	
	case "home";
		include "temp/atas.php";
		include "temp/menu_atas.php";
		include "temp/wrapper.php";
		include "page/home/slide/slide.php";
		//include "page/home/pengumuman/pengumuman.php";
		//include "page/home/banner/banner.php";
		include "temp/penutup_wrapper.php";
		include "temp/bawah.php";
		break;
		
	case "informasi";
		$title1 = "Sejarah ";
		$title2 = "Singkat";
		$sub1 = "Profil";
		$sub2 = "";
		include "temp/atas.php";
		include "temp/menu_atas.php";
		include "temp/breadcrumb.php";
		include "page/informasi/informasi.php";
		include "temp/menu_kanan.php";	
		include "temp/penutup_breadcrumb.php";
		include "temp/bawah.php";
		break;	
		
	case "berita";
		$title1 = "Berita ";
		$title2 = "Kami";
		$sub1 = "";
		$sub2 = "";
		include "temp/atas.php";
		include "temp/menu_atas.php";
		include "temp/breadcrumb.php";
		include "page/berita/berita.php";
		include "temp/menu_kanan.php";	
		include "temp/penutup_breadcrumb.php";
		include "temp/bawah.php";
		break;	
		
	case "promosi";
		$title1 = "Promosi ";
		$title2 = "Kami";
		$sub1 = "";
		$sub2 = "";
		include "temp/atas.php";
		include "temp/menu_atas.php";
		include "temp/breadcrumb.php";
		include "page/promosi/promosi.php";
		include "temp/menu_kanan.php";	
		include "temp/penutup_breadcrumb.php";
		include "temp/bawah.php";
		break;	
		
	case "galeri";
		$title1 = "Galeri";
		$title2 = "";
		$sub1 = "Foto";
		$sub2 = "";
		include "temp/atas.php";
		include "temp/menu_atas.php";		
		include "temp/breadcrumb.php";		
		include "page/galeri/galeri.php";
		include "temp/menu_kanan.php";	
		include "temp/penutup_breadcrumb.php";	
		include "temp/bawah.php";
		break;

	case "video";
		$title1 = "Video";
		$title2 = "Kami";
		$sub1 = "";
		$sub2 = "";
		include "temp/atas.php";
		include "temp/menu_atas.php";		
		include "temp/breadcrumb.php";		
		include "page/video/video.php";
		include "temp/penutup_breadcrumb.php";	
		include "temp/bawah.php";
		break;

}
	


?>