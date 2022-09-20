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

	.text_input {
		font-size: small;
		color: black;
	}
</style>

<div class="container-fluid">
	<br>
	<div class="alert alert-success alert-container" role="alert">
		<i class="fas fa-university"></i> Input <?php echo $this->session->userdata['judul']; ?>
	</div>
	<form id="cnc" name="cnc" action="<?php echo base_url('admin/Transaksi_PesananRNDPembelian/input_aksi'); ?>" class="form-horizontal needs-validation" method="post" novalidate>
		<div class="form-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Devisi </label>
						</div>
						<div class="col-md-2">
							<select value="" class="js-example-responsive-rn_dev form-control DEVISI" name="DEVISI" id="DEVISI" onchange="rn_dev(this.id)" onfocusout="hitung()" required></select>
						</div>
						<div class="col-md-1">
							<label class="label">No PO </label>
						</div>
						<div class="col-md-2 input-group">
							<input name="NO_PO" id="NO_PO" maxlength="50" type="text" class="form-control NO_PO text_input" onkeypress="return tabE(this,event)" readonly>
							<span class="input-group-btn">
								<a class="btn default" onfocusout="hitung()" id="0" data-target="#modal_no_po" data-toggle="modal" href="#no_po" ><i class="fa fa-search"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">No Bukti </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI" type="text" placeholder='<?php echo $this->session->userdata['bukti']; ?>' readonly>
						</div>
						<div class="col-md-1">
							<label class="label">Article </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NA_BRG" id="NA_BRG" name="NA_BRG" type="text" required>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Tanggal </label>
						</div>
						<div class="col-md-2">
							<input type="text" class="date form-control TGL text_input" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {																																echo $_POST["TGL"];																													} else echo date('d-m-Y'); ?>" onclick="select()">
						</div>
						<div class="col-md-1">
							<label class="label">Tanggal Marketing </label>
						</div>
						<div class="col-md-2">
							<input type="text" class="date form-control TGL_MARKETING text_input" id="TGL_MARKETING" name="TGL_MARKETING" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {																																echo $_POST["TGL_DIMINTA"];																													} else echo date('d-m-Y'); ?>" onclick="select()">
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group row">
						<div class="col-md-1">
							<label class="label">Keterangan </label>
						</div>
						<div class="col-md-2">
							<input class="form-control text_input NOTES" id="NOTES" name="NOTES" type="text" value=''>
						</div>
						<div class="col-md-1">
							<label class="label">Tanggal Diminta </label>
						</div>
						<div class="col-md-2">
							<input type="text" class="date form-control TGL_DIMINTA text_input" id="TGL_DIMINTA" name="TGL_DIMINTA" data-date-format="dd-mm-yyyy" value="<?php if (isset($_POST["tampilkan"])) {																																echo $_POST["TGL_DIMINTA"];																													} else echo date('d-m-Y'); ?>" onclick="select()">
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
								<th width="10px">No</th>
								<th width="150px">Komponen</th>
								<th width="150px">Serian</th>
								<th width="75px">Qty</th>
								<th width="100px">Satuan</th>
								<th width="150px">Keterangan</th>
								<th width="50px"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><input name="REC[]" id="REC0" type="text" value="1" class="form-control REC text_input" onkeypress="return tabE(this,event)" readonly></td>
								<td><input name="NA_BHN[]" id="NA_BHN0" type="text" class="form-control NA_BHN text_input" required></td>
								<td><input name="SERI[]" id="JENIS0" type="text" class="form-control SERI text_input" required></td>
								<td><input name="QTY[]" onclick="select()" onkeyup="hitung()" value="0" id="QTY0" type="text" class="form-control QTY rightJustified text-primary" required></td>
								<td><input name="SATUAN[]" id="SATUAN0" type="text" class="form-control SATUAN text_input"></td>
								<td><input name="KET[]" id="KET0" type="text" class="form-control KET text_input"></td>
								<td>
									<!-- <button type="button" class="btn btn-sm btn-circle btn-outline-danger btn-delete" onclick="">
										<i class="fa fa-fw fa-trash-alt"></i>
									</button> -->
								</td>
							</tr>
						</tbody>
						<tfoot>
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
		<!--tab-->
		<!-- <div class="col-md-12">
			<div class="form-group row">
				<div class="col-md-1">
					<button type="button" onclick="tambah()" class="btn btn-sm btn-success"><i class="fas fa-plus fa-sm md-3"></i> </button>
				</div>
			</div>
		</div> -->
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

