<?php
foreach ($rnd as $rowh) {
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
	<form id="cnc" name="cnc" action="<?php echo base_url('admin/Transaksi_VerifikasiLPB/update_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
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
						<div class="col-md-1">
							<label class="label">Supplier </label>
						</div>
						<div class="col-md-2">
							<input <?php if ($rowh-> VAL == !0) echo 'class="form-control KODES text_input" readonly'; ?> class="form-control text_input KODES" id="KODES" name="KODES" type="text" value="<?php echo $rowh->KODES ?>"readonly>
						</div>
						<div class="col-md-2"></div>
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
							<label class="label">Tgl LPB</label>
						</div>
						<div class="col-md-2">
							<input <?php if ($rowh-> VAL == !0) echo 'class="form-control text_input" readonly'; ?> type="text" class="date form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', strtotime($rowh->TGL, TRUE)); ?>" onclick="select()"readonly>
						</div>
						<div class="col-md-1">
							<label class="label"></label>
						</div>
						<div class="col-md-4">
							<input <?php if ($rowh-> VAL == !0) echo 'class="form-control NAMAS text_input" readonly'; ?> class="form-control text_input NAMAS" id="NAMAS" name="NAMAS" type="text" value="<?php echo $rowh->NAMAS ?>"readonly>
						</div>
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
						<div class="col-md-2">
							<input <?php if ($rowh->VAL == !0) echo 'hidden'; ?> class="form-control text_input PIN2" id="PIN2" name="PIN2" type="password" maxlength="6" value="" placeholder="PIN ...">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">NO PP </label>
						</div>
						<div class="col-md-3">
							<input <?php if ($rowh-> VAL == !0) echo 'class="form-control NO_PO text_input" readonly'; ?> class="form-control text_input NO_PO" id="NO_PO" name="NO_PO" type="text" value="<?php echo $rowh->NO_PO ?>"readonly>
						</div>
						<div class="col-md-4">
							<input <?php if ($rowh-> VAL == !0) echo 'class="form-control ALAMAT text_input" readonly'; ?> class="form-control text_input ALAMAT" id="ALAMAT" name="ALAMAT" type="text" value="<?php echo $rowh->ALAMAT ?>"readonly>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1"></div>
						<div class="col-md-3"></div>
						<div class="col-md-4">
							<input <?php if ($rowh-> VAL == !0) echo 'class="form-control KOTA text_input" readonly'; ?> class="form-control text_input KOTA" id="KOTA" name="KOTA" type="text" value="<?php echo $rowh->KOTA ?>"readonly>
						</div>
					</div>
				</div>
				<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive scrollable">
					<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
						<thead>
							<tr>
								<th width="10px">No</th>
								<th width="50px">Kode</th>
								<th width="150px">Uraian</th>
								<th width="70px">Keterangan</th>
								<th width="50px">Satuan</th>
								<th width="40px">QTY</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 0;
							foreach ($rnd as $row) :
							?>
								<tr>
									<td><input name="REC[]" id="REC<?php echo $no; ?>" value="<?= $row->REC ?>" type="text" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly>
									<input name="NO_ID[]" id="NO_ID<?php echo $no; ?>" value="<?= $row->NO_ID ?>" class="form-control" type="hidden">
									</td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control KD_BHN text_input" readonly'; ?> name="KD_BHN[]" id="KD_BHN<?php echo $no; ?>" value="<?= $row->KD_BHN ?>" type="text" class="form-control KD_BHN text_input"readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control NA_BHN text_input" readonly'; ?> name="NA_BHN[]" id="NA_BHN<?php echo $no; ?>" value="<?= $row->NA_BHN ?>" type="text" class="form-control NA_BHN text_input"readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control KET text_input" readonly'; ?> name="KET[]" id="KET<?php echo $no; ?>" value="<?= $row->KET ?>" type="text" class="form-control KET text_input"readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control SATUAN text_input" readonly'; ?> name="SATUAN[]" id="SATUAN<?php echo $no; ?>" value="<?= $row->SATUAN ?>" type="text" class="form-control SATUAN text_input"readonly></td>
									<td><input <?php if ($rowh->VAL == !0) echo 'class="form-control QTY text_input" readonly'; ?> name="QTY[]" onclick="select()" onkeyup="hitung()" id="QTY<?php echo $no; ?>" value="<?php echo number_format($row->QTY, 2, '.', ','); ?>" type="text" class="form-control QTY rightJustified text-primary"readonly></td>
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
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<br><br>
		<!--tab-->
		<!-- <div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
				</div>
			</div>
		</div> -->
		<br>
		<div class="col-md-12">
			<?php
			if ($rowh->VAL == 0)
			echo '
			<div class="col-xs-9">
				<div class="wells">
					<div class="btn-group cxx">
						<button hidden type="submit" onclick="chekbox()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
						<a type="button" href="javascript:javascript:history.go(-1)" class="btn btn-danger">Cancel</a>
					</div>
					<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Savings.done...</span></h4>
				</div>
			</div>';
			else echo '
			<div class="col-xs-9">
			<div class="wells">
				<div class="btn-group cxx">
					<button hidden type="submit" onclick="chekbox()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
					<a type="button" href="javascript:javascript:history.go(-1)" class="btn btn-danger">Cancel</a>
				</div>
				<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Savings.done...</span></h4>
			</div>
			</div>';
			?>
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

		var no_bon0 = "<div class='input-group'><select class='js-example-responsive-no_bon form-control NO_BON text_input' name='NO_BON[]' id=NO_BON" + idrow + " onchange='no_bon(this.id)' onfocusout='hitung()'></select></div>";
		var kd_bhn0 = "<div class='input-group'><select class='js-example-responsive-kd_bhn form-control KD_BHN text_input' name='KD_BHN[]' id=KD_BHN" + idrow + " onchange='kd_bhn(this.id)' onfocusout='hitung()'></select></div>";

		var no_bon = no_bon0;
		var kd_bhn = kd_bhn0;

		td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
		td2.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' readonly>";
		td3.innerHTML = "<input name='SIZE[]' id=SIZE" + idrow + " type='text' class='form-control SIZE text_input'>";
		td5.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary'>";
		td6.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input' readonly>";
		td7.innerHTML = "<input name='KET1[]' id=KET1" + idrow + " type='text' class='form-control KET1 text_input'>";
		td4.innerHTML = "<input name='TGL_DIMINTA_D[]' ocnlick='select()' id=TGL_DIMINTA_D" + idrow + " type='text' class='date form-control TGL_DIMINTA_D text_input' data-date-format='dd-mm-yyyy' value='<?php if (isset($_POST["tampilkan"])) {} else echo date('d-m-Y'); ?>'>";
		td7.innerHTML = "<input name='GAMBAR1[]' id=GAMBAR1" + idrow + " type='text' class='form-control GAMBAR1 text_input'>";
		td8.innerHTML = "<input type='hidden' value='0' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'>" +
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

	function btVerifikasi() {
		if ($('#PIN2').val() == '<?= $this->session->userdata['pin'] ?>') {
			if (confirm("Yakin Verifikasi?")) {
				window.location.replace("<?php echo base_url('admin/Transaksi_VerifikasiLPB/verifikasi_pin/' . $rowh->ID) ?>");
			}
		} else {
			alert("PIN SALAH");
		}
	}
</script>

<script>
	$(document).ready(function() {
		select_dr();
	});

	function select_dr() {
		$('.js-example-responsive-dragon').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_VerifikasiLPB/getDataAjax_dr') ?>",
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