<style>
	#myInput {
		background-image: url('<?php echo base_url() ?>assets/img/search-icon-blue.png');
		background-position: 10px 12px;
		background-repeat: no-repeat;
		width: 100%;
		padding: 12px 20px 12px 40px;
		border: 1px solid #ddd;
		margin-bottom: 12px;
	}

	#myTable {
		border-collapse: collapse;
		width: 100%;
		border: 1px solid #ddd;
	}

	#myTable th,
	#myTable td {
		text-align: left;
		padding: 5px;
	}

	#myTable tr {
		border-bottom: 1px solid #ddd;
	}

	#myTable tr.header,
	#myTable tr:hover {
		background-color: #f1f1f1;
	}

	input[type=text]:focus {
		width: 100%;
	}

	.text_input {
		color: black;
		text-transform: uppercase;
	}

	table {
		table-layout: fixed;
	}

	table th {
		color: black;
		text-align: center;
	}

	table td {
		overflow: hidden;
	}

	.label {
		color: black;
		font-weight: bold;
	}

	.rightJustified {
		text-align: right;
	}

	.total {
		font-weight: bold;
		color: blue;
	}

	.form-control {
		font-size: small;
	}

	.bodycontainer {
		/* width: 1000px; */
		max-height: 500px;
		margin: 0;
		overflow-y: auto;
	}

	#datatable td {
		padding: 2px !important;
		vertical-align: middle;
	}

	.alert-container {
		background-color: #00b386;
		color: black;
		font-weight: bolder;
	}

	.table-scrollable {
		margin: 0;
		padding: 0;
	}

	.modal-bodys {
		max-height: 250px;
		overflow-y: auto;
	}

	.select2-dropdown {
		width: 500px !important;
	}

	/* .container { text-align: center; vertical-align: middle;} */
	.checkbox_container {
		width: 25px;
		height: 25px;
	}

	td input[type="checkbox"] {
		float: left;
		margin: 0 auto;
		width: 100%;
	}

	.text_input {
		font-size: small;
		color: black;
	}
</style>

