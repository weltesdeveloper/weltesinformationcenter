<div id="content">	
	<section id="viewMenu">

		
	
		<section id="widget-grid" class="">
			
			<div id="list">
			
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-greenLight" data-widget-editbutton="false">
			
					<header>
						<h2>List <strong>Menu</strong></h2>						
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
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter No" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter Nama" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter Nama" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter Nama" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter Nama" /></th>
										<th></th>
									</tr>
									<tr>
										<th>ID</th>
										<th>Sub Level</th>
										<th>Nama Menu</th>
										<th>Url</th>
										<th>Letak Menu</th>
										<th>Urutan Menu</th>
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
						<h2>Form <strong>Menu</strong></h2>						
					</header>

					<div>
						
						<!-- widget content -->
						<div class="widget-body">
							
							<form id="formInput" class="form-horizontal iconAfterControl" >
								
								<input type="hidden" class="form-control" name="idMenu" id="idMenu" value=""/>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-2 col-lg-3 control-label">Sub Level</label>
										<div class="col-xs-9 col-lg-6 selectContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<select class="form-control" name="subLevel" id="subLevel">
													<option value="">- Pilih Level -</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5</option>
													<option value="6">6</option>
												</select>
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-2 col-lg-3 control-label">Menu Parent</label>
										<div class="col-xs-9 col-lg-6 selectContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<select class="form-control" name="menuParent" id="menuParent">
													<option value="0">- Pilih Parent -</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
												</select>
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-2 col-lg-3 control-label">Nama Menu</label>
										<div class="col-xs-9 col-lg-6 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="namaMenu" id="namaMenu" />
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-2 col-lg-3 control-label">URL</label>
										<div class="col-xs-9 col-lg-6 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="url" id="url"/>
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-2 col-lg-3 control-label">Icon</label>
										<div class="col-xs-9 col-lg-6 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="icon" id="icon" value="fa fa-home" />
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-2 col-lg-3 control-label">Letak Menu</label>
										<div class="col-xs-9 col-lg-6 selectContainer">
											<div class="input-group">
												<label class="radio radio-inline"><input type="radio" name="letakMenu" id="kiri" value="kiri">Kiri</label>
												<label class="radio radio-inline"><input type="radio" name="letakMenu" id="kanan" value="kanan">Kanan</label>
											</div>
										</div>
									</div>
								</fieldset>
								
								<fieldset>
									<div class="form-group">
										<label class="col-xs-2 col-lg-3 control-label">Urutan Menu</label>
										<div class="col-xs-9 col-lg-6 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="urutanMenu" id="urutanMenu" />
											</div>
										</div>
									</div>
								</fieldset>

								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-default" type="submit" id="btnSubmit">
												<i class="fa fa-eye"></i>
												Validate
											</button>
											<button class="btn btn-default" type="reset" id="btnCancel">
												<i class="fa fa-eye"></i>
												Batal
											</button>
										</div>
									</div>
								</div>
								
								
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
<script src="pages/W-menu/script-menu.js" type="text/javascript"></script>
		