<script type="text/javascript">
	$(document).ready(function() {
		$('#no_po').DataTable({
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

<!-- Modal No PO-->
<div id="modal_no_po" class="modal fade" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" style="font-weight: bold; color: black;">Data Beli</h4>
			</div>
			<div class="modal-body">
				<table class='table table-bordered' id='no_po'>
					<thead>	
						<th>No Bukti</th>
						<th>Kode Bagian</th>
						<th>Nama Bagian</th>
						<th>Tanggal</th>
						<th>Area</th>
						<th>Keterangan</th>
					</thead>
					<tbody>
					<?php
						$dr= $this->session->userdata['dr'];
						$sub= $this->session->userdata['sub'];
						$dr= $this->session->userdata['dr'];
						$sql = "SELECT NO_BUKTI AS NO_PO,
								KD_BAG,
								NA_BAG AS NM_BAG,
								TGL AS TGL_PO,
								DR,
								KET AS NA_BRG
							FROM pp
							WHERE TYP LIKE '%RND%'
							AND OK <> '1'
							ORDER BY PER, NO_BUKTI";
						$a = $this->db->query($sql)->result();
						foreach($a as $b ) { 
					?>
						<tr>
							<td class='NBBVAL'><a href="#" class="select_no_po"><?php echo $b->NO_PO;?></a></td>
							<td class='KDBVAL text_input'><?php echo $b->KD_BAG;?></td>
							<td class='NMBVAL text_input'><?php echo $b->NM_BAG;?></td>
							<td class='TGBVAL text_input'><?php echo $b->TGL_PO;?></td>
							<td class='DRBVAL text_input'><?php echo $b->DR;?></td>
							<td class='KEBVAL text_input'><?php echo $b->NA_BRG;?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="close">Close</button>
			</div>
		</div>
	</div>
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
			$("#SISABON" + i.toString()).autoNumeric('init', {
				aSign: '<?php echo ''; ?>',
				vMin: '-999999999.99'
			});
		}
		//MyModal No SO
		$('#modal_no_po').on('show.bs.modal', function(e) {
			target = $(e.relatedTarget);
		});
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

	function hitung() {
		var TOTAL_QTY = 0;
		var total_row = idrow;
		for (i = 0; i < total_row; i++) {
			var qty = parseFloat($('#QTY' + i).val().replace(/,/g, ''));
		};
		$(".QTY").each(function() {
			var val = parseFloat($(this).val().replace(/,/g, ''));
			if (isNaN(val)) val = 0;
			TOTAL_QTY += val;
		});

		$('body').on('click', '.select_no_po', function() {
			var val = $(this).parents("tr").find(".NBBVAL").text();
			target.parents("div").find(".NO_PO").val(val);
			var val = $(this).parents("tr").find(".KDBVAL").text();
			target.parents("div").find(".KD_BAG").val(val);
			var val = $(this).parents("tr").find(".NMBVAL").text();
			target.parents("div").find(".NM_BAG").val(val);
			var val = $(this).parents("tr").find(".TGBVAL").text();
			target.parents("div").find(".TGL_PO").val(val);
			var val = $(this).parents("tr").find(".DRBVAL").text();
			target.parents("div").find(".DR").val(val);
			var val = $(this).parents("tr").find(".KETBVAL").text();
			target.parents("div").find(".NA_BRG").val(val);
			$('#modal_no_po').modal('toggle');
			var no_so = $(this).parents("tr").find(".NBBVAL").text();
		});
		
		if (isNaN(TOTAL_QTY)) TOTAL_QTY = 0;

		$('#TOTAL_QTY').val(numberWithCommas(TOTAL_QTY));

		$('#TOTAL_QTY').autoNumeric('update');
	}

	// function tambah() {

	// 	var x = document.getElementById('datatable').insertRow(idrow + 1);
	// 	var td1 = x.insertCell(0);
	// 	var td2 = x.insertCell(1);
	// 	var td3 = x.insertCell(2);
	// 	var td4 = x.insertCell(3);
	// 	var td5 = x.insertCell(4);
	// 	var td6 = x.insertCell(5);
	// 	var td7 = x.insertCell(6);

	// 	var no_bon0 = "<div class='input-group'><select class='js-example-responsive-no_bon form-control NO_BON text_input' name='NO_BON[]' id=NO_BON" + idrow + " onchange='no_bon(this.id)' onfocusout='hitung()' required></select></div>";
	// 	var kd_bhn0 = "<div class='input-group'><select class='js-example-responsive-kd_bhn form-control KD_BHN text_input' name='KD_BHN[]' id=KD_BHN" + idrow + " onchange='kd_bhn(this.id)' onfocusout='hitung()' required></select></div>";
		
	// 	var no_bon = no_bon0;
	// 	var kd_bhn = kd_bhn0;

	// 	td1.innerHTML = "<input name='REC[]' id=REC" + idrow + " type='text' class='REC form-control text_input' onkeypress='return tabE(this,event)' readonly>";
	// 	td2.innerHTML = "<input name='NA_BHN[]' id=NA_BHN" + idrow + " type='text' class='form-control NA_BHN text_input' required>";
	// 	td3.innerHTML = "<input name='SERI[]' id=SERI" + idrow + " type='text' class='form-control SERI text_input' required>";
	// 	td4.innerHTML = "<input name='QTY[]' onclick='select()' onkeyup='hitung()' value='0' id=QTY" + idrow + " type='text' class='form-control QTY rightJustified text-primary' required>";
	// 	td5.innerHTML = "<input name='SATUAN[]' id=SATUAN" + idrow + " type='text' class='form-control SATUAN text_input' required>";
	// 	td6.innerHTML = "<input name='KET[]' id=KET" + idrow + " type='text' class='form-control KET text_input'>";
	// 	td7.innerHTML = "<input type='hidden' value='0' name='NO_ID[]' id=NO_ID" + idrow + "  class='form-control'>" +
	// 		" <button type='button' class='btn btn-sm btn-circle btn-outline-danger btn-delete' onclick=''> <i class='fa fa-fw fa-trash'></i> </button>";
	// 	jumlahdata = 100;
	// 	for (i = 0; i <= jumlahdata; i++) {
	// 		$("#QTY" + i.toString()).autoNumeric('init', {
	// 			aSign: '<?php echo ''; ?>',
	// 			vMin: '-999999999.99'
	// 		});
	// 		$("#SISABON" + i.toString()).autoNumeric('init', {
	// 			aSign: '<?php echo ''; ?>',
	// 			vMin: '-999999999.99'
	// 		});
	// 	}
	// 	idrow++;
	// 	nomor();
	// 	$(".ronly").on('keydown paste', function(e) {
	// 		e.preventDefault();
	// 		e.currentTarget.blur();
	// 	});
	// 	$('input[type="checkbox"]').on('change', function() {
	// 		this.value ^= 1;
	// 		console.log(this.value)
	// 	});
	// 	select_rn_dev();
	// 	select_dragon();
	// }

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
		select_rn_dev();
		select_dragon();
	});

	function select_rn_dev() {
		$('.js-example-responsive-rn_dev').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_BoronganCNC/getDataAjax_rndev') ?>",
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
			placeholder: 'Pilih Devisi',
			minimumInputLength: 0,
			templateResult: format_rn_dev,
			templateSelection: formatSelection_rn_dev
		});
	}

	function format_rn_dev(repo_rn_dev) {
		if (repo_rn_dev.loading) {
			return repo_rn_dev.text;
		}
		var $container = $(
			"<div class='select2-result-repository clearfix text_input'>" +
			"<div class='select2-result-repository__title text_input'></div>" +
			"</div>"
		);
		$container.find(".select2-result-repository__title").text(repo_rn_dev.DEVISI);
		return $container;
	}

	var nama = '';

	function formatSelection_rn_dev(repo_rn_dev) {
		nama = repo_rn_dev.NOTES;
		return repo_rn_dev.text;
	}

	function rn_dev(x) {
		var q = x.substring(6, 10);
		$('#NOTES' + q).val(nama);
	}

	function select_dragon() {
		$('.js-example-responsive-dragon').select2({
			ajax: {
				url: "<?= base_url('admin/Transaksi_BoronganCNC/getDataAjax_dragon') ?>",
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
		$container.find(".select2-result-repository__title").text(repo_dragon.AREA);
		return $container;
	}

	// var nm_dev = '';

	function formatSelection_dragon(repo_dragon) {
		// nm_dev = repo_rn_dev.NM_DEV;
		return repo_dragon.text;
	}

	function dragon(x) {
		var q = x.substring(6, 10);
	}
</script>