<div class="container-fluid">
	<br>
	<div class="alert alert-success alert-container" role="alert">
		<i class="fas fa-university"></i> Input Pesanan LBBA
	</div>
	<form id="cnc" name="cnc" action="<?php echo base_url('admin/Transaksi_PesananLBBA/input_aksi'); ?>" class="form-horizontal needs-validation" method="post" enctype="multipart/form-data" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" value='<?php echo $this->session->userdata['bukti']; ?>' readonly>
						</div>
						<div class="col-md-2">
							<input type="text" class="date form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {																																echo $_POST["TGL"];																													} else echo date('d-m-Y'); ?>" onclick="select()">
						</div>
						<div class="col-md-1">
							<label class="label">DR </label>
						</div>
						<div class="col-md-2">
							<select class="form-control text_input DEVISI" id="DEVISI" name="DEVISI" type="text">
								<?php if ($this->session->userdata['dr']=='RND1'){ // lutfi ayu //DR1
								echo '
								<option value=""></option>
								<option value="RD1">PUMA</option>
								<option value="RD2">CANVAS</option>
								';
								}
								else if ($this->session->userdata['dr']=='RND3') { //prayit indri //DR3
								echo '
								<option value=""></option>
								<option value="RD7">INJECT DR3</option>
								';
								}
								else if ($this->session->userdata['dr']=='RND4') { // mulyadi muklisun //DR2
								echo '
								<option value=""></option>
								<option value="RD5">CEMENTING</option>
								<option value="RD8">INJECT DR4</option>
								';
								}
								else if ($this->session->userdata['dr']=='RNDAB') { // toni ardhia  //DR2
								echo '
								<option value=""></option>
								<option value="RD3">AIR BLOW</option>
								<option value="RD4">PHYLON</option>
								';
								}
								else if ($this->session->userdata['dr']=='RNDVC') { // yudi yona //DR2
								echo '
								<option value=""></option>
								<option value="RD6">VULCANIZED</option>
								';
								}
								?>
							</select>
							<!-- <input class="form-control text_input DEVISI" id="DEVISI" name="DEVISI" type="text"> -->
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Article </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input ARTICLE" id="ARTICLE" name="ARTICLE" type="text" required>
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-1">
							<label class="label">Pesan </label>
						</div>
						<div class="col-md-2">
							<select class="form-control text_input PESAN" id="PESAN" name="PESAN" type="text">
								<option value=""></option>
								<option value="BARU">BARU</option>
								<option value="PERBAIKAN">PERBAIKAN</option>
							</select>
							<!-- <input class="form-control text_input PESAN" id="PESAN" name="PESAN" type="text"> -->
						</div>
						<div class="col-md-2">
							<select class="form-control text_input JO" id="JO" name="JO" type="text">
								<option value=""></option>
								<option value="MRL">MRL</option>
								<option value="MRE">MRE</option>
							</select>
							<!-- <input class="form-control text_input JO" id="JO" name="JO" type="text"> -->
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tanggal Diminta </label>
						</div>
						<div class="col-md-2">
							<input type="text" class="date form-control TGL_DIMINTA text_input" id="TGL_DIMINTA" name="TGL_DIMINTA" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {echo $_POST["TGL"];} else echo date('d-m-Y'); ?>" onclick="select()">
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-1">
							<label class="label">Flag </label>
						</div>
						<div class="col-md-2">
							<select class="form-control text_input FLAG3" id="FLAG3" name="FLAG3" type="text">
								<option value=""></option>
								<option value="CNC">CNC</option>
								<option value="PBL">PBL</option>
							</select>
							<!-- <input class="form-control text_input FLAG3" id="FLAG3" name="FLAG3" type="text"> -->
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-4">
							<label class="label">Gambar Cetakan Maksimal 1 MB ekstensi yang diperbolehkan .jpg .png .jpeg .bmp </label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-2">
						<input type="file" name="GAMBAR1" id="GAMBAR1" accept="image/png, image/jpeg, image/jpg, image/bmp">
						<img style="display:none;width:150px;height:150px;" id="GAMBAR1PREVIEW" src="#"  />
						<script>
							var loadFile = function(event) {
							var output = document.getElementById('GAMBAR1PREVIEW');
							output.src = URL.createObjectURL(event.target.files[0]);
							output.onload = function() {
								URL.revokeObjectURL(output.src) // free memory
							}
							
							$("#GAMBAR1PREVIEW").show();
							};
						</script>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive scrollable">
					<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
						<thead>
							<tr>
								<th width="40px">No</th>
								<th width="150px">Komponen</th>
								<th width="100px">Kode Bahan</th>
								<th width="100px">Tgl Minta</th>
								<th width="150px">Warna</th>
								<th width="120px">Serian</th>
								<th width="75px">Qty</th>
								<th width="75px">Satuan</th>
								<th width="175px">Keterangan</th>
								<th width="175px">Gambar</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input name="REC[]" id="REC0" type="text" value="1" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
								<td><input name="NA_BHN[]" id="NA_BHN0" type="text" class="form-control NA_BHN text_input" required></td>
								<td><input name="KD_BHN[]" id="KD_BHN0" type="text" class="form-control KD_BHN text_input" required></td>
								<td><input type="text" class="date form-control TGL_DIMINTAX text_input" id="TGL_DIMINTAX0" name="TGL_DIMINTAX[]" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {echo $_POST["TGL"];} else echo date('d-m-Y'); ?>" onclick="select()">
								<td><input name="WARNA[]" id="WARNA0" type="text" class="form-control WARNA text_input"></td>
								<td><input name="SERI[]" id="SERI0" type="text" class="form-control SERI text_input"></td>
								<td><input name="QTY[]" onclick="select()" onkeyup="hitung()" value="0" id="QTY0" type="text" class="form-control QTY rightJustified text-primary" required></td>
								<td><input name="SATUAN[]" id="SATUAN0" type="text" class="form-control SATUAN text_input"></td>
								<td><input name="KET[]" id="KET0" type="text" class="form-control KET text_input"></td>
								<td><input type="file" name="GAMBAR1X0" id="GAMBAR1X0" accept="image/png, image/jpeg, image/jpg, image/bmp">
								<img class="GAMBAR1PREVIEW2" src="#" style="display:none;width:150px;height:150px;" />
								<script>
									$("body").on("change", ".GAMBAR1X0", function(event)
									{
										var output = $(this).parents("tr").find(".GAMBAR1PREVIEW2");
										
										output.attr("src", URL.createObjectURL(event.target.files[0]));
										
										output.onload = function() {
											URL.revokeObjectURL(output.src) // free memory
										}
										output.show();
									});
								</script>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="0" readonly></td>
							<td></td>
							<td></td>
							<td></td>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<br><br>
		<div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-xs-9">
				<div class="wells">
					<div class="btn-group cxx">
						<button type="submit" onclick="chekbox()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
						<a type="button" href="javascript:javascript:history.go(-1)" class="btn btn-danger">Cancel</a>
					</div>
					<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Savings.done...</span></h4>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	(function() {
		'use strict';
		window.addEventListener('load', function() {
			var forms = document.getElementsByClassName('needs-validation');
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
						alert("Data Belum Lengkap");
						event.preventDefault();
						event.stopPropagation();
					} else {
						$(this).submit(function() {
							return false;
						});
					}
					form.classList.add('was-validated');
				}, false);
			});
		}, false);
	})();
	var target;
	var idrow = 1;

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$(document).ready(function() {
		$("#TOTAL_QTY").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
			$("#HARGA" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
			$("#TOTAL" + i.toString()).autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		}
		$('body').on('click', '.btn-delete', function() {
			var r = confirm("Yakin dihapus?");
			if (r == true) {
				// txt = "Dihapus";
				if (idrow > 1) {
					var val = $(this).parents("tr").remove();
					idrow--;
					nomor();
				}
			} else {
			}
		});
		$('input[type="checkbox"]').on('change', function() {
			this.value ^= 1;
			console.log(this.value)
		});
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
	});

	function nomor() {
		var i = 1;
		$(".REC").each(function() {
			$(this).val(i++);
		});
		hitung();
	}
