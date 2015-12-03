<div id="content">	
	<section id="view_kat_gallery">
		<section id="widget-grid" class="">			
			<div id="list">			
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-greenLight" data-widget-editbutton="false">
			
					<header>
						<h2>List <strong>Kategori Gallery</strong></h2>						
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
										<th></th>
									</tr>
									<tr>
										<th  width="70">ID </th>
										<th>Nama Kategori Gallery</th>
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
						<h2>Form <strong>Kategori Gallery</strong> </h2>	
					</header>

					<div>						
						<!-- widget content -->
						<div class="widget-body">
						
							<form id="formInput" class="form-horizontal iconAfterControl" >
									
								<input type="hidden" name="id" id="id" value="" />
																
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Nama Kategori Gallery</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="nama_kat" id="nama_kat"/>
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
		
		
<!-- TARUH SCRIPT DI SINI -->
<script src="pages/M-Kat-Gallery/script-kat-gallery.js" type="text/javascript"></script>
		
