<?php
	require_once('config/koneksi_prosedural.php');
		
	$query 	= "select * from m_slide order by id_slide desc limit 5";
	$hasil	= mysql_query($query);
?>
<section id="home_slide">
	<!-- REVOLUTION SLIDER -->
	<div class="fullwidthbanner-container roundedcorners">
		<div class="fullwidthbanner" id="slide_row">
			<ul>
				
				<?php 
					while($data = mysql_fetch_array($hasil)){
				?>
				
				<!--<li data-transition="flyin" data-slotamount="1" data-masterspeed="1000" >-->
				<li data-transition="3dcurtain-vertical" data-slotamount="7" data-masterspeed="1000" >
					<img src="uploads/fotoSlide/<?php echo "".$data['gambar'] ?>"  alt="kenburns2"  data-bgposition="left bottom" data-kenburns="on" data-duration="10000" data-ease="Power0.easeIn" data-bgfit="120" data-bgfitend="100" data-bgpositionend="right top">					
					<div class="tp-caption largeblackbg lfl customout tp-resizeme"
						data-x="100"
						data-y="bottom" data-voffset="-142"
						data-customout="x:0;y:180;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:1;scaleY:1;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
						data-speed="1000"
						data-start="2300"
						data-easing="Power4.easeInOut"
						data-endspeed="300"
						style="z-index: 2"><?php echo $data['judul'] ?>
					</div>
				</li>
				
				<!--
				<li data-transition="3dcurtain-vertical" data-slotamount="7" data-masterspeed="1000" >
					<img src="assets/images/dummy.png" alt="church3" data-lazyload="uploads/fotoSlide/<?php //echo "".$data['gambar'] ?>" data-fullwidthcentering="on">
					
					<div class="tp-caption medium_bg_asbestos skewfromleft customout"
					data-x="104"
					data-y="154" 
					data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
					data-speed="800"
					data-start="1500"
					data-easing="Power4.easeOut"
					data-endspeed="300"
					data-endeasing="Power1.easeIn"
					data-captionhidden="on"
					style="z-index: 6">Bersama Kami
					</div>
						
					<div class="tp-caption medium_bg_darkblue skewfromleft customout"
					data-x="168"
					data-y="193" 
					data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
					data-speed="800"
					data-start="1600"
					data-easing="Power4.easeOut"
					data-endspeed="300"
					data-endeasing="Power1.easeIn"
					data-captionhidden="on"
					style="z-index: 9">"<?php //echo $data['judul'] ?>"
					</div>
					
				</li>
				-->
				
				<?php } ?>
				
			</ul>
			<div class="tp-bannertimer"></div>
		</div>
	</div>
</section>	

	<section class="container text-center">
		<h1 class="text-center">
			Selamat <strong>Datang</strong>

		</h1>
		<p class="lead">VIRTUAL DIMENSI  
						adalah suatu perusahaan kecil bergerak di bidang software, hardware, network dan lain lain yang terkait dengan IT.</p>
	</section>
	<!-- /Title -->
	
	<div class="divider"><!-- divider -->
		<i class="fa fa-leaf"></i>
	</div>
