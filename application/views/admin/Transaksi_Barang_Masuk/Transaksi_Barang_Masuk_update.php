<?php
foreach ($barang_masuk as $rowh) {
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
	<form id="barangmasuk" name="barangmasuk" action="<?php echo base_url('admin/Transaksi_Barang_Masuk/update_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
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
						<div class="col-md-5"></div>
						<div class="col-md-2">
							<?php
							if ($rowh->VAL == 0)
								echo '<a 
									type="button" 
									class="btn btn-danger btn-center"
								>
									<span style="color: black; font-weight: bold;"></i> Belum Validasi</span>
								</a>';
							else echo '<a 
									type="button"
									class="btn btn-success btn-center" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> Tervalidasi</span>
								</a>';
							?>
						</div>
						<div class="col-md-2">
							<?php
							if ($rowh->VAL == 0)
								echo '<a 
								type="button" 
								class="btn btn-warning btn-center"
								onclick="btVerifikasi()"
								href="#"
							>
							<span style="color: black; font-weight: bold;"><i class="fa fa-upload"></i> Tombol Validasi</span>
						</a>';
							else echo '<a 
							hidden
							type="label"
							class="btn btn-success btn-center" 
								>
							<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> Tervalidasi</span>
						</a>';
							?>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tgl </label>
						</div>
						<div class="col-md-2">
							<input type="text" class="form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($rowh->TGL, TRUE)); ?>" onclick="select()" readonly>
						</div>
						<div class="col-md-1">
							<label class="label">Diterima Dari </label>
						</div>
						<div class="col-md-3">
							<input class="form-control text_input NAMAS" id="NAMAS" name="NAMAS" type="text" value="<?php echo $rowh->NAMAS ?>" readonly>
						</div>
						<div class="col-md-1"></div>
						<div class="col-md-2">
							<?php
							if ($rowh->VAL == 1 && $rowh->OK == 1)
								echo '<a 
								type="button" 
									class="btn btn-warning btn-center"
									>
									<span style="color: black; font-weight: bold;"><i class="fa fa-dropbox"></i> STOK</span>
									</a>';
							if ($rowh->VAL == 1 && $rowh->OK == 2)
								echo '<a 
									type="button"
									class="btn btn-warning btn-center" 
									>
									<span style="color: black; font-weight: bold;"><i class="fa fa-dropbox"></i> NON STOK</span>
									</a>';
							if ($rowh->VAL == 0 && $rowh->OK == 0)
								echo '<a 
									hidden
									type="button"
									class="btn btn-warning btn-center" 
									>
									<span style="color: black; font-weight: bold;"><i class="fa fa-dropbox"></i> NON STOK</span>
									</a>';
							?>
						</div>
						<div class="col-md-1">
							<input <?php if ($rowh->VAL == !0) echo 'hidden'; ?> class="form-control text_input PIN2" id="PIN2" name="PIN2" type="password" maxlength="6" value="" placeholder="PIN ...">
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
								<th width="150px">Kode</th>
								<th width="350px">Nama</th>
								<th width="150px">Rak</th>
								<th width="125px">Qty Beli</th>
								<th width="150px">Satuan Beli</th>
								<th width="125px">Qty</th>
								<th width="150px">Satuan</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 0;
							foreach ($barang_masuk as $row) :
							?>
								<tr>
									<td><input name="REC[]" id="REC<?php echo $no; ?>" value="<?= $row->REC ?>" type="text" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
									<td>
										<div class='input-group'>
											<select <?php if ($rowh->VAL == !0) echo 'disabled'; ?> value="<?= $row->KD_BHN ?>" class="js-example-responsive-kd_bhn form-control KD_BHN" name="KD_BHN[]" id="KD_BHN<?php echo $no; ?>" onchange="kd_bhn(this.id)" required>
												<option value="<?php echo $row->KD_BHN; ?>" selected id="KD_BHN<?php echo $no; ?>"><?php echo $row->KD_BHN; ?></option>
											</select>
										</div>
									</td>
									<td><input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> name="NA_BHN[]" id="NA_BHN<?php echo $no; ?>" value="<?= $row->NA_BHN ?>" type="text" class="form-control NA_BHN text_input" readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> name="RAK[]" id="RAK<?php echo $no; ?>" value="<?= $row->RAK ?>" type="text" class="form-control RAK text_input" readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> name="QTY[]" onkeyup="hitung()" id="QTY<?php echo $no; ?>" value="<?php echo number_format($row->QTY, 2, '.', ','); ?>" type="text" class="form-control QTY rightJustified text-primary" readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> name="SATUAN[]" id="SATUAN<?php echo $no; ?>" value="<?= $row->SATUAN ?>" type="text" class="form-control SATUAN text_input" readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> name="QTY_BL[]" onkeyup="hitung()" id="QTY_BL<?php echo $no; ?>" value="<?php echo number_format($row->QTY_BL, 2, '.', ','); ?>" type="text" class="form-control QTY_BL rightJustified text-primary"></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> name="SAT_BL[]" id="SAT_BL<?php echo $no; ?>" value="<?= $row->SAT_BL ?>" type="text" class="form-control SAT_BL text_input"></td>
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
							<td></td>
							<td><input <?php if ($rowh->VAL == !0) echo 'readonly'; ?> class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="<?php echo number_format($rowh->TOTAL_QTY, 2, '.', ','); ?>" readonly></td>
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
		<br>
		<div class="row">
			<div class="col-xs-9">
				<div class="wells">
					<div class="btn-group cxx">
						<button <?php if ($rowh->VAL == !0) echo 'hidden'; ?> type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
						<a type="button" href="javascript:javascript:history.go(-1)" class="btn btn-danger">Kembali</a>
					</div>
					<a type="text" class="btn btn-light"> </a>
					<button class="btn btn-secondary" type="button" onclick="prev()"><i class="fa fa-angle-double-left"></i> Prev</button>
					<button class="btn btn-secondary" type="button" onclick="next()">Next <i class="fa fa-angle-double-right"></i></button>
				</div>
				<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Savings.done...</span></h4>
			</div>
		</div>
</div>
</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#modal_bl').DataTable({
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
	var idrow = <?php echo $no ?>;

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	$(document).ready(function() {
		$("#TOTAL_QTYPP").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		$("#TOTAL_QTY").autoNumeric('init', {
			aSign: '<?php echo ''; ?>',
			vMin: '-999999999.99'
		});
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTYPP" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
			$("#QTY" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
		}
		$('body').on('click', '.btn-delete', function() {
			var val = $(this).parents("tr").remove();
			idrow--;
			nomor();
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
		var TOTAL_QTYPP = 0;
		var TOTAL_QTY = 0;
		var total_row = idrow;
		for (i = 0; i < total_row; i++) {

		};
		$(".QTYPP").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTYPP += val;
		});
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTY += val;
		});

		if (isNaN(TOTAL_QTYPP)) TOTAL_QTYPP = 0;
		if (isNaN(TOTAL_QTY)) TOTAL_QTY = 0;

		$('#TOTAL_QTYPP').val(numberWithCommas(TOTAL_QTYPP));
		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));

		$('#TOTAL_QTYPP').autoNumeric('update');
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

		var kd_bhn0 = "<div class='input-group'><select class='js-example-responsive-kd_bhn form-control KD_BHN0' name='KD_BHN[]' id=KD_BHN0" + idrow + " onchange='kd_bhn(this.id)' onfocusout='hitung()'></select></div>";

		var kd_bhn = kd_bhn0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = kd_bhn;
		td3.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN' readonly>";
		td4.innerHTML = "<input name='RAK[]' id=RAK" + idrow + " type='text' class='form-control RAK'>";
		td5.innerHTML = "<input name='QTYPP[]' onclick='select()' onkeyup='hitung()' value='0' id=QTYPP" + idrow + " type='text' class='form-control QTYPP rightJustified text-primary'>";
		td6.innerHTML = "<input name='SATUANPP[]' id=SATUANPP" + idrow + " type='text' class='form-control SATUANPP'>";
		td7.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary'>";
		td8.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN'>";
		td9.innerHTML = "<input type='hidden' name='NO_ID[]' id=NO_ID" + idrow + " class='form-control' value='0'>" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTYPP" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
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
		select_kd_bhn();
	}

	function hapus() {
		if (idrow > 1) {
			var x = document.getElementById('datatable').deleteRow(idrow);
			idrow--;
			nomor();
		}
	}

	function btVerifikasi() {
		if ($('#PIN2').val() == '<?= $this->session->userdata['pin'] ?>') {
			if (confirm("Yakin Verifikasi?")) {
				window.location.replace("<?php echo base_url('admin/Transaksi_Barang_Masuk/verifikasi_pin/' . $rowh->ID) ?>");
			}
		} else {
			alert("PIN MU SALAH COOK");
		}
	}
