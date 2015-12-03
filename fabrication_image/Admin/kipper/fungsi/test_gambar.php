<?php 
include "config/koneksi.php";
		$q = "select * from slide where id = '71'";
		$h=mysql_query($q);
		$dd= mysql_fetch_array($h);

echo $dd[namafile];
?>

<img src="<?php echo "img_slide/".$dd[namafile] ;?>" />
<img src="slide/23076IMG_20131005_094652.jpg" />