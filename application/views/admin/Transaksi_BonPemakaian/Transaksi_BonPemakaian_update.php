<?php
foreach ($bonpemakaian as $rowh) {
};
?>

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
		<i class="fas fa-university"></i> Update <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="bonpemakaian" name="bonpemakaian" action="<?php echo base_url('admin/Transaksi_BonPemakaian/update_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input type="hidden" name="ID" id="ID" class="form-control" value="<?php echo $rowh->ID ?>">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" value="<?php echo $rowh->NO_BUKTI ?>" readonly>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Notes </label>
						</div>
						<div class="col-md-3">
							<input class="form-control text_input NOTES text_input" id="NOTES" name="NOTES" type="text" value="<?php echo $rowh->NOTES ?>">
						</div>
						<div class="col-md-1">
							<label class="label">Tgl </label>
						</div>
						<div class="col-md-3">
							<input type="text" class="date form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($rowh->TGL, TRUE)); ?>" onclick="select()">
						</div>
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
								<th width="50px">No</th>
								<!-- <th width="75px">Kode</th> -->
								<th width="75px">Rak</th>
								<th width="250px">Uraian</th>
								<th width="75px">Qty</th>
								<th width="100px">Satuan</th>
								<!-- <th width="125px">Kode Golongan</th> -->
								<th width="150px">Keterangan 1</th>
								<th width="125px">Keterangan 2</th>
								<th width="175px">Grup</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 0;
							foreach ($bonpemakaian as $row) :
							?>
								<tr>
									<td><input name="REC[]" id="REC<?php echo $no; ?>" value="<?= $row->REC ?>" type="text" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
									<!-- <td>
										<div class="input-group">
											<select class="js-example-responsive-rak form-control RAK text_input" name="RAK[]" id="RAK<?php echo $no; ?>" onchange="rak(this.id)" required>
												<option value="<?php echo $row->RAK; ?>" selected id="RAK<?php echo $no; ?>"><?php echo $row->RAK; ?></option>
											</select>
										</div>
									</td> -->
									<td><input name="RAK[]" id="RAK<?php echo $no; ?>" value="<?= $row->RAK ?>" type="text" class="form-control RAK text_input"></td>
									<td><input name="NA_BHN[]" id="NA_BHN<?php echo $no; ?>" value="<?= $row->NA_BHN ?>" type="text" class="form-control NA_BHN text_input" readonly></td>
									<td hidden><input name="KD_BHN[]" id="KD_BHN<?php echo $no; ?>" value="<?= $row->KD_BHN ?>" type="text" class="form-control KD_BHN text_input"></td>
									<td><input name="QTY[]" onkeyup="hitung()" id="QTY<?php echo $no; ?>" value="<?php echo number_format($row->QTY, 2, '.', ','); ?>" type="text" class="form-control QTY rightJustified text-primary"></td>
									<td><input name="SATUAN[]" id="SATUAN<?php echo $no; ?>" value="<?= $row->SATUAN ?>" type="text" class="form-control SATUAN text_input"></td>
									<td><input name="KET1[]" id="KET1<?php echo $no; ?>" value="<?= $row->KET1 ?>" type="text" class="form-control KET1 text_input"></td>
									<td>
										<div class="input-group">
											<select class="js-example-responsive-sp_mesin form-control KET2 text_input" name="KET2[]" id="KET2<?php echo $no; ?>" onchange="kd_gol(this.id)" required>
												<option value="<?php echo $row->KET2; ?>" selected id="KET2<?php echo $no; ?>"><?php echo $row->KET2; ?></option>
											</select>
										</div>
									</td>
									<!-- <td><input name="NA_GOL[]" id="NA_GOL<?php echo $no; ?>" value="<?= $row->NA_GOL ?>" type="text" class="form-control NA_GOL text_input" readonly></td> -->
									<td><input name="GRUP[]" id="GRUP<?php echo $no; ?>" value="<?= $row->GRUP ?>" type="text" class="form-control GRUP text_input" readonly></td>
									<td>
										<input name="NO_ID[]" id="NO_ID<?php echo $no; ?>" value="<?= $row->NO_ID ?>" class="form-control" type="hidden">
										<button type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
											<i class="fa fa-fw fa-trash-alt"></i>
										</button>
									</td>
								</tr>
								<?php $no++; ?>
							<?php endforeach; ?>
						</tbody>
						<tfoot>
							<td></td>
							<td></td>
							<td></td>
							<!-- <td></td> -->
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="<?php echo number_format($rowh->TOTAL_QTY, 2, '.', ','); ?>" readonly></td>
							<td></td>
							<!-- <td></td> -->
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<br><br>
		<!--tab-->
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
						<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
						<a type="button" href="javascript:javascript:history.go(-1)" class="btn btn-danger">Cancel</a>
						<a type="text" class="btn btn-light"> </a>
						<button class="btn btn-secondary" type="button" onclick="prev()">
							<< PREV</button>
								<button class="btn btn-secondary" type="button" onclick="next()">NEXT >></button>
					</div>
					<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Savings.done...</span></h4>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#modal_bagian').DataTable({
			dom: "<'row'<'col-md-6'><'col-md-6'>>" + // 
				"<'row'<'col-md-6'f><'col-md-6'l>>" + // peletakan entries, search, dan test_btn
				"<'row'<'col-md-12't>><'row'<'col-md-12'ip>>", // peletakan show dan halaman
			buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
			order: true,
		});
		$('.modal-footer').on('click', '#close', function() {
			$('input[type=search]').val('').keyup(); // this line and next one clear the search dialog
		});
	});
</script>