//backup
	function hitung() {
		var TOTAL_QTY = 0;
		var TOTAL = 0;
		var total_row = idrow;
		for (i = 0; i < total_row; i++) {
			var qty = parseFloat($('#QTY' + i).val().replace(/,/g, ''));
		};
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTY += val;
		});

		if (isNaN(TOTAL_QTY)) TOTAL_QTY = 0;
		if (isNaN(TOTAL)) TOTAL = 0;

		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));
		$('#TOTAL').val(numberWithCommas(TOTAL));

		$('#TOTAL_QTY').autoNumeric('update');
		$('#TOTAL').autoNumeric('update');
	}

	function tambah() {

		var x = document.getElementById('datatable').insertRow(idrow + 1);
		var td1 = x.insertCell(0);
		var td2 = x.insertCell(1);
		var td3 = x.insertCell(2);
		var td4 = x.insertCell(3);
		var td5 = x.insertCell(4);
		var td6 = x.insertCell(5);
		var td7 = x.insertCell(6);
		var td8 = x.insertCell(7);
		var td9 = x.insertCell(8);
		var td10 = x.insertCell(9);
		var td11 = x.insertCell(10);

		var no_bon0 = "<div class='input-group'><select class='js-example-responsive-no_bon form-control NO_BON text_input' name='NO_BON[]' id=NO_BON" + idrow + " onchange='no_bon(this.id)' onfocusout='hitung()' required></select></div>";
		var kd_bhn0 = "<div class='input-group'><select class='js-example-responsive-kd_bhn form-control KD_BHN text_input' name='KD_BHN[]' id=KD_BHN" + idrow + " onchange='kd_bhn(this.id)' onfocusout='hitung()' required></select></div>";
		
		var no_bon = no_bon0;
		var kd_bhn = kd_bhn0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' required>";
		td3.innerHTML = "<input name='KD_BHN[]' id=KD_BHN" + idrow + " type='text' class='form-control KD_BHN text_input' required>";
		td4.innerHTML = "<input type='text' class='date form-control TGL_DIMINTA text_input' id='TGL_DIMINTAX" + idrow + "' name='TGL_DIMINTAX[]' data-date-format='dd-mm-yyyy' value='<?php if (isset($_POST["tampilkan"])) {echo $_POST["TGL"];} else echo date('d-m-Y'); ?>' onclick='select()'>";
		td5.innerHTML = "<input name='WARNA[]' id=WARNA" + idrow + " type='text' class='form-control WARNA text_input' required>";
		td6.innerHTML = "<input name='SERI[]' id=SERI" + idrow + " type='text' class='form-control SERI text_input'>";
		td7.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary' required>";
		td8.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input'>";
		td9.innerHTML = "<input name='KET[]' id=KET" + idrow + " type='text' class='form-control KET text_input'>";
		td10.innerHTML = "<input type='file' class='GAMBAR1X0' name=GAMBAR1X" + idrow + " id=GAMBAR1X" + idrow + " accept='image/png, image/jpeg, image/jpg, image/bmp'><img class='GAMBAR1PREVIEW2' src='#' style='display:none;width:150px;height:150px;' />";
		td11.innerHTML = "<input type='hidden' value='0' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'>" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
			$("#HARGA" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
			$("#TOTAL" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
		}
		idrow++;
		nomor();
		$(".ronly").on('keydown paste', function(e) {
			e.preventDefault();
			e.currentTarget.blur();
		});
		$('input[type="checkbox"]').on('change', function() {
			this.value ^= 1;
			console.log(this.value)
		});
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
		select_no_bon();
		select_kd_bhn();
	}

	function hapus() {
		if (idrow > 1) {
			var x = document.getElementById('datatable').deleteRow(idrow);
			idrow--;
			nomor();
		}
	}
</script>

<script>
	$(document).ready(function() {
		select_dragon();
	});
	function select_dragon() {
		$('.js-example-responsive-dragon').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_PesananLBBA/getDataAjax_dragon') ?>",
				dataType: "json",
				type: "post",
				delay: 10,
				data: function(params) {
					return {
						search: params.term,
						page: params.page
					}
				},
				processResults: function(data, params) {
					params.page = params.page || 1;
					return {
						results: data.items,
						pagination: {
							more: data.total_count
						}
					};
				},
				cache: true
			},
			placeholder: 'Pilih Dragon',
			minimumInputLength: 0,
			templateResult: format_dragon,
			templateSelection: formatSelection_dragon
		});
	}

	function format_dragon(repo_dragon) {
		if (repo_dragon.loading) {
			return repo_dragon.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_dragon.DR);
		return $container;
	}

	function formatSelection_dragon(repo_dragon) {
		return repo_dragon.text;
	}

	function dragon(x) {
		var q = x.substring(2, 10);
	}
</script>