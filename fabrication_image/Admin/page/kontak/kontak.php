<section id="section_kontak">

	<div class="col-md-12">

		<h2>Silahkan hubungi <strong>kami</strong></h2>

		<!-- 
			if you want to use your own contact script, remove .hide class
		-->

		<!-- SENT OK -->
		<div id="_sent_ok_" class="alert alert-success fade in fsize16 hide">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Terima kasih!</strong> Pesan anda berhasil terkirim!
		</div>
		<!-- /SENT OK -->

		<!-- SENT FAILED -->
		<div id="_sent_required_" class="alert alert-danger fade in fsize16 hide">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Perhatian!</strong> Silahkan lengkapi data yang bertanda (*) bintang!
		</div>
		<!-- /SENT FAILED -->

		<form id="contactForm" class="white-row" action="php/contact.php" method="post">
			<div class="row">
				<div class="form-group">
					<div class="col-md-4">
						<label>Nama Lengkap *</label>
						<input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name">
					</div>
					<div class="col-md-4">
						<label>Alamat E-mail *</label>
						<input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email">
					</div>
					<div class="col-md-4">
						<label>Telpon</label>
						<input type="text" value="" data-msg-required="Please enter your phone" data-msg-email="Please enter your phone" maxlength="100" class="form-control" name="phone" id="phone">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<label>Subyek</label>
						<input type="text" value="" data-msg-required="Please enter the subject." maxlength="100" class="form-control" name="subject" id="subject">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<div class="col-md-12">
						<label>Pesan *</label>
						<textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message"></textarea>
					</div>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="col-md-12">
					<span class="pull-right"><!-- captcha -->
						<label class="block text-right fsize12">capta</label>
						<img alt="" rel="nofollow,noindex" width="50" height="18" src="php/captcha.php?bgcolor=ffffff&amp;txtcolor=000000">
						<input type="text" name="contact_captcha" id="contact_captcha" value="" data-msg-required="Please enter the subject." maxlength="6" style="width:100px; margin-left:10px;">
					</span>

					<input id="contact_submit" type="submit" value="Kirim Pesan" class="btn btn-primary btn-lg" data-loading-text="Loading...">
				</div>
			</div>
		</form>
	
	</div>

</section>