</script>

<script>
	$(document).ready(function() {
		select_kd_bhn();
	});

	function select_kd_bhn() {
		$('.js-example-responsive-kd_bhn').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_Barang_Masuk/getDataAjax_bhn') ?>",
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
			placeholder: 'Pilih Kode',
			minimumInputLength: 0,
			templateResult: format_kd_bhn,
			templateSelection: formatSelection_kd_bhn
		});
	}

	function format_kd_bhn(repo_kd_bhn) {
		if (repo_kd_bhn.loading) {
			return repo_kd_bhn.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix'>" +
			"<div class='select2-result-repository__title'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_kd_bhn.KD_BHN);
		return $container;
	}
	var na_bhn = '';
	var satuan = '';
	var rak = '';

	function formatSelection_kd_bhn(repo_kd_bhn) {
		na_bhn = repo_kd_bhn.NA_BHN;
		satuan = repo_kd_bhn.SATUAN;
		rak = repo_kd_bhn.RAK;
		return repo_kd_bhn.text;
	}

	function kd_bhn(x) {
		var q = x.substring(6, 12);
		$('#NA_BHN' + q).val(na_bhn);
		$('#SATUAN' + q).val(satuan);
		$('#RAK' + q).val(rak);
		console.log(q);
	}
</script>

<script>
	function prev() {
		var ID = $('#ID').val();
		$.ajax({
			type: 'get',
			url: '<?php echo base_url('index.php/admin/Transaksi_Barang_Masuk/prev'); ?>',
			data: {
				ID: ID
			},
			dataType: 'json',
			success: function(response) {
				window.location.replace("<?php echo base_url('index.php/admin/Transaksi_Barang_Masuk/update/'); ?>" + response[0].NO_ID);
				// console.log('test');
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				// console.log('error');
			}
		});
	}

	function next() {
		var ID = $('#ID').val();
		$.ajax({
			type: 'get',
			url: '<?php echo base_url('index.php/admin/Transaksi_Barang_Masuk/next'); ?>',
			data: {
				ID: ID
			},
			dataType: 'json',
			success: function(response) {
				window.location.replace("<?php echo base_url('index.php/admin/Transaksi_Barang_Masuk/update/'); ?>" + response[0].NO_ID);
				console.log(response[0].NO_ID);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				// console.log('error');
			}
		});
	}
</script>