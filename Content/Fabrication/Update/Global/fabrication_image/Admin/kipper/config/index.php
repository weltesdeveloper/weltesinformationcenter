<?php

require_once('menuAkademik.php');

$con = new menuAkademik();

$arrayUpdate = array(
	'nama_menu' => "Dashboard Bro",
);

$idWhere = array(
	'id_menu' => 1,
);

$con->update("w_menu",$arrayUpdate,$idWhere);

$arrayUpdate = array(
	'nama_menu' => "Laporan Jeh",
);

$idWhere = array(
	'id_menu' => 4,
);

$con->update("w_menu",$arrayUpdate,$idWhere);

$hasil = $con->commitQuery();
print_r($hasil);
echo "<br><br>";
echo $con->statusCommit;

echo "<br><br>";
echo $hasil;

$hasil = $con->selectFrom("w_menu",'-');
echo $hasil;


/*
$coba = array(
	'item' => 1
	,'value' => 50
	,'name' => "Arif"
);

$varDump = var_dump($coba);
$printR = print_r($coba,true);

echo $varDump;
echo "<br><br>";
echo $printR;



$arrayField = '-';

$arrayField = strtoupper($arrayField);

$arrayField = ($arrayField == '*' ? 'ALL' : $arrayField);
echo "<br><br>";
echo $arrayField;

$htmlMenu = $con->setMenuKiri();

echo $htmlMenu;

$html = $con->setMenuKanan();

echo $html;
echo htmlentities($html);


require_once('dbAkademik.php');

$con = new dbAkademik();
$con->connecting();
echo $con->statusConn;
$con->disconnect();


// CONTOH SELECT
$fields = array(
	'id_user','password'
);

$whereId = array(
	'id_user' => '1209100068'
);

//$hasilArray = $hasilArray = $con->selectFrom("w_user");
//$hasilArray = $hasilArray = $con->selectFrom("w_user","ALL",$whereId);
//$hasilArray = $con->selectFrom("w_user",$fields);
$hasilArray = $con->selectFrom("w_user",$fields,$whereId);

echo var_dump($hasilArray);

/* CONTOH SELECT SELESAI */







/*
 *** 
	-- CONTOH MENGAKSES METHOD QUERY SELECT FROM ONE TABEL / VIEW
	
	$hasilArray = $con->selectFrom("w_user);
	-- UNTUK MENGECEK ADA ATAU TIDAK DATA YANG DIAMBIL
	if(empty($array))
	-- BERNILAI 'true' jika array bernilai kosong dan sebaliknya bernilai 'false' jika array tersebut ada isinya
	if(isset($array))
	-- BERNILAI 'true' jika array ada isinya dan sebaliknya bernilai 'false' jika array tersebut kosong
	
	-- JIKA BERHASIL MAKA $hasilArray berisi data Array dari SELECT TABEL/VIEW tersebut (MESKIPUN ARRAY KOSONG)
	-- JIKA GAGAL MAKA $hasilArray bernilai 'false'
 **/
 
 
/*
 *** 
	-- CONTOH MENGAKSES SELECT DATA KE TABEL 
	
	-- 1. SELECT ALL FROM ONE TABEL/VIEW
	-- 2. SELECT ALL FROM ONE TABEL/VIEW DAN SPESIFIKASI WHERE
	-- 3. SELECT SPESIFIKASI FIELD FROM ONE TABEL/VIEW
	-- 4. SELECT SPESIFIKASI FIELD DAN SPESIFIKASI WHRE FROM ONE TABEL/VIEW
	
	- 1 -
	$hasilArray = $con->selectFrom("w_user","ALL");
	
	- 2 - 
	$hasilArray = $con->selectFrom("w_user","ALL",$whereId);
	
	- 3 - 
	$hasilArray = $con->selectFrom("w_user",$fields);
	
	- 4 - 
	$hasilArray = $con->selectFrom("w_user",$fields,$whereId);
	
	-- NB: $fields dan $whereId berbentuk array(key => value)
 **/

 
 
 
 
?>