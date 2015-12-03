<?php

switch($SESSION_ID_LEVEL){
	
	//ADMIN SUPERUSER
	case "1":
		include "pages/M-Product/view-product.php";
		break;

	default:
		break;


}





?>