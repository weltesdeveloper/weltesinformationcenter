<?php
require_once('dbConfig.php');

class dbWeb extends dbConfig
{
	function __construct() {
		/*
		parent::setHost("localhost");
		parent::setDbName("unmerpon_web_tes");
		parent::setUser("unmerpon_web_tes");
		parent::setPassword("cakhadi123!@");
		*/
		
		 parent::setHost("localhost");
		parent::setDbName("db_itk");
		parent::setUser("root");
		parent::setPassword(""); 
	}

}

?>