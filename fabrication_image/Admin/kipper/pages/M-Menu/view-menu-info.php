<div id="content">	
	<section id="viewMenuInfo">

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
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th class="textSearch"><input type="text" class="form-control input-xs" placeholder="Filter" /></th>
										<th></th>
									</tr>
									<tr>
										<th>ID </th>
										<th>Parent_ID</th>
                                        <th>Judul</th>
										<th>URL</th>
										<th>Letak</th>
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
						<h2>Form <strong>Menu</strong> </h2>	

					</header>

					<div>
						
						<!-- widget content -->
						<div class="widget-body">
						
							<form id="formInput" class="form-horizontal iconAfterControl" >
									
								<input type="hidden" name="idMenuI" id="idMenuI" value="" />
																
								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Judul Menu</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="judulmenu" id="judulmenu"/>
											</div>
										</div>
									</div>
								</fieldset>
                                
                                <fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">URL</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input type="text" class="form-control" name="url" id="url"/>
											</div>
										</div>
									</div>
								</fieldset>

								<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Parent</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<select class="form-control" name="pnt" id="pnt">
                                                  <option value="1">Parent 1</option>
                                                  <option value="2">Parent 2</option>
                                                  <option value="3">Parent 3</option>
                                                </select>
											</div>
										</div>
									</div>
								</fieldset>	
		

							<fieldset>
									<div class="form-group">
										<label class="col-xs-1 col-lg-2 control-label">Urut</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<select class="form-control" name="urut" id="urut">
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
										<label class="col-xs-1 col-lg-2 control-label">Letak</label>
										<div class="col-xs-9 col-lg-10 inputGroupContainer">
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<select class="form-control" name="ltk" id="ltk">
                                                  <option value="1">Menu Atas</option>
                                                  <option value="2">Menu Kiri</option>
                                                </select>
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
<script src="pages/M-Menu/script-menu-info.js" type="text/javascript"></script>
		
