<div id="content">	
	<section id="viewInformasi">

		<section id="widget-grid" class="">
			
			<div id="list">
			
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-greenLight" data-widget-editbutton="false">
			
					<header>
						<h2>List <strong>Informasi</strong></h2>						
					</header>

					<div>
						
						<!-- widget content -->
						<div class="widget-body">
							<!--
							<div class="widget-body-toolbar">
								<button type="button" class="btn btn-primary btn-sm" id="btnAdd">Tambah Data</button>
							</div>
							-->
							<div class="table-responsive">
							  <table class="table table-striped table-bordered table-condensed table-hover" id="tabelData" width="100%">
								
								<thead>
									<tr>
										<th></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th></th>
									</tr>
									<tr>
										<th>ID </th>
										<th width="150">Judul Informasi</th>
                                        <th>Isi</th>
                                        <th width="100">Gambar</th>
                                        <th width="100">Tgl. Posting</th>
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
						<h2>Form <strong>Informasi</strong> </h2>	

					</header>

					<div>
						
						<!-- widget content -->
						<div class="widget-body">
						
							<form id="formInput" class="form-horizontal iconAfterControl" >
									
								<input type="hidden" name="idInformasi" id="idInformasi" value="" />
																
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Judul Informasi</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="judulIn" id="judulIn"/>
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Posisi Foto</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<select class="form-control" name="pf" id="pf">
                                                  <option value="1">Foto Kiri</option>
                                                  <option value="2">Foto Kanan</option>
                                                  <option value="3">Tanpa Foto</option>
                                                </select>
											</div>
										</div>
									</div>
								</fieldset>	

								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Isi</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<!--<input type="text" class="form-control" name="ket" id="ket"/>-->
                                                <textarea class="form-control" name="isi" id="isi" cols="10" rows="10"></textarea>
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
<script src="pages/M-Informasi/script-informasi.js" type="text/javascript"></script>
		