<script>
	(function() {
		'use strict';
		window.addEventListener('load', function() {
			var forms = document.getElementsByClassName('needs-validation');
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
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
	var idrow = <?php echo $no ?>;

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$(document).ready(function() {

		isiRAK();

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
				// txt = "Batal Hapus";
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

	function hitung() {
		var TOTAL_QTY = 0;
		var total_row = idrow;
		for (i = 0; i < total_row; i++) {

		};
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTY += val;
		});

		if (isNaN(TOTAL_QTY)) TOTAL_QTY = 0;

		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));

		$('#TOTAL_QTY').autoNumeric('update');
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
		// var td10 = x.insertCell(9);
		// var td11 = x.insertCell(10);

		var rak0 = "<div class='input-group'><select class='js-example-responsive-rak form-control RAK0 text_input' name='RAK[]' id=RAK0" + idrow + " onchange='rak(this.id)' onfocusout='hitung()' required></select></div>";

		var rak = rak0;

		var kd_gol0 = "<div class='input-group'><select class='js-example-responsive-sp_mesin form-control KET20 text_input' name='KET2[]' id=KET2" + idrow + " onchange='kd_gol(this.id)' onfocusout='hitung()' required></select></div>";

		var kd_gol = kd_gol0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		// td2.innerHTML = rak;
		td2.innerHTML = "<input name='RAK[]' id=RAK" + idrow + " type='text' class='form-control RAK text_input'>";
		td3.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' readonly> <input hidden name='KD_BHN[]' id=KD_BHN" + idrow + " type='text' class='form-control KD_BHN text_input' readonly>";
		td4.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary' required>";
		td5.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input' >";
		// td9.innerHTML = "<input name='NA_GOL[]' id=NA_GOL0" + idrow + " type='text' class='form-control NA_GOL text_input' readonly>";
		td6.innerHTML = "<input name='KET1[]' id=KET1" + idrow + " type='text' class='form-control KET1 text_input'>";
		td7.innerHTML = kd_gol;
		td8.innerHTML = "<input name='GRUP[]' id=GRUP" + idrow + " type='text' class='form-control GRUP text_input' readonly>";
		td9.innerHTML = "<input type='hidden' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control' value='0'>" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY" + i.toString()).autoNumeric('init', {
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
		select_rak();
		isiRAK();
		select_sp_mesin();
	}

	function hapus() {
		if (idrow > 1) {
			var x = document.getElementById('datatable').deleteRow(idrow);
			idrow--;
			nomor();
		}
	}

	function btVerifikasi() {
		if ($('#PIN').val() == '<?= $this->session->userdata['pin'] ?>') {
			if (confirm("Yakin Posting?")) {
				// document.getElementById("transaksipemesanan").submit();
				window.location.replace("<?php echo base_url('admin/Transaksi_Barang_Masuk/verifikasi_ttd1/' . $rowh->NO_BUKTI) ?>");
			}
		} else {
			alert("Verifikasi Gagal!");
		}
	}
</script>

<script>
	$(document).ready(function() {
		select_rak();
		select_sp_mesin();
	});

	function select_sp_mesin() {
		$('.js-example-responsive-sp_mesin').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_BonPemakaian/getDataAjax_sp_mesin') ?>",
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
			placeholder: 'Pilih Keterangan',
			minimumInputLength: 0,
			templateResult: format_kd_gol,
			templateSelection: formatSelection_kd_gol
		});
	}

	function format_kd_gol(repo_kd_gol) {
		if (repo_kd_gol.loading) {
			return repo_kd_gol.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_kd_gol.KD_GOL);
		return $container;
	}
	var na_gol = '';
	var grup = '';

	function formatSelection_kd_gol(repo_kd_gol) {
		na_gol = repo_kd_gol.NA_GOL;
		grup = repo_kd_gol.GRUP;
		return repo_kd_gol.text;
	}

	function kd_gol(x) {
		var q = x.substring(4, 12);
		$('#NA_GOL' + q).val(na_gol);
		$('#GRUP' + q).val(grup);

		console.log(q);
	}

	function select_rak() {
		$('.js-example-responsive-rak').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_BonPemakaian/getDataAjax_bhn') ?>",
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
			placeholder: 'Pilih Rak',
			minimumInputLength: 0,
			templateResult: format_rak,
			templateSelection: formatSelection_rak
		});
	}

	function format_rak(repo_rak) {
		if (repo_rak.loading) {
			return repo_rak.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_rak.RAK);
		return $container;
	}
	var na_bhn = '';
	var satuan = '';

	function formatSelection_rak(repo_rak) {
		na_bhn = repo_rak.NA_BHN;
		satuan = repo_rak.SATUAN;
		return repo_rak.text;
	}

	function rak(x) {
		var q = x.substring(3, 12);
		$('#NA_BHN' + q).val(na_bhn);
		$('#SATUAN' + q).val(satuan);
		console.log(q);
	}

	function isiRAK() {
		$(".RAK").change(function() {
			// AMBIL ID / NO URUT
			var ID = $(this).attr('id').substring(3, 5);
			// AMBIL ISI RAK
			var VAL = $(this).val();
			console.log(VAL);
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url('index.php/admin/Transaksi_BonPemakaian/isiRAK'); ?>',
				data: {
					VAL: VAL
				},
				dataType: 'json',
				success: function(response) {
					// window.location.replace("<?php echo base_url('index.php/admin/Transaksi_Barang_Masuk/update/'); ?>" + response[0].NO_ID);
					console.log('ADA');
					$("#NA_BHN" + ID).val(response[0].NA_BHN);
					$("#KD_BHN" + ID).val(response[0].KD_BHN);
					$("#SATUAN" + ID).val(response[0].SATUAN);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log('gak ada');
				}
			});
		})
	}
</script>