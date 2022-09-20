<?php
foreach ($pemesanan as $rowh) {
};
?>

<script src="https://unpkg.com/@develoka/angka-terbilang-js/index.min.js"></script>

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

	hr {
		border: 0;
		clear: both;
		display: block;
		width: 100%;
		background-color: black;
		height: 1px;
	}

	*/ .text_input {
		font-size: small;
		color: black;
	}
</style>

<div class="container-fluid">
	<br>
	<div class="alert alert-success alert-container" role="alert">
		<i class="fas fa-university"></i> Update <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="pemesanan" name="pemesanan" action="<?php echo base_url('admin/Transaksi_Pemesanan/update_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input type="hidden" name="ID" class="form-control" value="<?php echo $rowh->ID ?>">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" value="<?php echo $rowh->NO_BUKTI ?>" readonly>
						</div>
						<div class="col-md-2"></div>
						<div class="col-md-2">
							<label class="label"><strong style="color: red;">*</strong> Biru = Bisa tanda tangan </label>
						</div>
						<div class="col-md-2">
							<label class="label"><strong style="color: red;">*</strong> Kuning = Belum tanda tangan </label>
						</div>
						<div class="col-md-2">
							<label class="label"><strong style="color: red;">*</strong> Hijau = Sudah tanda tangan </label>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tanggal </label>
						</div>
						<div class="col-md-2">
							<input <?php if ($rowh->TTD3 == !0) echo 'class="form-control TGL text_input" readonly'; ?> type="text" class="date form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($rowh->TGL, TRUE)); ?>" onclick="select()">
						</div>
						<div class="col-md-1">
							<label class="label">Ket </label>
						</div>
						<div class="col-md-3">
							<input <?php if ($rowh->TTD3 == !0) echo 'readonly'; ?> class="form-control text_input NOTES" id="NOTES" name="NOTES" type="text" value="<?php echo $rowh->NOTES ?>">
						</div>
					</div>
				</div>
				<hr>
				<div class="col-md-12" style="justify-content: center;">
					<div class="form-group row" style="justify-content: center;">
						<div class="col-md-2">
							<?php
							if ($rowh->TTD1 == 0)
								echo '<a 
										type="button" 
										class="btn btn-primary" 
										onclick="btVerifikasi()" 
									>
										<span style="color: white; font-weight: bold;"><i class="fa fa-upload"></i> VALIDASI</span>
									</a>';
							else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> ADMIN</span>
								</a>';
							?>
						</div>
						<div class="col-md-2">
							<?php
							if ($rowh->TTD2 == 0)
								echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> KABAG</span>
									</a>';
							else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> KABAG</span>
								</a>';
							?>
						</div>
						<div class="col-md-2">
							<?php
							if ($rowh->TTD3 == 0)
								echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> FM</span>
									</a>';
							else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> FM</span>
								</a>';
							?>
						</div>
						<div class="col-md-2">
							<?php
							if ($rowh->TTD4 == 0)
								echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> PURCH</span>
									</a>';
							else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> PURCH</span>
								</a>';
							?>
						</div>
						<div class="col-md-2">
							<?php
							if ($rowh->TTD5 == 0)
								echo '<a 
										type="button" 
										class="btn btn-warning" 
									>
										<span style="color: black; font-weight: bold;"><i class="fa fa-times-circle-o"></i> DIREKT</span>
									</a>';
							else echo '<a 
									type="button" 
									class="btn btn-success" 
								>
									<span style="color: black; font-weight: bold;"><i class="fa fa-check"></i> DIREKT</span>
								</a>';
							?>
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
								<th width="150px">Bon</th>
								<th width="75px">Kode</th>
								<th width="175px">Uraian</th>
								<th width="175	px">Ket Barang</th>
								<th width="50px">Qty</th>
								<th width="100px">Bilangan</th>
								<th width="75px">Satuan</th>
								<th width="100px">Devisi</th>
								<th width="225px">Keterangan</th>
								<th width="100px">Tgl Diminta</th>
								<th width="50px">Sisa</th>
								<th width="90px">Urgent</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 0;
							foreach ($pemesanan as $row) :
							?>
								<tr>
									<td><input name="REC[]" id="REC<?php echo $no; ?>" value="<?= $row->REC ?>" type="text" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
									<!-- <td>
									<div class='input-group'>
										<select class="js-example-responsive-no_bon form-control NO_BON" name="NO_BON[]" id="NO_BON<?php echo $no; ?>" value="<?= $row->NO_BON ?>" onchange="no_bon(this.id)" onfocusout="hitung()"></select>
									</div>
									</td>
									<td>
									<div class='input-group'>
										<select class="js-example-responsive-kd_bhn form-control KD_BHN text_input" name="KD_BHN[]" id="KD_BHN<?php echo $no; ?>" value="<?= $row->KD_BHN ?>" onchange="kd_bhn(this.id)" onfocusout="hitung()"></select>
									</div>
									</td> -->
									<td><input name="NO_BON[]" id="NO_BON<?php echo $no; ?>" value="<?= $row->NO_BON ?>" type="text" class="form-control NO_BON text_input" readonly></td>
									<td><input name="KD_BHN[]" id="KD_BHN<?php echo $no; ?>" value="<?= $row->KD_BHN ?>" type="text" class="form-control KD_BHN text_input" readonly></td>
									<td><input name="NA_BHN[]" id="NA_BHN<?php echo $no; ?>" value="<?= $row->NA_BHN ?>" type="text" class="form-control NA_BHN text_input" readonly></td>
									<td><input <?php if ($rowh->TTD3 == !0) echo 'readonly'; ?> name="TIPE[]" id="TIPE<?php echo $no; ?>" value="<?= $row->TIPE ?>" type="text" class="form-control TIPE text_input"></td>
									<td><input <?php if ($rowh->TTD3 == !0) echo 'readonly'; ?> name="QTY[]" onclick="select()" onkeyup="hitung()" id="QTY<?php echo $no; ?>" value="<?php echo number_format($row->QTY, 2, '.', ','); ?>" type="text" class="form-control QTY rightJustified text-primary"></td>
									<td><input name="BILANGAN[]" id="BILANGAN<?php echo $no; ?>" value="<?= $row->BILANGAN ?>" type="text" class="form-control BILANGAN text_input" readonly></td>
									<td><input <?php if ($rowh->TTD3 == !0) echo 'readonly'; ?> name="SATUAN[]" id="SATUAN<?php echo $no; ?>" value="<?= $row->SATUAN ?>" type="text" class="form-control SATUAN text_input" readonly></td>
									<td><input <?php if ($rowh->TTD3 == !0) echo 'readonly'; ?> name="DEVISI[]" id="DEVISI<?php echo $no; ?>" value="<?= $row->DEVISI ?>" type="text" class="form-control DEVISI text_input"></td>
									<td><input <?php if ($rowh->TTD3 == !0) echo 'readonly'; ?> name="KET[]" id="KET<?php echo $no; ?>" value="<?= $row->KET ?>" type="text" class="form-control KET text_input"></td>
									<td>
										<input <?php if ($rowh->TTD3 == !0) echo 'class="form-control TGL_DIMINTA text_input" readonly'; ?> name="TGL_DIMINTA[]" id="TGL_DIMINTA<?php echo $no; ?>" type="text" class="date form-control text_input" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($row->TGL_DIMINTA, TRUE)); ?>" onclick="select()">
									</td>
									<td><input name="SISABON[]" onkeyup="hitung()" id="SISABON<?php echo $no; ?>" value="<?php echo number_format($row->SISABON, 2, '.', ','); ?>" type="text" class="form-control SISABON rightJustified text-primary" readonly></td>
									<td>
										<input <?php
												if ($row->URGENT != "0") echo 'checked'; ?> name="URGENT[]" id="URGENT<?php echo $no; ?>" type="checkbox" value="<?= $row->URGENT ?>" class="checkbox_container URGENT">
									</td>
									<td>
										<input name="NO_ID[]" id="NO_ID<?php echo $no; ?>" value="<?= $row->NO_ID ?>" class="form-control" type="hidden">
										<button <?php if ($rowh->TTD3 == !0) echo 'style="visibility: hidden;"'; ?> type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
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
							<td></td>
							<td><input class="form-control TOTAL_QTY rightJustified text-primary font-weight-bold" id="TOTAL_QTY" name="TOTAL_QTY" value="<?php echo number_format($rowh->TOTAL_QTY, 2, '.', ','); ?>" readonly></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<!-- <td></td> -->
							<td></td>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<br><br>
		<!--tab-->
		<div class="col-md-12" <?php if ($rowh->TTD3 == !0) echo 'style="visibility: hidden;"'; ?>>
			<div class="form-group row">
				<div class="col-md-1">
					<button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
				</div>
			</div>
		</div>
		<br>
		<!-- <div class="col-md-12" <?php if ($rowh->TTD3 == !0) echo 'style="visibility: hidden;"'; ?>> -->
		<div class="col-md-12">
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

