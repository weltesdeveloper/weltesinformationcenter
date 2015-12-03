<?php
require_once('dbConfig.php');

class dbAkademik extends dbConfig
{
	function __construct() {
		parent::setHost("lazuardi-pc");
		parent::setDbName("DBAkademik");
		parent::setUser("sa");
		parent::setPassword("P@ssw0rd");
	}

}

?>