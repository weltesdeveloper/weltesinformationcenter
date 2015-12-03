<div id="content">	
	<section id="viewVideo">

		<section id="widget-grid" class="">
			
			<div id="list">
			
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-greenLight" data-widget-editbutton="false">
			
					<header>
						<h2>List <strong>Upload Video</strong></h2>						
					</header>

					<div>
						
						<!-- widget content -->
						<div class="widget-body">
							
							<div class="widget-body-toolbar">
								<button type="button" class="btn btn-primary btn-sm" id="btnAdd">Tambah Data</button>
							</div>
							
							<div class="table-responsive">
							  <table class="table table-striped table-bordered table-condensed table-hover" id="tabelData">
								
								<thead>
									<tr>
										<th></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th></th>
									</tr>
									<tr>
										<th>ID </th>
										<th>Nama File</th>
										<th>File</th>
										<th>Tgl.Posting</th>
										<th>Aksi</th>
									</tr>
								</thead>
								
								<tfoot>
								
								</tfoot>

							  </table>
							</div>
							
						</div>
						<!-- end widget content -->
						
					</div>
					<!-- end widget div -->
					
				</div>
				<!-- end widget -->
			</div>
			
			
			<div id="form" style="display:none" >
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-teal" data-widget-editbutton="false">
			
					<header>
						<h2>Form <strong>Upload Video</strong> </h2>	

					</header>

					<div>
						
						<!-- widget content -->
						<div class="widget-body">
						
							<form id="formInput" class="form-horizontal iconAfterControl" enctype="multipart/form-data">
									
								<input type="text" name="idVideo" id="idVideo" value="" />
                                <input name="MAX_FILE_SIZE" value="8931439" type="text"/>
																
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Nama File</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="nama" id="nama"/>
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Foto</label>
										<div class="col-xs-9 col-lg-6 inputGroupContainer">
											<div class="input-group">
                                                <img id="displayFoto" src="img/foto.jpg" alt="" width="30%" />
													<br>
													<br>
													<!-- The fileinput-button span is used to style the file input field as button -->
													<label id="statusFile">Type File harus .MP4 Ukuran File dibawah 7 MB</label>
													<br>
													<span class="btn btn-success fileinput-button">
														<i class="glyphicon glyphicon-plus"></i>
														<span>Pilih Video</span>
														<!-- The file input field used as target for the file upload widget -->
														<input id="imgInput" type="file" name="FileInputVideo">
													</span>
											</div>
										</div>
									</div>
								</fieldset>										
								
								
								<hr>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-2 col-lg-3 control-label"></label>
										<div class="col-xs-9 col-lg-6 selectContainer">
											<button class="btn btn-primary" type="submit" id="btnSimpan" >
												<i class="glyphicon glyphicon-save"></i>
												Simpan 
											</button>
											<button class="btn btn-danger" type="reset" id="btnCancel">
												<i class="glyphicon glyphicon-remove"></i>
												Batal
											</button>
										</div>
									</div>
								</fieldset>
								
								
							</form>

							
						</div>
						<!-- end widget content -->
						
					</div>
					<!-- end widget div -->
					
				</div>
				<!-- end widget -->
			</div>
		
		</section>

	
		
	</section>
</div>
		
	
	
		
<!-- TARUH SCRIPT MODUL MAHASISWA DI SINI -->
<script src="pages/M-Video/script-video.js" type="text/javascript"></script>
		