<script type="text/javascript">
	$(document).ready(function() {
		$('#modal_beli').DataTable({
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
			$("#SISABON" + i.toString()).autoNumeric('init', {
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
			var qty = parseFloat($('#QTY' + i).val().replace(/,/g, ''));
			var sisabon = parseFloat($('#SISABON' + i).val().replace(/,/g, ''));

			if (qty > sisabon) {
				alert("Qty tidak boleh lebih besar dari Sisa Bon");
				$('#QTY' + i).val(0);
				console.log('TIDAK OK !!!')
			} else {
				console.log('OK !!!')
			}
			$('#BILANGAN' + i).val(angkaTerbilang(qty));
			// console.log(angkaTerbilang('Terbilang :'+qty));
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

	function chekbox() {
		$(".URGENT").each(function() {
			if ($(this).is(":checked") == true) {
				$(this).attr('value', '1');
			} else {
				$(this).prop('checked', true);
				$(this).attr('value', '0');
			}
		});
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
		var td12 = x.insertCell(11);
		var td13 = x.insertCell(12);
		var td14 = x.insertCell(13);

		var no_bon0 = "<div class='input-group'><select class='js-example-responsive-no_bon form-control NO_BON text_input' name='NO_BON[]' id=NO_BON" + idrow + " onchange='no_bon(this.id)' onfocusout='hitung()'></select></div>";
		var kd_bhn0 = "<div class='input-group'><select class='js-example-responsive-kd_bhn form-control KD_BHN text_input' name='KD_BHN[]' id=KD_BHN" + idrow + " onchange='kd_bhn(this.id)' onfocusout='hitung()'></select></div>";

		var no_bon = no_bon0;
		var kd_bhn = kd_bhn0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = no_bon;
		td3.innerHTML = kd_bhn;
		td4.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' readonly>";
		td5.innerHTML = "<input name='TIPE[]' id=TIPE" + idrow + " type='text' class='form-control TIPE text_input'>";
		td6.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary'>";
		td7.innerHTML = "<input name='BILANGAN[]' id=BILANGAN" + idrow + " type='text' class='form-control BILANGAN text_input' readonly>";
		td8.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input' readonly>";
		td9.innerHTML = "<input name='DEVISI[]' id=DEVISI" + idrow + " type='text' class='form-control DEVISI text_input'>";
		td10.innerHTML = "<input name='KET[]' id=KET" + idrow + " type='text' class='form-control KET text_input'>";
		td11.innerHTML = "<input name='TGL_DIMINTA[]' ocnlick='select()' id=TGL_DIMINTA" + idrow + " type='text' class='date form-control TGL_DIMINTA text_input' data-date-format='dd-mm-yyyy' value='<?php if (isset($_POST["tampilkan"])) {
																																																			echo $_POST["TGL_DIMINTA"];
																																																		} else echo date('d-m-Y'); ?>'>";
		td12.innerHTML = "<input name='SISABON[]' onclick='select()' onkeyup='hitung()' value='0' id=SISABON" + idrow + " type='text' class='form-control SISABON rightJustified text-primary' readonly>";
		td13.innerHTML = "<input name='URGENT[]' id=URGENT" + idrow + " type='checkbox' class='checkbox_container' value='0' unchecked>";
		td14.innerHTML = "<input type='hidden' value='0' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'>" +
			" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
		jumlahdata = 100;
		for (i = 0; i <= jumlahdata; i++) {
			$("#QTY" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
			$("#SISABON" + i.toString()).autoNumeric('init', {
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
		select_no_bon();
		select_kd_bhn();
	});

	function select_no_bon() {
		$('.js-example-responsive-no_bon').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_Pemesanan/getDataAjax_bond') ?>",
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
			placeholder: 'Pilih No Bon',
			minimumInputLength: 0,
			templateResult: format_no_bon,
			templateSelection: formatSelection_no_bon
		});
	}

	function format_no_bon(repo_no_bon) {
		if (repo_no_bon.loading) {
			return repo_no_bon.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_no_bon.NO_BON);
		return $container;
	}

	var tipe = '';
	var na_bhn = '';
	var ket = '';
	var satuan = '';
	var qty = '';
	var sisabon = '';
	var devisi = '';
	var tgl_diminta = '';

	function formatSelection_no_bon(repo_no_bon) {
		tipe = repo_no_bon.TIPE;
		na_bhn = repo_no_bon.NA_BHN;
		ket = repo_no_bon.KET;
		satuan = repo_no_bon.SATUAN;
		qty = repo_no_bon.QTY;
		sisabon = repo_no_bon.SISABON;
		devisi = repo_no_bon.DEVISI;
		tgl_diminta = repo_no_bon.TGL_DIMINTA;
		return repo_no_bon.text;
	}

	function no_bon(x) {
		var q = x.substring(6, 10);
		$('#TIPE' + q).val(tipe);
		$('#NA_BHN' + q).val(na_bhn);
		$('#KET' + q).val(ket);
		$('#SATUAN' + q).val(satuan);
		$('#QTY' + q).val(qty);
		$('#SISABON' + q).val(sisabon);
		$('#DEVISI' + q).val(devisi);
		$('#TGL_DIMINTA' + q).val(tgl_diminta);
	}

	function select_kd_bhn() {
		$('.js-example-responsive-kd_bhn').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_Pemesanan/getDataAjax_bhn') ?>",
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
			placeholder: 'Pilih Barang',
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
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_kd_bhn.KD_BHN);
		return $container;
	}

	// var na_bhn = '';
	var satuan = '';

	function formatSelection_kd_bhn(repo_kd_bhn) {
		// na_bhn = repo_kd_bhn.NA_BHN;
		satuan = repo_kd_bhn.SATUAN;
		return repo_kd_bhn.text;
	}

	function kd_bhn(xx) {
		var qq = xx.substring(6, 10);
		// $('#NA_BHN' + qq).val(na_bhn);
		$('#SATUAN' + qq).val(satuan);
	}
</script>