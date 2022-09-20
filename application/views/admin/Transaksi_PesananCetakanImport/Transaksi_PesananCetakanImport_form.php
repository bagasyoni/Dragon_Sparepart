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
		<i class="fas fa-university"></i> Input Pesanan Cetakan Import
	</div>
	<?php echo form_open_multipart('admin/Transaksi_PesananCetakanImport/input_aksi'); ?>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" placeholder='<?php echo $this->session->userdata['bukti']; ?>' readonly>
						</div>
						<div class="col-md-2">
							<input type="text" class="date form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {																																echo $_POST["TGL"];																													} else echo date('d-m-Y'); ?>" onclick="select()">
						</div>
						<div class="col-md-1">
							<label class="label">DR </label>
						</div>
						<div class="col-md-1">
						<select value="" class="js-example-responsive-dragon form-control DEVISI" name="DEVISI" id="DEVISI" onchange="dragon(this.id)" onfocusout="hitung()" required></select>
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
							<label class="label">Size </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input SIZE" id="SIZE" name="SIZE" type="text">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Jumlah </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input JUMLAH" id="JUMLAH" name="JUMLAH" type="text" required>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Keterangan </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NOTES" id="NOTES" name="NOTES" type="text" required>
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-1">
							<label class="label">Tipe Cetakan </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input TIPE_CETAK" id="TIPE_CETAK" name="TIPE_CETAK" type="text">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">M Lasting </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input M_LASTING" id="M_LASTING" name="M_LASTING" type="text" required>
						</div>
						<div class="col-md-2">
						</div>
						<div class="col-md-1">
							<label class="label">Pilih Proses </label>
						</div>
						<div class="col-md-2">
							<select class="form-control text_input PROSES" id="PROSES" name="PROSES" type="text">
								<option value="CETAKAN">CETAKAN</option>
								<option value="MATRAS">MATRAS</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tanggal Diminta </label>
						</div>
						<div class="col-md-2">
							<input type="text" class="date form-control TGL_DIMINTA text_input" id="TGL_DIMINTA" name="TGL_DIMINTA" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {																																echo $_POST["TGL"];																													} else echo date('d-m-Y'); ?>" onclick="select()">
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
						<input type="file" name="GAMBAR2" id="GAMBAR2" accept="image/png, image/jpeg, image/jpg, image/bmp">
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
						<input type="file" name="GAMBAR3" id="GAMBAR3" accept="image/png, image/jpeg, image/jpg, image/bmp">
						</div>
					</div>
				</div>
			</div>
		</div>
		<br><br>
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
	<?php echo form_close(); ?>
	<!-- </form> -->
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

		var no_bon0 = "<div class='input-group'><select class='js-example-responsive-no_bon form-control NO_BON text_input' name='NO_BON[]' id=NO_BON" + idrow + " onchange='no_bon(this.id)' onfocusout='hitung()' required></select></div>";
		var kd_bhn0 = "<div class='input-group'><select class='js-example-responsive-kd_bhn form-control KD_BHN text_input' name='KD_BHN[]' id=KD_BHN" + idrow + " onchange='kd_bhn(this.id)' onfocusout='hitung()' required></select></div>";
		
		var no_bon = no_bon0;
		var kd_bhn = kd_bhn0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = "<input name='SIZE[]' id=SIZE" + idrow + " type='text' class='form-control SIZE text_input' required>";
		td3.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input' required>";
		td4.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary' required>";
		td5.innerHTML = "<input type='hidden' value='0' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'>" +
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
				url: "<?= base_url('admin/Transaksi_PesananCetakanImport/getDataAjax_dragon') ?>",
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