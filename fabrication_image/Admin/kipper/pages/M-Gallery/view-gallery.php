<div id="content">	
	<section id="view_galeri">

		<section id="widget-grid" class="">
			
			<div id="list">
			
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-greenLight" data-widget-editbutton="false">
			
					<header>
						<h2>List <strong>Gallery</strong></h2>						
					</header>

					<div>
						
						<!-- widget content -->
						<div class="widget-body">
							
							<div class="widget-body-toolbar">
								<button type="button" class="btn btn-primary btn-sm" id="btnAdd">Tambah Data</button>
							</div>
							
							<div class="table-responsive">
							  <table class="table table-striped table-bordered table-condensed table-hover" id="tabelData" width="100%">
								
								<thead>
									<tr>
										<th></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<!--<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>-->
										<th></th>
									</tr>
									<tr>
										<th width="20">ID</th>
										<th width="150">Nama Gallery</th>
										<th >Keterangan</th>
										<th width="100">Gambar</th>
										<th width="70">Aksi</th>
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
						<h2>Form <strong>Gallery</strong> </h2>	

					</header>

					<div>
						
						<!-- widget content -->
						<div class="widget-body">
						
							<form id="formInput" class="form-horizontal iconAfterControl" >
									
								<input type="hidden" name="idGallery" id="idGallery" value="" />
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Kategori Gallery</label>
										<div class="col-xs-9 col-lg-6 selectContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<select class="form-control" name="kat_galeri" id="kat_galeri">
												</select>
											</div>
										</div>
									</div>
								</fieldset>
															
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Nama Gallery</label>
										<div class="col-xs-9 col-lg-6 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="nama_gallery" id="nama_gallery"/>
											</div>
										</div>
									</div>
								</fieldset>						
																	
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Keterangan</label>
										<div class="col-xs-11 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<div class='box-body pad'>
                                                            <textarea id="ket" name="ket" rows="10" cols="80">
                                                            </textarea>    
                                                    </div>
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
													<label id="statusFile">Tidak ada file yang dipilih</label>
													<br>
													<span class="btn btn-success fileinput-button">
														<i class="glyphicon glyphicon-plus"></i>
														<span>Pilih Foto</span>
														<!-- The file input field used as target for the file upload widget -->
														<input id="imgInput" type="file" name="FileInput">
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
<script src="pages/M-Gallery/script-gallery.js" type="text/javascript"></script>

		
