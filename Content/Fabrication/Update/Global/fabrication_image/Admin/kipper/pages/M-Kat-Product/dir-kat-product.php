<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Kat-Product/view-kat-product.php";
		break;

	default:
		break;


}


?>